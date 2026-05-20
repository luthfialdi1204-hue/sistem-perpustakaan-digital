<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Halaman_Masuk extends Controller
{
    private function passwordMatches(string $input, ?string $stored): bool
    {
        if ($stored === null || $stored === '') {
            return false;
        }

        // Jika password di tabel `user` ternyata sudah hash (opsional), tetap bisa login.
        if (strlen($stored) >= 50 && Hash::check($input, $stored)) {
            return true;
        }

        // Legacy tabel `user` umumnya menyimpan plaintext.
        return hash_equals($stored, $input);
    }

    public function showLoginForm(Request $request)
    {
        return view('auth.Halaman_Masuk');
    }

    public function loginMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => ['required', 'digits_between:6,20'],
            'password' => ['required', 'string'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.digits_between' => 'NIM harus berupa angka dengan panjang 6-20 digit.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'mahasiswa')->withInput();
        }

        $user = User::where('nim', $request->nim)->first();

        if (! $user || ! $this->passwordMatches($request->password, $user->password)) {
            return back()
                ->withErrors(['nim' => 'NIM atau kata sandi tidak sesuai.'], 'mahasiswa')
                ->withInput($request->only('nim'));
        }

        if (! $user->isMahasiswa()) {
            return back()
                ->withErrors(['nim' => 'Akun ini bukan akun mahasiswa.'], 'mahasiswa')
                ->withInput($request->only('nim'));
        }

        // Tabel `user` tidak punya remember_token, jadi jangan gunakan remember.
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('mahasiswa.beranda'));
    }

    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => ['required', 'digits_between:6,20'],
            'password' => ['required', 'string'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits_between' => 'NIP harus berupa angka dengan panjang 6-20 digit.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'admin')->withInput();
        }

        $user = User::where('nip', $request->nip)->first();

        if (! $user || ! $this->passwordMatches($request->password, $user->password)) {
            return back()
                ->withErrors(['nip' => 'NIP atau kata sandi tidak sesuai.'], 'admin')
                ->withInput($request->only('nip'));
        }

        if (! $user->isAdmin()) {
            return back()
                ->withErrors(['nip' => 'Akun ini bukan akun admin.'], 'admin')
                ->withInput($request->only('nip'));
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/Dashboard_Admin');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda berhasil keluar.');
    }
}
