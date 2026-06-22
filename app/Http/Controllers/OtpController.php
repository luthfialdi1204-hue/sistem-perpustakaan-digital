<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function __construct(
        protected OtpService $otpService
    ) {}

    /**
     * Tampilkan halaman input OTP.
     * Session harus sudah berisi 'otp_identifier' dan 'otp_email'.
     */
    public function showVerificationForm()
    {
        if (!session('otp_identifier') || !session('otp_email')) {
            return redirect()->route('login')
                ->with('error', 'Sesi tidak valid. Silakan login ulang.');
        }

        return view('auth.otp-verify');
    }

    /**
     * Kirim OTP ke email pengguna.
     * Dipanggil setelah login berhasil (step pertama).
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'email'      => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $result = $this->otpService->generateAndSend(
            identifier: $request->identifier,
            email: $request->email,
        );

        if (!$result['success']) {
            return back()->with('error', $result['message'])
                         ->with('remaining_seconds', $result['remaining_seconds'] ?? null);
        }

        // Simpan info ke session untuk step verifikasi
        session([
            'otp_identifier' => $request->identifier,
            'otp_email'      => $request->email,
        ]);

        return redirect()->route('otp.verify.form')
            ->with('success', $result['message']);
    }

    /**
     * Verifikasi kode OTP yang diinput pengguna.
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $identifier = session('otp_identifier');
        if (!$identifier) {
            return redirect()->route('login.form')
                ->with('error', 'Sesi habis. Silakan ulangi dari awal.');
        }

        $result = $this->otpService->verify(
            identifier: $identifier,
            code: $request->code,
        );

        if (!$result['success']) {
            return back()
                ->with('error', $result['message'])
                ->with('otp_expired', $result['expired'] ?? false);
        }

        // Bersihkan session OTP setelah verifikasi berhasil
        session()->forget(['otp_identifier', 'otp_email']);

        // Lanjutkan ke halaman berikutnya (sesuaikan dengan flow kalian)
        return redirect()->route('dashboard')
            ->with('success', 'Verifikasi berhasil! Selamat datang.');
    }

    /**
     * Kirim ulang OTP (hanya bisa jika OTP aktif sudah expired).
     */
    public function resend(Request $request)
    {
        $identifier = session('otp_identifier');
        $email      = session('otp_email');

        if (!$identifier || !$email) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak valid.',
            ], 401);
        }

        $result = $this->otpService->resend($identifier, $email);

        return response()->json($result, $result['success'] ? 200 : 429);
    }
}