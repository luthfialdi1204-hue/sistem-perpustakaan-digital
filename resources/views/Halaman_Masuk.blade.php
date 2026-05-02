<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Masuk</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background: url('{{ asset("images/background.jpg") }}') no-repeat center center/cover;
}

/* overlay */
.overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(30,58,138,0.8), rgba(147,51,234,0.6));
}
</style>
</head>

<body class="relative min-h-screen flex items-center justify-center">

<div class="overlay"></div>

<!-- CARD -->
<div class="relative z-10 backdrop-blur-lg bg-white/20 border border-white/30 rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center">

  <!-- LOGO -->
  <img src="{{ asset('images/poltek.png') }}" class="w-20 mx-auto mb-3 drop-shadow-lg">

  <h5 class="font-bold text-lg text-white">Perpustakaan Digital</h5>
  <p class="mb-6 text-sm text-white/80">Silahkan Masuk Untuk Melanjutkan</p>

    <!-- INPUT NIM -->
    <div class="mb-4">
      <input 
        type="text"
        name="nim"
        required
        class="w-full px-4 py-2 rounded-full bg-white/80 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition"
        placeholder="Masukkan NIM">
    </div>

    <!-- PASSWORD -->
    <div class="mb-5 relative">
      <input 
        type="password" 
        id="password"
        name="password"
        required
        class="w-full px-4 py-2 rounded-full bg-white/80 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none pr-10 transition"
        placeholder="Kata Sandi">

      <i id="togglePassword"
         class="bi bi-eye-slash absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-600 hover:text-blue-600 transition">
      </i>
    </div>

    <!-- BUTTON -->
    <div class="flex justify-between items-center">

      <!-- KEMBALI -->
      <a href="{{ url('/Landing_Page') }}" 
         class="bg-white/70 px-4 py-1 rounded-full text-sm hover:bg-white transition shadow inline-block">
        Kembali
      </a>

      <!-- SUBMIT -->
      <a href="Beranda_Mahasiswa">
      <button type="submit"
        class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-1 rounded-full text-sm hover:scale-105 transition shadow-lg">
        Masuk
      </button>
      </a>
    </div>
</div>

<script>
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');

togglePassword.addEventListener('click', function () {
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);

  this.classList.toggle('bi-eye');
  this.classList.toggle('bi-eye-slash');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>