<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private const UPLOAD_DIR = 'uploads/profile';

    public function index()
    {
        $user = Auth::user();
        $user = User::select('id_user', 'nama', 'email', 'foto_url', 'role')
            ->where('id_user', $user->id_user)
            ->firstOrFail();

        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('user', 'email')->ignore($user->id_user, 'id_user'),
            ],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan akun lain.',
        ]);

        User::where('id_user', $user->id_user)->update($validated);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'profile_photo.required' => 'Pilih foto terlebih dahulu.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $uploadPath = public_path(self::UPLOAD_DIR);
        File::ensureDirectoryExists($uploadPath);

        if ($user->foto_url && ! filter_var($user->foto_url, FILTER_VALIDATE_URL)) {
            $oldPath = $uploadPath . DIRECTORY_SEPARATOR . basename($user->foto_url);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $file = $request->file('profile_photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move($uploadPath, $filename);

        if (! File::exists($uploadPath . DIRECTORY_SEPARATOR . $filename)) {
            return back()->withErrors([
                'profile_photo' => 'Gagal menyimpan foto. Periksa izin folder public/uploads/profile.',
            ]);
        }

        User::where('id_user', $user->id_user)->update(['foto_url' => $filename]);

        return redirect()
            ->route('profile')
            ->with('success', 'Foto profil berhasil diperbarui.')
            ->with('avatar_refresh', time());
    }
}
