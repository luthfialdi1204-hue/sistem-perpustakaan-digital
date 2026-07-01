<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    /** Halaman profil admin */
    public function admin()
    {
        return view('Admin.Profil_Admin');
    }

    /**
     * Upload / ganti foto profil.
     * POST /admin/profil/foto  (admin)
     */
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto_profil' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto_profil.required' => 'Pilih foto terlebih dahulu.',
            'foto_profil.image'    => 'File harus berupa gambar.',
            'foto_profil.mimes'    => 'Format foto harus JPG, PNG, atau WebP.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Simpan foto baru ke storage/app/public/foto_profil/
        $path = $request->file('foto_profil')->store('foto_profil', 'public');

        $user->foto_profil = $path;
        $user->save();

        return redirect()->route('admin.profil')->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Hapus foto profil (kembali ke inisial).
     * DELETE /admin/profil/foto  (admin)
     */
    public function hapusFoto(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->foto_profil = null;
        $user->save();

        return redirect()->route('admin.profil')->with('success', 'Foto profil berhasil dihapus.');
    }
}
