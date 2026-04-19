<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Katalog Buku</title>

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
  <a href="Katalog_Buku" class="block px-5 py-3 bg-blue-700">Katalog Buku</a>
  <a href="Riwayat_Peminjaman" class="block px-5 py-3 hover:bg-blue-700">Riwayat Peminjaman</a>
</div>

<!-- CONTENT -->
<div class="ml-56 p-6">

<!-- HEADER -->
<div class="flex justify-between items-center mb-4">
  <h5 class="font-bold text-lg">Katalog Buku</h5>
  <div>Nama Pengguna 👤</div>
</div>

<!-- SEARCH -->
<div class="bg-white p-4 rounded-xl shadow mb-6">
  <h6 class="font-bold">📚 Katalog Buku</h6>
  <p class="text-sm text-gray-500">Temukan dan pinjam buku yang anda inginkan</p>

  <div class="grid md:grid-cols-2 gap-3 mt-3">
    <input type="text" id="searchInput"
      class="w-full px-4 py-2 border rounded-lg"
      placeholder="Cari Judul, Pengarang, atau penerbit...">

    <select id="categoryFilter" class="w-full px-4 py-2 border rounded-lg">
      <option value="all">Semua Kategori</option>
      <option value="Pendidikan">Pendidikan</option>
      <option value="Fiksi">Fiksi</option>
    </select>
  </div>
</div>

<!-- GRID -->
<div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="book-container"></div>

<!-- FOOTER -->
<div class="text-center mt-6 text-gray-500">
  Copyright © 2025 Outrent. All Rights Reserved
</div>

</div>

<!-- MODAL DETAIL (VERSI RAPI & LENGKAP) -->
<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 relative">

    <!-- CLOSE -->
    <button onclick="closeModal()" class="absolute top-3 right-4 text-2xl text-gray-500 hover:text-black">✖</button>

    <h5 class="text-xl font-bold mb-4">Detail Buku</h5>

    <div class="grid md:grid-cols-2 gap-6">

      <!-- KIRI (GAMBAR) -->
      <div>
        <img id="modalImg" class="w-full rounded shadow mb-2">
        <p class="text-sm text-gray-600">⭐ 4.8 (1.2k review)</p>
      </div>

      <!-- KANAN (DETAIL) -->
      <div class="text-sm space-y-1">

        <h5 id="modalTitle" class="text-lg font-bold mb-2"></h5>

        <p><b>Kode Buku:</b> BP001</p>
        <p><b>Pengarang:</b> <span id="modalAuthor"></span></p>
        <p><b>Penerbit:</b> Avery Publishing</p>
        <p><b>Tahun Terbit:</b> 16 Oktober 2018</p>

        <p><b>Kategori:</b> 
          <span id="modalCategory" class="bg-blue-500 text-white px-2 py-1 rounded text-xs"></span>
        </p>

        <p><b>Ketersediaan:</b> 
          <span id="modalStock" class="bg-green-500 text-white px-2 py-1 rounded text-xs"></span>
        </p>

        <p><b>Lokasi Rak:</b> A5-01</p>
        <p><b>ISBN:</b> 978-602-06-3317-6</p>

        <!-- DESKRIPSI -->
        <div class="mt-3">
          <p class="font-semibold">Deskripsi:</p>
          <p class="text-gray-600 text-xs leading-relaxed">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolore nihil, repudiandae accusantium omnis consequuntur praesentium corporis est maxime autem vitae quis et repellendus harum eligendi architecto sint ipsum cumque distinctio.
          </p>
        </div>

      </div>
    </div>

    <!-- BUTTON -->
    <div class="text-right mt-5">
      <button onclick="closeModal()" class="bg-gray-400 px-4 py-1 rounded text-white">
        Kembali
      </button>
    </div>

  </div>
</div> 

