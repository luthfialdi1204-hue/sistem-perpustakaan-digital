<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('Lupa_Password');
    }

    public function showOtpForm()
    {
        $role = request('role', 'mahasiswa');
        $identifier = request('id', '');
        $label = $role === 'admin' ? 'NIP' : 'NIM';
        return view('Verifikasi_Otp', [
            'email' => 'email_akun@kampus.ac.id',
            'role' => $role,
            'identifier' => $identifier,
            'label' => $label,
        ]);
    }

    public function showResetForm()
    {
        return view('Reset_Password', [
            'email' => request('email', 'email_akun@kampus.ac.id'),
        ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:mahasiswa,admin'],
            'identifier' => ['required', 'digits_between:6,20'],
        ]);

        $role = $request->input('role');
        $identifier = $request->input('identifier');
        $column = $role === 'mahasiswa' ? 'nim' : 'nip';

        if (!Schema::hasColumn('users', $column)) {
            return back()->withErrors([
                'forgot' => "Kolom {$column} belum tersedia di tabel users. Jalankan migration terbaru.",
            ]);
        }

        $user = User::where($column, $identifier)->first();
        if (!$user) {
            return back()->withErrors([
                'forgot' => $role === 'mahasiswa'
                    ? 'NIM tidak ditemukan.'
                    : 'NIP tidak ditemukan.',
            ])->withInput();
        }

        $otp = (string) random_int(100000, 999999);

        session([
            'forgot_password' => [
                'email' => $user->email,
                'otp' => $otp,
                'verified' => false,
                'role' => $role,
                'identifier' => $identifier,
                'expires_at' => now()->addMinutes(5)->timestamp,
            ],
        ]);

        Mail::raw("Kode OTP reset password Anda adalah: {$otp}. Berlaku 5 menit.", function ($message) use ($user) {
            $message->to($user->email)->subject('OTP Reset Password');
        });

        return redirect()->route('password.forgot.otp')->with('success', 'Kode OTP sudah dikirim ke email akun.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $state = session('forgot_password');
        if (!$state || empty($state['email']) || empty($state['otp']) || empty($state['expires_at'])) {
            return redirect()->route('password.forgot')->withErrors(['email' => 'Silakan kirim OTP terlebih dahulu.']);
        }

        if (now()->timestamp > (int) $state['expires_at']) {
            session()->forget('forgot_password');
            return redirect()->route('password.forgot')->withErrors(['email' => 'OTP sudah kadaluarsa. Silakan kirim ulang OTP.']);
        }

        if ($request->otp !== $state['otp']) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        $state['verified'] = true;
        session(['forgot_password' => $state]);

        return redirect()->route('password.forgot.reset')->with('success', 'OTP valid. Silakan buat password baru.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $state = session('forgot_password');
        if (!$state || empty($state['verified']) || empty($state['email'])) {
            return redirect()->route('password.forgot.otp')->withErrors(['otp' => 'OTP belum diverifikasi.']);
        }

        $user = User::where('email', $state['email'])->first();
        if (!$user) {
            session()->forget('forgot_password');
            return back()->withErrors(['password' => 'Pengguna tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget('forgot_password');

        return redirect()->route('login.form')->with('success', 'Password berhasil direset. Silakan login kembali.');
    }
}
