<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use App\Models\Kost;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    /**
     * Toggle favorit status for a kost
     */
    public function toggle(Kost $kost)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $favorit = Favorit::where('id_user', $user->id_user)
            ->where('id_kost', $kost->id_kost)
            ->first();

        if ($favorit) {
            // Remove from favorite
            $favorit->delete();
        } else {
            // Add to favorite
            Favorit::create([
                'id_user' => $user->id_user,
                'id_kost' => $kost->id_kost,
            ]);
        }

        return back();
    }
}
