<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi OTP</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
  <div class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-6">
    <h1 class="text-xl font-bold text-slate-800">Verifikasi OTP</h1>
    <p class="mt-1 text-sm text-slate-500">Langkah 2 dari 3 (tampilan): masukkan kode OTP yang dikirim ke email akun <b>{{ $email }}</b>.</p>

    @if (session('success'))
      <div class="mt-4 rounded-lg bg-emerald-100 text-emerald-700 px-3 py-2 text-sm">{{ session('success') }}</div>
    @endif

    <div class="mt-5 rounded-xl border border-slate-200 p-4">
      <p class="text-xs text-slate-500">Akun terdeteksi: {{ $label ?? 'ID' }} {{ $identifier ?? '-' }}</p>
      <label class="text-sm font-semibold text-slate-700">Kode OTP</label>
      <input type="text" name="otp" value="{{ old('otp') }}"
        class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
        placeholder="Masukkan 6 digit OTP" required>
      <button type="button" id="btnOtpPreview" class="mt-3 rounded-lg bg-violet-600 px-4 py-2 text-sm text-white hover:bg-violet-700">Verifikasi OTP</button>
    </div>

    <a href="{{ route('login.form') }}" class="mt-5 inline-block text-sm text-blue-600 hover:underline">Kembali ke login</a>
  </div>
  <script>
  document.getElementById('btnOtpPreview').addEventListener('click', function () {
    window.location.href = "{{ route('password.forgot.reset') }}?email={{ urlencode($email ?? 'email_akun@kampus.ac.id') }}";
  });
  </script>
</body>
</html>
