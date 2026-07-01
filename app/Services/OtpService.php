<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpService
{
    /**
     * Durasi OTP dalam menit
     */
    const OTP_EXPIRY_MINUTES = 5;

    /**
     * Generate OTP baru dan kirim ke email.
     * Jika ada OTP sebelumnya, akan diganti dengan OTP baru.
     *
     * @param string $identifier  Email atau NIM pengguna
     * @param string $email       Alamat email tujuan
     * @param string $type        Jenis OTP (default: email_verification)
     * @return array
     */
    public function generateAndSend(string $identifier, string $email, string $type = 'email_verification'): array
    {
        // Hapus semua OTP lama (expired/sudah dipakai/aktif) untuk identifier ini
        OtpCode::where('identifier', $identifier)
            ->where('type', $type)
            ->delete();

        // Generate kode OTP 6 digit unik
        $code = $this->generateUniqueCode();

        // Simpan ke database
        $otpRecord = OtpCode::create([
            'identifier' => $identifier,
            'code'       => $code,
            'type'       => $type,
            'is_used'    => false,
            'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'created_at' => Carbon::now(),
        ]);

        // Kirim email: gunakan PHPMailer jika dikonfigurasi di .env, fallback ke Laravel Mail
        try {
            if (env('MAIL_MAILER') === 'phpmailer' && class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                // Render view email menjadi string HTML
                $body = view('emails.otp', [
                    'code' => $code,
                    'expiryMinutes' => self::OTP_EXPIRY_MINUTES,
                ])->render();

                // Jika ada host, gunakan SMTP
                if (env('MAIL_HOST')) {
                    $mail->isSMTP();
                    $mail->Host = env('MAIL_HOST');
                    $mail->Port = (int) env('MAIL_PORT', 25);
                    $mail->SMTPAuth = env('MAIL_USERNAME') ? true : false;
                    $mail->Username = env('MAIL_USERNAME');
                    $mail->Password = env('MAIL_PASSWORD');

                    $encryption = env('MAIL_ENCRYPTION', env('MAIL_SCHEME'));
                    if ($encryption) {
                        $mail->SMTPSecure = $encryption;
                    }
                }

                $fromAddress = env('MAIL_FROM_ADDRESS', 'hello@example.com');
                $fromName = env('MAIL_FROM_NAME', config('app.name'));

                $mail->setFrom($fromAddress, $fromName);
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = '🔐 Kode OTP Perpustakaan Digital Polibatam';
                $mail->Body = $body;
                $mail->AltBody = strip_tags($body);

                $mail->send();
            } else {
                Mail::to($email)->send(new OtpMail($code, self::OTP_EXPIRY_MINUTES));
            }
        } catch (\Exception $e) {
            // Hapus record jika gagal kirim email (kecuali di mode lokal/dev)
            if (config('app.env') !== 'local') {
                $otpRecord->delete();
            }
            return [
                'success' => false,
                'message' => 'Gagal mengirim email OTP. Silakan coba lagi.',
                'error'   => $e->getMessage(),
                'local_code' => config('app.env') === 'local' ? $code : null,
            ];
        }

        return [
            'success'         => true,
            'message'         => 'Kode OTP berhasil dikirim ke email Anda.',
            'expires_in'      => self::OTP_EXPIRY_MINUTES * 60, // dalam detik
            'expires_at'      => $otpRecord->expires_at->toIso8601String(),
        ];
    }

    /**
     * Verifikasi kode OTP yang diinput pengguna.
     *
     * @param string $identifier
     * @param string $code
     * @param string $type
     * @return array
     */
    public function verify(string $identifier, string $code, string $type = 'email_verification'): array
    {
        $otp = OtpCode::where('identifier', $identifier)
            ->where('type', $type)
            ->where('code', $code)
            ->latest('created_at')
            ->first();

        // OTP tidak ditemukan
        if (!$otp) {
            return [
                'success' => false,
                'message' => 'Kode OTP tidak valid.',
            ];
        }

        // OTP sudah dipakai
        if ($otp->is_used) {
            return [
                'success' => false,
                'message' => 'Kode OTP sudah pernah digunakan.',
            ];
        }

        // OTP sudah expired
        if ($otp->isExpired()) {
            return [
                'success' => false,
                'message' => 'Kode OTP sudah kedaluwarsa. Silakan minta OTP baru.',
                'expired' => true,
            ];
        }

        // OTP valid — tandai sebagai sudah dipakai
        $otp->update(['is_used' => true]);

        return [
            'success' => true,
            'message' => 'Verifikasi OTP berhasil.',
        ];
    }

    /**
     * Kirim ulang OTP (hanya bisa jika OTP aktif sudah expired).
     *
     * @param string $identifier
     * @param string $email
     * @param string $type
     * @return array
     */
    public function resend(string $identifier, string $email, string $type = 'email_verification'): array
    {
        return $this->generateAndSend($identifier, $email, $type);
    }

    /**
     * Generate kode OTP 6 digit yang unik (tidak ada di DB saat ini).
     */
    private function generateUniqueCode(): string
    {
        do {
            // random_int lebih aman dari rand() untuk keamanan kriptografis
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Pastikan kode tidak bentrok dengan OTP aktif lain
            $exists = OtpCode::where('code', $code)->active()->exists();
        } while ($exists);

        return $code;
    }
}