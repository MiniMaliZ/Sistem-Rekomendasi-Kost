<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserHomeController extends Controller
{
    // =========================================================
    //  DATA STATIS — ganti dengan database nanti
    //  Cukup edit bagian ini saja ketika database sudah siap
    // =========================================================

    private function semuaKost(): array
    {
        return [
            [
                'id'          => 1,
                'nama'        => 'Kost Putri Casa De Flora',
                'tipe'        => 'PUTRI',
                'kota'        => 'Malang',
                'provinsi'    => 'Jawa Timur',
                'harga'       => 1350000,
                'rating'      => 4.9,
                'ulasan'      => 320,
                'foto'        => 'kost1.png',
                'fasilitas'   => ['WiFi', 'Kamar Mandi Dalam', 'AC'],
                'is_baru'     => false,
            ],
            [
                'id'          => 2,
                'nama'        => 'Kost Putri Green Hills',
                'tipe'        => 'PUTRI',
                'kota'        => 'Malang',
                'provinsi'    => 'Jawa Timur',
                'harga'       => 1250000,
                'rating'      => 4.9,
                'ulasan'      => 180,
                'foto'        => 'kost2.png',
                'fasilitas'   => ['WiFi', 'Kamar Mandi Dalam', 'AC'],
                'is_baru'     => false,
            ],
            [
                'id'          => 3,
                'nama'        => 'Kost Putri Central Park Premium',
                'tipe'        => 'PUTRI',
                'kota'        => 'Malang',
                'provinsi'    => 'Jawa Timur',
                'harga'       => 1250000,
                'rating'      => 4.8,
                'ulasan'      => 210,
                'foto'        => 'kost3.png',
                'fasilitas'   => ['WiFi', 'Parkir', 'Dapur Bersama'],
                'is_baru'     => true,
            ],
            [
                'id'          => 4,
                'nama'        => 'Kost Melati Residence',
                'tipe'        => 'CAMPUR',
                'kota'        => 'Malang',
                'provinsi'    => 'Jawa Timur',
                'harga'       => 1400000,
                'rating'      => 4.9,
                'ulasan'      => 127,
                'foto'        => 'kost4.png',
                'fasilitas'   => ['WiFi', 'AC', 'Laundry'],
                'is_baru'     => true,
            ],
            [
                'id'          => 5,
                'nama'        => 'Kost Taman Anggrek',
                'tipe'        => 'CAMPUR',
                'kota'        => 'Solo',
                'provinsi'    => 'Jawa Tengah',
                'harga'       => 1600000,
                'rating'      => 4.8,
                'ulasan'      => 12,
                'foto'        => 'kost5.png',
                'fasilitas'   => ['WiFi', 'AC', 'Parkir'],
                'is_baru'     => true,
            ],
        ];
    }

    // =========================================================
    //  HELPER — format data kost
    // =========================================================

    /** Format harga: 1350000 → "Rp. 1.350.000" */
    private function formatHarga(int $harga): string
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

    /** Siapkan data kost agar siap dikirim ke view */
    private function prepareKost(array $kost, array $favoritIds): array
    {
        $kost['harga_format']   = $this->formatHarga($kost['harga']);
        $kost['lokasi']         = "{$kost['kota']}, {$kost['provinsi']}";
        $kost['fasilitas_tags'] = $this->fasilitasTags($kost['fasilitas']);
        $kost['is_favorit']     = in_array($kost['id'], $favoritIds);
        return $kost;
    }

    // =========================================================
    //  USER STATIS — ganti dengan Auth::user() nanti
    // =========================================================

    private function userDemo(): array
    {
        return [
            'nama'       => 'Kevin Wijaya',
            'nama_depan' => 'Kevin',
            'email'      => 'kevin@demo.com',
            'avatar'     => null, // null = pakai foto default
        ];
    }

    // =========================================================
    //  CONTROLLERS
    // =========================================================

    /**
     * Halaman Home
     */
    public function index()
    {
        $user       = $this->userDemo();
        $semua      = $this->semuaKost();
        $favoritIds = Session::get('favorit_ids', []);

        // Rekomendasi: rating >= 4.8, maks 4 kartu
        $rekomendasi = array_slice(
            array_filter($semua, fn($k) => $k['rating'] >= 4.8),
            0,
            4
        );

        // Terbaru: kost dengan flag is_baru = true, maks 3
        $terbaru = array_slice(
            array_filter($semua, fn($k) => $k['is_baru']),
            0,
            3
        );

        // Siapkan data (tambahkan harga_format, lokasi, fasilitas_tags, is_favorit)
        $rekomendasi = array_map(fn($k) => $this->prepareKost($k, $favoritIds), $rekomendasi);
        $terbaru     = array_map(fn($k) => $this->prepareKost($k, $favoritIds), $terbaru);

        // Ambil kost favorit untuk sidebar kanan
        $kostsById = array_column($semua, null, 'id');
        $favoritKosts = array_map(
            fn($id) => isset($kostsById[$id]) ? $this->prepareKost($kostsById[$id], $favoritIds) : null,
            $favoritIds
        );
        $favoritKosts = array_filter($favoritKosts); // buang null

        $semuaKostJson = json_encode(array_map(fn($k) => $this->prepareKost($k, $favoritIds), $semua));
        
        return view('user.home', compact(
            'user',
            'rekomendasi',
            'terbaru',
            'favoritIds',
            'favoritKosts',
            'semuaKostJson'
        ));
    }

    /**
     * Pencarian kost
     * GET /home/search?q=keyword
     */
    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string|min:1|max:100']);

        $keyword    = strtolower(trim($request->q));
        $user       = $this->userDemo();
        $favoritIds = Session::get('favorit_ids', []);
        $semua      = $this->semuaKost();

        $hasil = array_filter($semua, function ($kost) use ($keyword) {
            return str_contains(strtolower($kost['nama']), $keyword)
                || str_contains(strtolower($kost['kota']), $keyword)
                || str_contains(strtolower($kost['provinsi']), $keyword);
        });

        $hasil = array_map(fn($k) => $this->prepareKost($k, $favoritIds), $hasil);

        return view('user.search', compact('hasil', 'keyword', 'user', 'favoritIds'));
    }

    /**
     * Toggle Favorit — disimpan di Session (tanpa database)
     * POST /favorit/toggle
     */
    public function toggleFavorit(Request $request)
    {
        $request->validate(['kost_id' => 'required|integer']);

        $kostId     = (int) $request->kost_id;
        $favoritIds = Session::get('favorit_ids', []);

        if (in_array($kostId, $favoritIds)) {
            // Sudah ada → hapus
            $favoritIds = array_values(array_filter($favoritIds, fn($id) => $id !== $kostId));
            $isFavorit  = false;
            $pesan      = 'Kost dihapus dari favorit.';
        } else {
            // Belum ada → tambah
            $favoritIds[] = $kostId;
            $isFavorit    = true;
            $pesan        = 'Kost ditambahkan ke favorit!';
        }

        Session::put('favorit_ids', $favoritIds);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'is_favorit' => $isFavorit,
                'pesan'      => $pesan,
            ]);
        }

        return back()->with('success', $pesan);
    }
}
