<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Peminjaman</title>

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
  <a href="Riwayat_Peminjaman" class="block px-5 py-3 bg-blue-700">Riwayat Peminjaman</a>
</div>

<!-- CONTENT -->
<div class="ml-56 p-6">

<!-- TOP -->
<div class="flex justify-between items-center mb-6">
  <h5 class="font-bold text-lg">Riwayat Peminjaman</h5>

  <a href="/Profil_Pengguna"
     class="font-semibold flex items-center gap-2 hover:text-blue-600">
     Nama Pengguna 👤
  </a>
</div>

<!-- HEADER -->
<div class="mb-6">
  <h2 class="text-3xl font-bold uppercase">🔎 RIWAYAT PEMINJAMAN</h2>
  <p class="text-gray-600 mt-2">Lihat Seluruh Aktivitas Peminjaman Anda</p>
</div>

<!-- INFO -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    3<br>Total Peminjaman
  </div>

  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    2<br>Sedang Dipinjam
  </div>

  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    0<br>Sudah Dikembalikan
  </div>

  <div class="bg-blue-300 text-white p-4 rounded-xl text-center">
    1<br>Terlambat
  </div>
</div>

<!-- SEARCH -->
<div class="bg-white rounded-xl shadow p-4 mb-6 flex gap-4">
  <input type="text"
         placeholder="Cari Judul, Pengarang, atau penerbit..."
         class="flex-1 border rounded px-4 py-2 text-sm">

  <select class="border rounded px-4 py-2 text-sm w-56">
    <option>Semua Kategori</option>
  </select>
</div>

<!-- TABLE -->
<div class="bg-white rounded-xl shadow">

  <!-- HEADER TABLE -->
  <div class="grid grid-cols-7 bg-gray-100 font-bold text-sm p-4 border-b">
    <div>Kode Transaksi</div>
    <div>Buku</div>
    <div>Tentang</div>
    <div>Tanggal Pinjam</div>
    <div>Tanggal Kembali</div>
    <div>Status</div>
    <div>Aksi</div>
  </div>

  <!-- ITEM 1 -->
  <div class="grid grid-cols-7 gap-4 p-4 border-b text-sm items-start">
    <div>
      <strong>BPKSJ124</strong><br>
      <small>23/03/2026 16:03</small>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-20 rounded shadow">
    </div>

    <div>
      <h6 class="font-semibold">Tentang Kamu</h6>
      <small>Tere Liye</small><br><br>

      <strong>BPKSJ124</strong><br>
      <small>23/Maret/2026</small><br>
      <small>29/Maret/2026</small><br>

      <span class="bg-green-400 text-white px-2 py-1 rounded text-xs">
        Sedang Dipinjam
      </span>
    </div>

    <div>23/Maret/2026</div>

    <div>
      30/Maret/2026<br>
      <span class="text-red-500">Telat 1 hari</span>
    </div>

    <div>
      <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">
        Terlambat
      </span>
    </div>

    <div>
      <button onclick="openModal()"
              class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
        👁
      </button>
    </div>
  </div>

  <!-- ITEM 2 -->
  <div class="grid grid-cols-7 gap-4 p-4 border-b text-sm items-start">
    <div>
      <strong>BPKSJ124</strong><br>
      <small>23/03/2026 16:03</small>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-20 rounded shadow">
    </div>

    <div>
      <h6 class="font-semibold">Tentang Kamu</h6>
      <small>Tere Liye</small>
    </div>

    <div>23/Maret/2026</div>

    <div>
      30/Maret/2026<br>
      <span class="text-green-500">1 hari lagi</span>
    </div>

    <div>
      <span class="bg-yellow-400 text-black px-2 py-1 rounded text-xs">
        Sedang Dipinjam
      </span>
    </div>

    <div>
      <button onclick="openModal()"
              class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
        👁
      </button>
    </div>
  </div>

  <!-- ITEM 3 -->
  <div class="grid grid-cols-7 gap-4 p-4 text-sm items-start">
    <div>
      <strong>BPKSJ124</strong><br>
      <small>23/03/2026 16:03</small>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-20 rounded shadow">
    </div>

    <div>
      <h6 class="font-semibold">Tentang Kamu</h6>
      <small>Tere Liye</small>
    </div>

    <div>23/Maret/2026</div>

    <div>30/Maret/2026</div>

    <div>
      <span class="bg-gray-400 text-white px-2 py-1 rounded text-xs">
        Pending
      </span>
    </div>

    <div>
      <button onclick="openModal()"
              class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
        👁
      </button>
    </div>
  </div>

</div>

<!-- FOOTER -->
<div class="text-center mt-6 text-gray-500">
  © <span id="year"></span> PERRRPUS | Politeknik Negeri Batam
</div>

</div>

<!-- MODAL POPUP -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-40 hidden justify-center items-center z-50">

  <div class="bg-white rounded-lg shadow-lg w-[850px] max-w-[95%] p-6 relative">

    <h2 class="text-lg font-semibold border-b pb-2 mb-4">Detail Riwayat</h2>

    <h3 class="font-bold text-lg mb-4">Detail Buku</h3>

    <div class="grid grid-cols-2 gap-6 text-sm">

      <!-- LEFT -->
      <div class="space-y-3">
        <div>
          <label class="block">Kode Buku</label>
          <input type="text" value="BPKSJ123" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Judul Buku</label>
          <input type="text" value="Tentang Kamu" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Penerbit</label>
          <input type="text" value="Republika Penerbit" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Pengarang</label>
          <input type="text" value="Tere Liye" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Kategori</label>
          <input type="text" value="Fiksi" class="w-full border rounded px-2 py-1">
        </div>

        <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
             class="w-28 rounded shadow mt-3">
      </div>

      <!-- RIGHT -->
      <div class="space-y-3">
        <div>
          <label class="block">Tahun Terbit</label>
          <input type="text" value="24/Oktober/2016" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Tanggal Peminjaman</label>
          <input type="text" value="23/Maret/2026" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Tanggal Kembali</label>
          <input type="text" value="30/Maret/2026" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Telat</label>
          <input type="text" value="2 Hari" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label class="block">Denda</label>
          <input type="text" value="4.000 RP" class="w-full border rounded px-2 py-1">
        </div>
      </div>

    </div>

    <div class="flex justify-end mt-6">
      <button onclick="closeModal()"
              class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded">
        Kembali
      </button>
    </div>

  </div>
</div>

<!-- SCRIPT -->
<script>
document.getElementById("year").textContent = new Date().getFullYear();

function openModal() {
    document.getElementById("detailModal").classList.remove("hidden");
    document.getElementById("detailModal").classList.add("flex");
}

function closeModal() {
    document.getElementById("detailModal").classList.remove("flex");
    document.getElementById("detailModal").classList.add("hidden");
}
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>