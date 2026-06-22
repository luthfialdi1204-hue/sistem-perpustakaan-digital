<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f0f4f8; }
        .wrapper { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #3b5bdb 0%, #7048e8 100%); padding: 36px 40px; text-align: center; }
        .header img { width: 64px; height: 64px; border-radius: 14px; margin-bottom: 12px; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; }
        .header p { color: rgba(255,255,255,0.8); font-size: 13px; margin-top: 4px; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #333; margin-bottom: 16px; }
        .desc { font-size: 14px; color: #666; line-height: 1.6; margin-bottom: 28px; }
        .otp-box { background: #f5f3ff; border: 2px dashed #7048e8; border-radius: 12px; text-align: center; padding: 24px 16px; margin-bottom: 28px; }
        .otp-label { font-size: 12px; color: #7048e8; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 12px; }
        .otp-code { font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #3b5bdb; font-family: 'Courier New', monospace; }
        .expiry { display: flex; align-items: center; justify-content: center; gap: 8px; background: #fff3cd; border-radius: 8px; padding: 12px 16px; margin-bottom: 24px; }
        .expiry-icon { font-size: 18px; }
        .expiry-text { font-size: 13px; color: #856404; font-weight: 500; }
        .warning { font-size: 13px; color: #999; line-height: 1.6; border-top: 1px solid #f0f0f0; padding-top: 20px; }
        .footer { background: #f8f9fa; padding: 20px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #aaa; }
        .footer strong { color: #3b5bdb; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>📚 Perpustakaan Digital</h1>
            <p>Politeknik Negeri Batam</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Halo! 👋</p>
            <p class="desc">
                Kami menerima permintaan verifikasi akun Anda di <strong>Perpustakaan Digital Polibatam</strong>.
                Gunakan kode OTP berikut untuk melanjutkan proses verifikasi:
            </p>

            <!-- Kode OTP -->
            <div class="otp-box">
                <div class="otp-label">Kode OTP Anda</div>
                <div class="otp-code">{{ $code }}</div>
            </div>

            <!-- Waktu expired -->
            <div class="expiry">
                <span class="expiry-icon">⏳</span>
                <span class="expiry-text">
                    Kode ini berlaku selama <strong>{{ $expiryMinutes }} menit</strong>
                    dan akan kedaluwarsa setelah itu.
                </span>
            </div>

            <!-- Peringatan -->
            <p class="warning">
                ⚠️ <strong>Jangan bagikan kode ini</strong> kepada siapapun, termasuk pihak Perpustakaan.
                Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem<br>
            <strong>Perpustakaan Digital Polibatam</strong> — Jangan balas email ini.</p>
        </div>
    </div>
</body>
</html>