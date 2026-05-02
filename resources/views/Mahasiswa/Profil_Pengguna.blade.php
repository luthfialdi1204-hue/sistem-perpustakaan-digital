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

<body class="bg-slate-100 text-slate-800">

<!-- SIDEBAR -->
<div class="fixed top-0 left-0 h-screen w-64 bg-slate-900 text-white shadow-xl">
  <div class="border-b border-slate-700 text-center p-6">
    <img src="{{ asset('images/poltek.png') }}" class="w-16 mx-auto">
    <p class="mt-3 text-sm text-slate-200">Perpustakaan Digital</p>
  </div>

  <div class="p-3 space-y-2">
    <a href="/Beranda_Mahasiswa" class="block rounded-lg px-4 py-3 text-slate-200 hover:bg-slate-800 transition">Beranda</a>
    <a href="/Katalog_Buku" class="block rounded-lg px-4 py-3 text-slate-200 hover:bg-slate-800 transition">Katalog Buku</a>
    <a href="/Riwayat_Peminjaman" class="block rounded-lg px-4 py-3 text-slate-200 hover:bg-slate-800 transition">Riwayat Peminjaman</a>
  </div>
</div>

<!-- CONTENT -->
<div class="ml-64 min-h-screen p-6 md:p-8">
  <!-- TOP -->
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold">Profil Pengguna</h1>
      <p class="text-sm text-slate-500">Kelola informasi akun Anda.</p>
    </div>

    <div class="relative">
      <button id="userMenuButton" type="button"
        class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 shadow-sm hover:shadow transition">
        <span class="font-medium text-gray-800">Nama Pengguna</span>
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-semibold">
          NP
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <div id="userDropdown"
        class="hidden absolute right-0 mt-2 w-44 rounded-xl border border-slate-200 bg-white py-2 shadow-lg z-50">
        <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 transition text-sm">Profil</a>
        <a href="/Landing_Page" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 transition text-sm text-red-500">Keluar</a>
      </div>
    </div>
  </div>

  <!-- MAIN -->
  <div class="flex justify-center">

    <div class="w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

      <!-- PROFILE HEADER -->
      <div class="mb-6 text-center">
        <div class="mx-auto mb-3 flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-700">NP</div>
        <h2 class="text-2xl font-bold">Nama Pengguna</h2>
      </div>

      <!-- INFORMASI -->
      <div class="mb-5">
        <h3 class="mb-4 text-lg font-semibold">Informasi Akun</h3>

        <div class="space-y-4">

          <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-base font-medium">
            Nama Pengguna : Luthfi Dwi Aprialdy
          </div>

          <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-base font-medium">
            Nim : 3312501077
          </div>

          <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-base font-medium">
            Kata Sandi : www
          </div>

          <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-base font-medium">
            Tipe Keanggotaan : Mahasiswa
          </div>

          <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-base font-medium">
            Email : luthfi@gmail.com
          </div>

        </div>
      </div>

      <!-- BUTTON -->
      <div class="flex justify-end mt-8">
        <a href="/Beranda_Mahasiswa"
           class="rounded-lg bg-slate-500 px-6 py-2 text-sm font-medium text-white hover:bg-slate-600 transition">
          Kembali
        </a>
      </div>

    </div>

  </div>

  <!-- FOOTER -->
  <div class="mt-6 text-center text-sm text-gray-500">
    © <span id="year"></span> PERRRPUS | Politeknik Negeri Batam
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

<script>
const userMenuButton = document.getElementById('userMenuButton');
const userDropdown = document.getElementById('userDropdown');
document.getElementById("year").textContent = new Date().getFullYear();

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