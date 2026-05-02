<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>

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

  <a href="Beranda_Mahasiswa" class="block px-5 py-3 hover:bg-blue-700">Beranda</a>
  <a href="Katalog_Buku" class="block px-5 py-3 hover:bg-blue-700">Katalog Buku</a>
  <a href="Riwayat_Peminjaman" class="block px-5 py-3 hover:bg-blue-700">Riwayat Peminjaman</a>
</div>

<!-- CONTENT -->
<div class="ml-56 p-6">

<!-- TOP -->
<div class="flex justify-between items-center mb-6">
  <h5 class="font-bold text-lg">Beranda</h5>
  <a href="/Profil_Pengguna"
      class="font-semibold flex items-center gap-2 hover:text-blue-600">
      Nama Pengguna 👤
  </a>
</div>

<!-- WELCOME -->
<div class="bg-blue-300 text-white p-5 rounded-xl mb-6">
  <h6 class="mb-1 font-semibold">Selamat Datang, Luthfi Dwi Apriyadi!!</h6>
  <small>Senin, 29 Maret 2026</small>
</div>

<!-- INFO -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">1<br>Total Peminjaman</div>
  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">1<br>Sedang Dipinjam</div>
  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">1<br>Sudah Dikembalikan</div>
  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">1<br>Terlambat</div>
</div>

<!-- DIPINJAM -->
<div class="bg-white rounded-xl shadow mb-6">
  <div class="p-4 font-bold border-b">Buku Yang Sedang Dipinjam</div>
  <div class="p-4 flex items-center">
    <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="w-24">
    
    <div class="ml-4">
      <h6 class="font-semibold">Tentang Kamu</h6>
      <small>Tere Liye</small><br>
      <small>Pinjam: 23 Maret 2026</small><br>
      <small>Kembali: 30 Maret 2026</small><br>

      <span class="bg-yellow-400 text-black px-2 py-1 rounded text-xs">
        Sedang Dipinjam
      </span>
    </div>
  </div>
</div>

<!-- REKOMENDASI -->
<div class="bg-white rounded-xl shadow">
  <div class="p-4 font-bold border-b">Rekomendasi Buku Untuk Anda</div>

  <div class="p-4 grid grid-cols-2 md:grid-cols-6 gap-3" id="rekomendasi-container"></div>
</div>

<!-- FOOTER -->
<div class="text-center mt-6 text-gray-500">
  © <span id="year"></span> PERRRPUS | Politeknik Negeri Batam
</div>

</div>

<!-- SCRIPT -->
<script>
document.getElementById("year").textContent = new Date().getFullYear();

const books = [
  {title:"Atomic Habits", img:"https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg", available:true},
  {title:"Rich Dad Poor Dad", img:"https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg", available:true},
  {title:"Filosofi Teras", img:"https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg", available:false},
  {title:"Psikologi Uang", img:"https://m.media-amazon.com/images/I/71g2ednj0JL.jpg", available:true},
  {title:"Laut Bercerita", img:"https://m.media-amazon.com/images/I/81af+MCATTL.jpg", available:true},
  {title:"Bumi", img:"https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg", available:false}
];

// shuffle
function shuffle(array){
  return array.sort(() => 0.5 - Math.random());
}

// prioritas tersedia
let rekomendasi = [
  ...books.filter(b => b.available),
  ...books.filter(b => !b.available)
];

rekomendasi = shuffle(rekomendasi).slice(0,6);

const container = document.getElementById("rekomendasi-container");

rekomendasi.forEach(book => {
  container.innerHTML += `
    <div class="bg-white rounded-lg shadow p-2 text-center hover:scale-105 transition">
      <img src="${book.img}" class="w-full h-28 object-cover rounded">
      <small class="block mt-1">${book.title}</small>
      <button class="mt-2 w-full bg-blue-600 text-white text-xs py-1 rounded">
        Detail
      </button>
    </div>
  `;
});
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>