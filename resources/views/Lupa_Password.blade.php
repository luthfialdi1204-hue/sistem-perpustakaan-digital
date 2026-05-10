<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
  <div class="w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-6">
    <h1 class="text-xl font-bold text-slate-800">Lupa Password</h1>
    <p class="mt-1 text-sm text-slate-500">Langkah 1 dari 3: masukkan email untuk menerima OTP.</p>

    @if (session('success'))
      <div class="mt-4 rounded-lg bg-emerald-100 text-emerald-700 px-3 py-2 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.otp.send') }}" class="mt-5 rounded-xl border border-slate-200 p-4">
      @csrf
      <label class="text-sm font-semibold text-slate-700">Email akun</label>
      <input type="email" name="email" value="{{ old('email') }}"
        class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
        placeholder="Masukkan email akun" required>
      @error('email')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
      <button class="mt-3 rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Kirim OTP</button>
    </form>

    <a href="{{ route('login.form') }}" class="mt-5 inline-block text-sm text-blue-600 hover:underline">Kembali ke login</a>
  </div>
</body>
</html>
