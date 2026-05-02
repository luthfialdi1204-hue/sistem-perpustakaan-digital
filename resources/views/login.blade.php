<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Masuk</title>

<!-- Tailwind + Flowbite -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background: url('{{ asset("images/bg GU.jpg") }}') no-repeat center center/cover;
}

/* overlay ungu */
.overlay {
  position: absolute;
  inset: 0;
  background: rgba(128, 0, 255, 0.4);
}
</style>
</head>

<body class="relative min-h-screen flex items-center justify-center">

<!-- OVERLAY -->
<div class="overlay"></div>

<!-- CARD LOGIN -->
<div class="relative z-10 bg-gray-200 rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center">

  <!-- LOGO -->
  <img src="{{ asset('images/Logo polibatam.png') }}" class="w-20 mx-auto mb-2">

  <h5 class="font-bold text-lg">Perpustakaan Digital</h5>
  <p class="mb-4 text-sm">Silahkan Masuk Untuk Melanjutkan</p>

  <!-- FORM -->
  <form>

    <!-- NIM -->
    <div class="mb-3">
      <input type="text"
        class="w-full px-4 py-2 rounded-full shadow focus:outline-none"
        placeholder="Masukkan NIM">
    </div>

    <!-- PASSWORD -->
    <div class="mb-4 relative">
      <input type="password" id="password"
        class="w-full px-4 py-2 rounded-full shadow pr-10 focus:outline-none"
        placeholder="Kata Sandi">

      <i id="togglePassword"
         class="bi bi-eye-slash absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-600">
      </i>
    </div>

    <!-- BUTTON -->
    <div class="flex justify-between">

      <!-- Kembali -->
      <button type="button"
        onclick="window.location.href='{{ url('/Landing_Page')}}'"
        class="bg-gray-300 px-4 py-1 rounded-full text-sm hover:bg-gray-400">
        Kembali
      </button>

      <!-- Masuk -->
      <button type="button"
        onclick="window.location.href='{{ url('/Beranda_Mahasiswa')}}'"
        class="bg-blue-600 text-white px-4 py-1 rounded-full text-sm hover:bg-blue-700">
        Masuk
      </button>

    </div>

  </form>
</div>

<!-- SCRIPT -->
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