<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    /**
     * Daftar kecamatan beserta jenis wilayahnya.
     * 'kota' → Kota Malang, 'kab' → Kab. Malang
     */
    private function kecamatanMap(): array
    {
        return [
            // Kota Malang
            'Lowokwaru'     => 'kota',
            'Blimbing'      => 'kota',
            'Klojen'        => 'kota',
            'Sukun'         => 'kota',
            'Kedungkandang' => 'kota',
            // Kabupaten Malang
            'Karang Ploso'  => 'kab',
            'Dau'           => 'kab',
        ];
    }

    /**
     * Ekstrak nama kecamatan dari nama_kost dan kembalikan string lokasi
     * dalam format "Kecamatan, Kota Malang" atau "Kecamatan, Kab. Malang".
     */
    private function getLokasi(string $namaKost): string
    {
        foreach ($this->kecamatanMap() as $kecamatan => $jenis) {
            if (stripos($namaKost, $kecamatan) !== false) {
                $wilayah = $jenis === 'kota' ? 'Kota Malang' : 'Kab. Malang';
                return "{$kecamatan}, {$wilayah}";
            }
        }

        return 'Malang';
    }

    /**
     * Ekstrak nama kecamatan saja dari nama_kost (dipakai untuk filter).
     */
    private function getKecamatan(string $namaKost): string
    {
        foreach ($this->kecamatanMap() as $kecamatan => $jenis) {
            if (stripos($namaKost, $kecamatan) !== false) {
                return $kecamatan;
            }
        }

        return '';
    }

    /**
     * Hitung rata-rata rating dari relasi feedback.
     */
    private function getRating(Kost $kost): string
    {
        $feedbacks = $kost->feedback;
        if ($feedbacks->isEmpty()) {
            return '0.0';
        }
        return (string) round($feedbacks->avg('rating'), 1);
    }

    /**
     * Tampilkan halaman favorit (guest & auth)
     */
    public function index()
    {
        $user    = Auth::user();
        $isGuest = !$user;

        if ($isGuest) {
            return view('user.favorite', [
                'isGuest'      => true,
                'totalFavorit' => 0,
                'favoritKosts' => [],
                'favoritIds'   => [],
            ]);
        }

        // Ambil semua id kost yang difavoritkan user ini
        $favoritIds = Favorit::where('id_user', $user->id_user)
            ->pluck('id_kost')
            ->toArray();

        if (empty($favoritIds)) {
            return view('user.favorite', [
                'isGuest'      => false,
                'totalFavorit' => 0,
                'favoritKosts' => [],
                'favoritIds'   => [],
            ]);
        }

        // Ambil data kost beserta foto dan feedback
        $kosts = Kost::with(['fotoKost', 'feedback'])
            ->whereIn('id_kost', $favoritIds)
            ->get();

        $favoritKosts = $kosts->map(function ($kost) {
            // Foto — kolom foto_bangunan di tabel foto_kost
            $foto = $kost->fotoKost?->foto_bangunan ?? 'images/kost1.png';

            // Tipe badge & label — nilai enum DB: 'Kos Putri', 'Kos Putra', 'Kos Campur'
            $tipeMap = [
                'Kos Putri'  => ['badge' => 'badge-putri',  'label' => 'PUTRI'],
                'Kos Putra'  => ['badge' => 'badge-putra',  'label' => 'PUTRA'],
                'Kos Campur' => ['badge' => 'badge-campur', 'label' => 'CAMPUR'],
            ];
            $tipeKos   = $kost->tipe_kos ?? 'Kos Campur';
            $tipeBadge = $tipeMap[$tipeKos]['badge'] ?? 'badge-campur';
            $tipeLabel = $tipeMap[$tipeKos]['label'] ?? 'Kos Campur';

            // Fasilitas tags (ambil dari fasilitas_kamar, maks 3 tampil)
            $fasilitasRaw  = $kost->fasilitas_kamar ?? '';
            $fasilitasList = array_filter(array_map('trim', explode(',', $fasilitasRaw)));
            $fasilitasTags = array_slice($fasilitasList, 0, 3);
            $extraCount    = max(0, count($fasilitasList) - 3);

            // Kecamatan untuk filter dropdown
            $kecamatan = $this->getKecamatan($kost->nama_kost);

            // Lokasi tampil: "Kecamatan, Kota/Kab. Malang"
            $lokasi = $this->getLokasi($kost->nama_kost);

            return [
                'id'             => $kost->id_kost,
                'nama'           => $kost->nama_kost,
                'harga'          => $kost->harga,
                'harga_format'   => $kost->harga_formatted,
                'tipe_kos'       => $tipeKos,
                'tipe_badge'     => $tipeBadge,
                'tipe_label'     => $tipeLabel,
                'lokasi'         => $lokasi,
                'kecamatan'      => $kecamatan,
                'foto'           => $foto,
                'rating'         => $this->getRating($kost),
                'fasilitas_tags' => array_values($fasilitasTags),
                'extra_count'    => $extraCount,
            ];
        })->values()->toArray();

        return view('user.favorite', [
            'isGuest'      => false,
            'totalFavorit' => count($favoritKosts),
            'favoritKosts' => $favoritKosts,
            'favoritIds'   => $favoritIds,
        ]);
    }

    /**
     * Toggle favorit via AJAX — mengembalikan JSON
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $request->validate(['kost_id' => ['required', 'integer']]);

        $kostId  = $request->input('kost_id');
        $favorit = Favorit::where('id_user', $user->id_user)
            ->where('id_kost', $kostId)
            ->first();

        if ($favorit) {
            $favorit->delete();
            $isFavorit = false;
        } else {
            Favorit::create([
                'id_user' => $user->id_user,
                'id_kost' => $kostId,
            ]);
            $isFavorit = true;
        }

        // Kembalikan daftar id favorit terbaru
        $favoritIds = Favorit::where('id_user', $user->id_user)
            ->pluck('id_kost')
            ->toArray();

        return response()->json([
            'success'     => true,
            'is_favorit'  => $isFavorit,
            'favorit_ids' => $favoritIds,
        ]);
    }

    /**
     * Hapus satu kost dari favorit (non-AJAX)
     */
    public function destroy($kostId)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        Favorit::where('id_user', $user->id_user)
            ->where('id_kost', $kostId)
            ->delete();

        return redirect()->route('user.favorit')
            ->with('success', 'Kost berhasil dihapus dari favorit.');
    }
}