<!-- MODAL PINJAM -->
<div id="pinjamModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-5 relative">

    <button onclick="closePinjamModal()" class="absolute top-2 right-3 text-xl">✖</button>

    <h5 class="text-lg font-bold mb-4">Pinjam Buku</h5>

    <div class="grid md:grid-cols-2 gap-4">

      <div>
        <img id="pinjamImg" class="w-full rounded mb-2">
        <p class="text-sm">⭐ 4.8 (1.2k review)</p>
      </div>

      <div class="space-y-2 text-sm">
        <p><b>Kode Buku:</b> BP001</p>
        <p><b>Judul:</b> <span id="pinjamTitle"></span></p>
        <p><b>Pengarang:</b> <span id="pinjamAuthor"></span></p>

        <div>
          <label>Tanggal Pinjam</label>
          <input type="date" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label>Tanggal Kembali</label>
          <input type="date" class="w-full border rounded px-2 py-1">
        </div>

        <div>
          <label>Denda</label>
          <input type="text" value="Rp2.000 / hari" disabled class="w-full border rounded px-2 py-1 bg-gray-100">
        </div>
      </div>

    </div>

    <div class="flex justify-end gap-2 mt-4">
      <button onclick="closePinjamModal()" class="bg-gray-400 px-4 py-1 rounded text-white">
        Kembali
      </button>

      <button onclick="alert('Buku berhasil dipinjam!')" class="bg-green-500 px-4 py-1 rounded text-white">
        Pinjam
      </button>
    </div>

  </div>
</div>

<!-- SCRIPT -->
<script>
const books = [
  {title:"Atomic Habits", author:"James Clear", category:"Pendidikan", stock:30, img:"https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg", status:"available"},
  {title:"Tentang Kamu", author:"Tere Liye", category:"Fiksi", stock:30, img:"https://m.media-amazon.com/images/I/81af+MCATTL.jpg", status:"borrowed"},
  {title:"Rich Dad Poor Dad", author:"Robert Kiyosaki", category:"Pendidikan", stock:25, img:"https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg", status:"available"},
  {title:"Filosofi Teras", author:"Henry Manampiring", category:"Pendidikan", stock:10, img:"https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg", status:"available"},
  {title:"Laut Bercerita", author:"Leila S Chudori", category:"Fiksi", stock:15, img:"https://m.media-amazon.com/images/I/81af+MCATTL.jpg", status:"available"},
  {title:"Bumi", author:"Tere Liye", category:"Fiksi", stock:12, img:"https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg", status:"available"}
];

// render
function renderBooks(data){
  const container = document.getElementById("book-container");
  container.innerHTML = "";

  data.slice(0,6).forEach(book => {
    container.innerHTML += `
      <div class="bg-white rounded-xl shadow p-3 text-center hover:scale-105 transition">
        <img src="${book.img}" class="w-full h-40 object-cover rounded">
        
        <h6 class="mt-2 font-semibold">${book.title}</h6>
        <small class="text-gray-500">${book.author}</small><br>
        <small class="text-gray-500">${book.category}</small><br>
        <small class="text-gray-500">Tersedia: ${book.stock}</small>

        <div class="mt-2 space-y-1">
          <button onclick="openModal('${book.title}','${book.author}','${book.category}','${book.stock}','${book.img}')"
            class="w-full bg-blue-600 text-white text-sm py-1 rounded">
            Lihat Detail
          </button>

          ${
            book.status === "available"
            ? `<button onclick="openPinjamModal('${book.title}','${book.author}','${book.img}')"
                class="w-full bg-green-500 text-white text-sm py-1 rounded">
                Pinjam Buku
              </button>`
            : `<button class="w-full bg-yellow-400 text-black text-sm py-1 rounded">
                Sedang Dipinjam
              </button>`
          }
        </div>
      </div>
    `;
  });
}

// modal detail
function openModal(title, author, category, stock, img){
  document.getElementById("detailModal").classList.remove("hidden");
  document.body.classList.add("no-scroll"); // 🔥 tambah ini

  modalTitle.innerText = title;
  modalAuthor.innerText = author;
  modalCategory.innerText = category;
  modalStock.innerText = stock + " tersedia";
  modalImg.src = img;
}

function closeModal(){
  document.getElementById("detailModal").classList.add("hidden");
  document.body.classList.remove("no-scroll"); // 🔥 tambah ini
}

// modal pinjam
function openPinjamModal(title, author, img){
  document.getElementById("pinjamModal").classList.remove("hidden");
  pinjamTitle.innerText = title;
  pinjamAuthor.innerText = author;
  pinjamImg.src = img;
}

function closePinjamModal(){
  document.getElementById("pinjamModal").classList.add("hidden");
}

// filter
function filterBooks(){
  const keyword = searchInput.value.toLowerCase();
  const category = categoryFilter.value;

  const filtered = books.filter(book => {
    return (
      (book.title.toLowerCase().includes(keyword) ||
       book.author.toLowerCase().includes(keyword) ||
       book.category.toLowerCase().includes(keyword)) &&
      (category === "all" || book.category === category)
    );
  });

  renderBooks(filtered);
}

searchInput.addEventListener("keyup", filterBooks);
categoryFilter.addEventListener("change", filterBooks);

renderBooks(books);
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>