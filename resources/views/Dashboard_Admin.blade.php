<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>

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

  <a href="/dashboardadmin" class="block px-5 py-3 bg-blue-700">Beranda</a>
  <a href="/kelolabukuadmin" class="block px-5 py-3 hover:bg-blue-700">Kelola Buku</a>
  <a href="/kelolaanggotaadmin" class="block px-5 py-3 hover:bg-blue-700">Kelola Anggota</a>
  <a href="/kelolapeminjamanadmin" class="block px-5 py-3 hover:bg-blue-700">Kelola Peminjaman</a>
  <a href="/laporanadmin" class="block px-5 py-3 hover:bg-blue-700">Laporan</a>
</div>

<!-- CONTENT -->
<div class="ml-56 p-6">

<!-- TOP -->
<div class="flex justify-between items-center mb-6">
  <h5 class="font-bold text-lg">Beranda</h5>

  <a href="/Profil_Admin"
     class="font-semibold flex items-center gap-2 hover:text-blue-600">
     Nama Pengguna 👤
  </a>
</div>

<!-- WELCOME -->
<div class="bg-blue-300 text-white p-5 rounded-xl mb-6">
  <h6 class="mb-1 font-semibold text-xl">Selamat Datang, Admin</h6>
  <small>Senin, 29 Maret 2026</small>
</div>

<!-- INFO -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    <span class="text-3xl font-bold">30</span><br>Total Buku
  </div>

  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    <span class="text-3xl font-bold">2</span><br>Total Anggota
  </div>

  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    <span class="text-3xl font-bold">1</span><br>Buku Dipinjam
  </div>

  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    <span class="text-3xl font-bold">1</span><br>Transaksi Hari Ini
  </div>
</div>

<!-- GRAFIK -->
<div class="bg-white rounded-xl shadow mb-6 p-4">
  <canvas id="adminChart" height="100"></canvas>
</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow">

  <div class="p-4 font-bold border-b">
    Daftar Riwayat Peminjaman
  </div>

  <!-- HEADER -->
  <div class="grid grid-cols-6 bg-gray-100 font-bold text-sm p-4 border-b">
    <div>Anggota</div>
    <div>Buku</div>
    <div>Judul</div>
    <div>Tanggal Pinjam</div>
    <div>Tanggal Kembali</div>
    <div>Status</div>
  </div>

  <!-- ITEM 1 -->
  <div class="grid grid-cols-6 gap-4 p-4 border-b text-sm items-start">
    <div>
      👤<br>
      <strong>Luthfi Dwi Apriyadi</strong>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-16 rounded shadow">
    </div>

    <div>
      <strong>Tentang Kamu</strong><br>
      <small>Tere Liye</small>
    </div>

    <div>23/Maret/2026</div>

    <div>30/Maret/2026</div>

    <div>
      <span class="bg-green-400 text-white px-2 py-1 rounded text-xs">
        Dipinjam
      </span>
    </div>
  </div>

  <!-- ITEM 2 -->
  <div class="grid grid-cols-6 gap-4 p-4 text-sm items-start">
    <div>
      👤<br>
      <strong>Muhammad Zaky Sadewa</strong>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/81wgcld4wxL.jpg"
           class="w-16 rounded shadow">
    </div>

    <div>
      <strong>Atomic Habits</strong><br>
      <small>James Clear</small>
    </div>

    <div>21/Maret/2026</div>

    <div>26/Maret/2026</div>

    <div>
      <span class="bg-yellow-400 text-black px-2 py-1 rounded text-xs">
        Dikembalikan
      </span>
    </div>
  </div>

</div>

<!-- FOOTER -->
<div class="text-center mt-6 text-gray-500">
  © <span id="year"></span> Outrent. All Rights Reserved
</div>

</div>

<!-- SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.getElementById("year").textContent = new Date().getFullYear();

const ctx = document.getElementById('adminChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['25 Mar', '26 Mar', '27 Mar', '28 Mar', '29 Mar'],
        datasets: [{
            label: 'Peminjaman',
            data: [1, 1, 1, 1, 1],
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(147,197,253,0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>