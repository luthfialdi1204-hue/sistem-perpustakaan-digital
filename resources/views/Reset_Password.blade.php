<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
  <div class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-6">
    <h1 class="text-xl font-bold text-slate-800">Reset Password</h1>
    <p class="mt-1 text-sm text-slate-500">Langkah 3 dari 3 (tampilan): buat password baru untuk <b>{{ $email }}</b>.</p>

    @if (session('success'))
      <div class="mt-4 rounded-lg bg-emerald-100 text-emerald-700 px-3 py-2 text-sm">{{ session('success') }}</div>
    @endif

    <div class="mt-5 rounded-xl border border-slate-200 p-4">
      <label class="text-sm font-semibold text-slate-700">Password baru</label>
      <input type="password" name="password"
        class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
        placeholder="Password baru" required>
      <input type="password" name="password_confirmation"
        class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
        placeholder="Konfirmasi password baru" required>
      <button type="button" id="btnResetPreview" class="mt-3 rounded-lg bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700">Simpan Password Baru</button>
    </div>

    <a href="{{ route('password.forgot.otp') }}" class="mt-5 inline-block text-sm text-blue-600 hover:underline">Kembali ke verifikasi OTP</a>
  </div>
  <script>
  document.getElementById('btnResetPreview').addEventListener('click', function () {
    alert('Tampilan berhasil: password baru tersimpan (simulasi).');
    window.location.href = "{{ route('login.form') }}";
  });
  </script>
</body>
</html>
