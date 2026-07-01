<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function showForm(Request $request)
    {
        $role = $request->query('role', 'mahasiswa');
        if (! in_array($role, ['mahasiswa', 'admin'], true)) {
            $role = 'mahasiswa';
        }

        return view('auth.Lupa_Password', [
            'role' => $role,
            'label' => $role === 'admin' ? 'NIP' : 'NIM',
            'identifier' => $request->query('id', ''),
        ]);
    }

    public function showOtpForm()
    {
        $state = session('forgot_password');
        if (! $state || empty($state['identifier']) || empty($state['email'])) {
            return redirect()->route('password.forgot', ['role' => $state['role'] ?? 'mahasiswa'])
                ->withErrors(['forgot' => 'Silakan kirim OTP terlebih dahulu.']);
        }

        $role = $state['role'] ?? 'mahasiswa';

        return view('auth.otp-verify', [
            'email' => $state['email'] ?? null,
            'identifier' => $state['identifier'] ?? '',
            'label' => $role === 'admin' ? 'NIP' : 'NIM',
            'role' => $role,
        ]);
    }
    
    public function showResetForm()
    {
        $state = session('forgot_password');
        if (! $state || empty($state['verified'])) {
            return redirect()->route('password.forgot.otp')
                ->withErrors(['otp' => 'OTP belum diverifikasi.']);
        }

        return view('auth.Reset_Password', [
            'email' => $state['email'] ?? null,
        ]);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'in:mahasiswa,admin'],
            'identifier' => ['required', 'digits_between:6,20'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $role = $request->input('role');
        $identifier = $request->input('identifier');
        $column = $role === 'mahasiswa' ? 'nim' : 'nip';

        // Kita pakai tabel legacy `user`. Kolom nim/nip sudah pasti ada.
        $user = User::where($column, (int) $identifier)->first();

        if (! $user) {
            return back()->withErrors([
                'forgot' => $role === 'mahasiswa'
                    ? 'NIM tidak ditemukan.'
                    : 'NIP tidak ditemukan.',
            ])->withInput();
        }

        if ($role === 'mahasiswa' && ! $user->isMahasiswa()) {
            return back()->withErrors(['forgot' => 'Akun bukan mahasiswa.'])->withInput();
        }

        if ($role === 'admin' && ! $user->isAdmin()) {
            return back()->withErrors(['forgot' => 'Akun bukan admin.'])->withInput();
        }

        if (empty($user->email)) {
            return back()->withErrors(['forgot' => 'Email akun tidak terdaftar. Hubungi admin.'])->withInput();
        }

        // Gunakan OtpService untuk generate dan kirim OTP (tipe: password_reset)
        $result = $this->otpService->generateAndSend($identifier, $user->email, 'password_reset');

        if (! $result['success']) {
            if (isset($result['remaining_seconds'])) {
                return back()->withErrors([
                    'forgot' => $result['message'],
                ])->withInput();
            }

            Log::error('Gagal kirim OTP: '.($result['error'] ?? $result['message'] ?? 'unknown'));

            if (config('app.env') === 'local' && !empty($result['local_code'])) {
                session([
                    'forgot_password' => [
                        'email' => $user->email,
                        'verified' => false,
                        'role' => $role,
                        'identifier' => $identifier,
                        'user_id' => $user->id_user,
                        'expires_at' => now()->addMinutes(5)->toIso8601String(),
                    ],
                ]);

                return redirect()->route('password.forgot.otp')
                    ->with('success', 'Gagal mengirim email asli. (Mode Lokal: Gunakan kode OTP: ' . $result['local_code'] . ')');
            }

            return back()->withErrors([
                'forgot' => 'Gagal mengirim email OTP. Periksa konfigurasi mail server dan coba lagi.',
            ])->withInput();
        }

        $successMessage = 'Kode OTP sudah dikirim ke email akun.';

        // Simpan state forgot_password tanpa menyimpan kode OTP langsung
        session([
            'forgot_password' => [
                'email' => $user->email,
                'verified' => false,
                'role' => $role,
                'identifier' => $identifier,
                'user_id' => $user->id_user,
                'expires_at' => $result['expires_at'] ?? now()->addMinutes(5)->toIso8601String(),
            ],
        ]);

        return redirect()->route('password.forgot.otp')->with('success', $successMessage);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $state = session('forgot_password');
        if (! $state || empty($state['email']) || empty($state['identifier']) || empty($state['expires_at'])) {
            return redirect()->route('password.forgot', ['role' => $state['role'] ?? 'mahasiswa'])
                ->withErrors(['forgot' => 'Silakan kirim OTP terlebih dahulu.']);
        }

        // Verifikasi menggunakan OtpService (tipe: password_reset)
        $identifier = $state['identifier'];
        $result = $this->otpService->verify($identifier, $request->otp, 'password_reset');

        if (! $result['success']) {
            if (! empty($result['expired'])) {
                session()->forget('forgot_password');
                return redirect()->route('password.forgot', ['role' => $state['role'] ?? 'mahasiswa'])
                    ->withErrors(['forgot' => 'OTP sudah kadaluarsa. Silakan kirim ulang OTP.']);
            }

            return back()->withErrors(['otp' => $result['message'] ?? 'Kode OTP tidak valid.'])->withInput();
        }

        $state['verified'] = true;
        session(['forgot_password' => $state]);

        return redirect()->route('password.forgot.reset')
            ->with('success', 'OTP valid. Silakan buat password baru.');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'min:6', 'max:255', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $state = session('forgot_password');
        if (! $state || empty($state['verified']) || empty($state['email'])) {
            return redirect()->route('password.forgot.otp')
                ->withErrors(['otp' => 'OTP belum diverifikasi.']);
        }

        // Lebih aman: pakai user_id dari session state.
        $userId = $state['user_id'] ?? null;
        $user = $userId ? User::where('id_user', (int) $userId)->first() : null;
        if (! $user) {
            $user = User::where('email', $state['email'])->first();
        }
        if (! $user) {
            session()->forget('forgot_password');

            return back()->withErrors(['password' => 'Pengguna tidak ditemukan.']);
        }

        // Tabel `user` menyimpan kata_sandi plaintext (varchar(255)).
        $user->password = $request->password;
        $user->save();

        session()->forget('forgot_password');

        return redirect()->route('login.form')
            ->with('success', 'Password berhasil direset. Silakan login kembali.');
    }
}
