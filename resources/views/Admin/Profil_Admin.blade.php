<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil Pengguna</title>

<!-- Tailwind + Flowbite -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />

</head>

<body class="bg-gray-100">

<!-- SIDEBAR -->
<div class="fixed top-0 left-0 h-screen w-56 bg-blue-900 text-white">
  <div class="text-center p-5">
    <img src="{{ asset('images/logo Polibatam.png') }}" class="w-16 mx-auto"><br>
    <small>Perpustakaan Digital</small>
  </div>

  <a href="/Beranda_Mahasiswa" class="block px-5 py-3 hover:bg-blue-700">Beranda</a>
  <a href="/" class="block px-5 py-3 hover:bg-blue-700">Kelola Buku</a>
  <a href="/" class="block px-5 py-3 hover:bg-blue-700">Kelola Anggota</a>
  <a href="/" class="block px-5 py-3 hover:bg-blue-700">Kelola Peminjaman</a>
  <a href="/" class="block px-5 py-3 hover:bg-blue-700">Laporan</a>
</div>

<!-- CONTENT -->
<div class="ml-56 min-h-screen">

  <!-- TOPBAR -->
    <div class="flex justify-end items-center bg-white shadow px-8 py-5 relative">
        <div class="relative">
            <button id="userMenuButton"
                class="text-sm font-semibold flex items-center gap-2 hover:text-blue-600">
                Nama Pengguna
                <span class="text-3xl">👤</span>
                ▼
            </button>

            <!--DROPDOWN LOGOUT-->
            <div id="userDropdown"
                class="hidden absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-lg border z-50">

                <a href="/Landing_Page"
                    class="block px-4 py-3 hover:bg-gray-100 text-black font-medium">
                    🚪 Keluar
                </a>  
            </div>          
        </div>
    </div>

  <!-- MAIN -->
  <div class="p-8 flex justify-center items-center">

    <div class="bg-gray-300 rounded-[25px] shadow-lg border border-gray-500 w-full max-w-3xl p-8">

      <!-- PROFILE HEADER -->
      <div class="text-center mb-6">
        <div class="text-6xl mb-3">👤</div>
        <h1 class="text-3xl font-bold">Nama Pengguna</h1>
      </div>

      <!-- INFORMASI -->
      <div class="mb-5">
        <h2 class="text-2xl mb-4">Informasi Akun</h2>

        <div class="space-y-4">

          <div class="bg-white rounded-2xl shadow px-5 py-3 text-xl font-semibold">
            Nama Pengguna : Luthfi Dwi Aprialdy
          </div>

          <div class="bg-white rounded-2xl shadow px-5 py-3 text-xl font-semibold">
            Kata Sandi : www
          </div>

          <div class="bg-white rounded-2xl shadow px-5 py-3 text-xl font-semibold">
            Tipe Keanggotaan : Mahasiswa
          </div>

          <div class="bg-white rounded-2xl shadow px-5 py-3 text-xl font-semibold">
            Email : luthfi@gmail.com
          </div>

        </div>
      </div>

      <!-- BUTTON -->
      <div class="flex justify-end mt-8">
        <a href="/Dashboard_Admin"
           class="bg-gray-400 hover:bg-gray-500 text-black font-bold text-lg px-8 py-2 rounded-full shadow border border-gray-700">
          Kembali
        </a>
      </div>

    </div>

  </div>

</div>

<!--SCRIPT-->
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script>
const userMenuButton = document.getElementById('userMenuButton');
const userDropdown = document.getElementById('userDropdown');

userMenuButton.addEventListener('click', () => {
    userDropdown.classList.toggle('hidden');
});

window.addEventListener('click', function(e) {
    if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
        userDropdown.classList.add('hidden');
    }
});
</script>

</body>
</html>