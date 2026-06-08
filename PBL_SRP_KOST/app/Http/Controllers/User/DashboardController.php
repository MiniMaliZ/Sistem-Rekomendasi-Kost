<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Favorit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // =========================================================
    //  HELPER — format data kost
    // =========================================================

    /** Format harga: 1350000 → "Rp. 1.350.000" */
    private function formatHarga(float $harga): string
    {
        return 'Rp. ' . number_format($harga, 0, ',', '.');
    }

    /** Ambil 3 fasilitas pertama, sisanya jadi "N+" */
    private function fasilitasTags(array $fasilitas): array
    {
        $visible = array_slice($fasilitas, 0, 3);
        $extra   = count($fasilitas) - 3;
        if ($extra > 0) {
            $visible[] = "{$extra}+";
        }
        return $visible;
    }

    /**
     * Gabungkan semua kolom fasilitas menjadi satu array bersih.
     * Kolom: fasilitas_umum, fasilitas_kamar, fasilitas_kamar_mandi, fasilitas_parkir
     */
    private function parseFasilitas(Kost $kost): array
    {
        $cols = [
            $kost->fasilitas_umum,
            $kost->fasilitas_kamar,
            $kost->fasilitas_kamar_mandi,
            $kost->fasilitas_parkir,
        ];

        $all = [];
        foreach ($cols as $col) {
            if (!empty($col)) {
                foreach (explode(',', $col) as $item) {
                    $trimmed = trim($item);
                    if ($trimmed !== '') {
                        $all[] = $trimmed;
                    }
                }
            }
        }

        return array_values(array_unique($all));
    }

    /**
     * Hitung rata-rata rating dari relasi feedback.
     * Kost model sudah eager-loaded feedbacks sebelum memanggil ini.
     */
    private function getRating(Kost $kost): float
    {
        $feedbacks = $kost->feedback;
        if ($feedbacks->isEmpty()) {
            return 0.0;
        }
        return round($feedbacks->avg('rating'), 1);
    }

    /**
     * Siapkan data kost agar siap dikirim ke view.
     * Output array-nya SAMA PERSIS dengan format lama supaya view tidak perlu diubah.
     */
    private function prepareKost(Kost $kost, array $favoritIds): array
    {
        $fasilitas = $this->parseFasilitas($kost);

        return [
            'id'             => $kost->id_kost,
            'nama'           => $kost->nama_kost,
            'tipe'           => strtoupper(str_replace('Kos ', '', $kost->tipe_kos)),
            'kota'           => 'Malang',
            'provinsi'       => 'Jawa Timur',
            'harga'          => (float) $kost->harga,
            'harga_format'   => $this->formatHarga((float) $kost->harga),
            'rating'         => $this->getRating($kost),
            'ulasan'         => $kost->feedback->count(),
            'foto'           => $kost->fotoKost?->foto_bangunan_url ?? asset('images/no-image.jpg'),
            'lokasi'         => $this->getLokasi($kost->nama_kost),
            'fasilitas'      => $fasilitas,
            'fasilitas_tags' => $this->fasilitasTags($fasilitas),
            'is_baru'        => false,
            'is_favorit'     => in_array($kost->id_kost, $favoritIds),
        ];
    }

    // =========================================================
    //  HELPER — data user dari Auth
    // =========================================================

    private function getUserData(): array
    {
        if (!Auth::check()) {
            return [
                'nama'       => 'Tamu',
                'nama_depan' => 'Tamu',
                'email'      => '',
                'avatar'     => null,
            ];
        }

        $user      = Auth::user();
        $namaDepan = explode(' ', $user->nama)[0];

        return [
            'nama'       => $user->nama,
            'nama_depan' => $namaDepan,
            'email'      => $user->email,
            'avatar'     => $user->foto_url,
        ];
    }

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

        // Fallback jika tidak ditemukan
        return 'Malang';
    }

    /**
     * Ekstrak nama kecamatan saja dari nama_kost (dipakai jika dibutuhkan).
     */
    private function getKecamatan(string $namaKost): string
    {
        foreach ($this->kecamatanMap() as $kecamatan => $jenis) {
            if (stripos($namaKost, $kecamatan) !== false) {
                return $kecamatan;
            }
        }

        return 'Malang';
    }

    /**
     * Ambil array id_kost yang difavoritkan user yang sedang login.
     * Guest → array kosong.
     */
    private function getFavoritIds(): array
    {
        if (!Auth::check()) {
            return [];
        }

        return Favorit::where('id_user', Auth::id())
            ->pluck('id_kost')
            ->map(fn($id) => (int) $id)
            ->toArray();
    }

    // =========================================================
    //  CONTROLLERS
    // =========================================================

    /**
     * Halaman Home — GET /dashboard
     */
    public function index()
    {
        $user       = $this->getUserData();
        $favoritIds = $this->getFavoritIds();

        // Eager-load feedback sekali saja untuk menghindari N+1
        $semuaKostModels = Kost::with(['feedback', 'fotoKost'])->get();

        // Rekomendasi: rating >= 4.0, maks 4
        $rekomendasi = $semuaKostModels
            ->filter(fn(Kost $k) => $this->getRating($k) >= 4.0)
            ->take(4)
            ->map(fn(Kost $k) => $this->prepareKost($k, $favoritIds))
            ->values()
            ->toArray();

        // Fallback: jika kurang dari 4, pakai kost terbaru
        if (count($rekomendasi) < 4) {
            $rekomendasi = $semuaKostModels
                ->sortByDesc('id_kost')
                ->take(4)
                ->map(fn(Kost $k) => $this->prepareKost($k, $favoritIds))
                ->values()
                ->toArray();
        }

        // Terbaru: 3 kost dengan id_kost tertinggi (= paling baru dimasukkan)
        $terbaru = $semuaKostModels
            ->sortByDesc('id_kost')
            ->take(3)
            ->map(fn(Kost $k) => $this->prepareKost($k, $favoritIds))
            ->values()
            ->toArray();

        // Favorit sidebar: kost yang ada di daftar favorit user
        $favoritKosts = [];
        if (!empty($favoritIds)) {
            $favoritKosts = $semuaKostModels
                ->whereIn('id_kost', $favoritIds)
                ->map(fn(Kost $k) => $this->prepareKost($k, $favoritIds))
                ->values()
                ->toArray();
        }

        // JSON untuk bridge ke JavaScript di dashboard.blade.php
        $semuaKostJson = json_encode(
            $semuaKostModels
                ->map(fn(Kost $k) => $this->prepareKost($k, $favoritIds))
                ->values()
                ->toArray()
        );

        // Nama variabel compact SAMA PERSIS dengan sebelumnya — view tidak perlu diubah
        return view('user.dashboard', compact(
            'user',
            'rekomendasi',
            'terbaru',
            'favoritIds',
            'favoritKosts',
            'semuaKostJson'
        ));
    }

    /**
     * Pencarian kost — GET /dashboard/search?q=keyword
     */
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1|max:100']);

        $keyword    = strtolower(trim($request->q));
        $user       = $this->getUserData();
        $favoritIds = $this->getFavoritIds();

        $hasil = Kost::with(['feedback', 'fotoKost'])
            ->where('nama_kost', 'like', "%{$keyword}%")
            ->get()
            ->map(fn(Kost $k) => $this->prepareKost($k, $favoritIds))
            ->values()
            ->toArray();

        return view('user.search', compact('hasil', 'keyword', 'user', 'favoritIds'));
    }

    /**
     * Toggle Favorit — POST /favorit/toggle (AJAX)
     * Sekarang menyimpan ke tabel favorit di database, bukan session.
     */
    public function toggleFavorit(Request $request)
    {
        $request->validate(['kost_id' => 'required|integer']);

        $kostId = (int) $request->kost_id;
        $userId = (int) Auth::id();

        $existing = Favorit::where('id_user', $userId)
            ->where('id_kost', $kostId)
            ->first();

        if ($existing) {
            $existing->delete();
            $isFavorit = false;
            $pesan     = 'Kost dihapus dari favorit.';
        } else {
            Favorit::create([
                'id_user' => $userId,
                'id_kost' => $kostId,
            ]);
            $isFavorit = true;
            $pesan     = 'Kost ditambahkan ke favorit!';
        }

        // Ambil ulang semua favorit ids user (untuk sync di frontend)
        $favoritIds = Favorit::where('id_user', $userId)
            ->pluck('id_kost')
            ->map(fn($id) => (int) $id)
            ->toArray();

        if ($request->expectsJson()) {
            return response()->json([
                'success'     => true,
                'is_favorit'  => $isFavorit,
                'pesan'       => $pesan,
                'favorit_ids' => $favoritIds,
            ]);
        }

        return back()->with('success', $pesan);
    }
}
