<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Riwayat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Tampilkan halaman history.
     * Guest tetap bisa akses — view yang menangani tampilan warning.
     */
    public function index(Request $request)
    {
        // Jika guest, kirim koleksi kosong + flag guest
        if (!Auth::check()) {
            return view('user.history', [
                'title'    => 'History - roomor',
                'riwayat'  => collect(),
                'isGuest'  => true,
                'filter'   => 'semua',
            ]);
        }

        $user   = Auth::user();
        $filter = $request->query('filter', 'semua');

        $query = Riwayat::with(['kost', 'kost.fotoKost', 'kost.feedback'])
            ->where('id_user', $user->id_user)
            ->orderByDesc('created_at');

        if ($filter === 'hari_ini') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === '7_hari') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        }

        $riwayat = $query->get();

        return view('user.history', [
            'title'   => 'History - roomor',
            'riwayat' => $riwayat,
            'isGuest' => false,
            'filter'  => $filter,
        ]);
    }
}
