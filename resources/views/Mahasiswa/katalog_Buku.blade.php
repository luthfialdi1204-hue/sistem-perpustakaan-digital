@extends('layouts.mahasiswa')

@section('title', 'Katalog Buku')
@section('active_page', 'katalog')
@section('page_title', 'Katalog Buku')
@section('page_subtitle', 'Temukan dan pinjam buku sesuai kebutuhan Anda.')

@section('content')

<!-- SEARCH -->
<div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
  <h2 class="font-semibold text-lg">Filter Buku</h2>
  <p class="text-sm text-slate-500">Cari berdasarkan judul, pengarang, atau kategori.</p>

  <div class="mt-4 grid gap-3 md:grid-cols-2">
    <input type="text" id="searchInput"
      class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none"
      placeholder="Cari Judul, Pengarang, atau penerbit...">

    <select id="categoryFilter" class="w-full rounded-lg border border-slate-300 px-4 py-2 focus:border-blue-500 focus:outline-none">
      <option value="all">Semua Kategori</option>
      <option value="Pendidikan">Pendidikan</option>
      <option value="Fiksi">Fiksi</option>
    </select>
  </div>
</div>

<!-- GRID -->
<div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4" id="book-container"></div>

<!-- MODAL DETAIL (VERSI RAPI & LENGKAP) -->
<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
  <div class="relative w-full max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">

    <!-- CLOSE -->
    <button onclick="closeModal()" class="absolute right-4 top-3 text-2xl text-gray-500 hover:text-black">✖</button>

    <h5 class="mb-4 text-xl font-bold">Detail Buku</h5>

    <div class="grid gap-6 md:grid-cols-2">

      <!-- KIRI (GAMBAR) -->
      <div>
        <img id="modalImg" class="mb-2 w-full rounded-lg shadow">
        <p class="text-sm text-gray-600">⭐ 4.8 (1.2k review)</p>
      </div>

      <!-- KANAN (DETAIL) -->
      <div class="space-y-1 text-sm">

        <h5 id="modalTitle" class="mb-2 text-lg font-bold"></h5>

        <p><b>Kode Buku:</b> BP001</p>
        <p><b>Pengarang:</b> <span id="modalAuthor"></span></p>
        <p><b>Penerbit:</b> Avery Publishing</p>
        <p><b>Tahun Terbit:</b> 16 Oktober 2018</p>

        <p><b>Kategori:</b> 
          <span id="modalCategory" class="rounded bg-blue-500 px-2 py-1 text-xs text-white"></span>
        </p>

        <p><b>Ketersediaan:</b> 
          <span id="modalStock" class="rounded bg-green-500 px-2 py-1 text-xs text-white"></span>
        </p>

        <p><b>Lokasi Rak:</b> A5-01</p>
        <p><b>ISBN:</b> 978-602-06-3317-6</p>

        <!-- DESKRIPSI -->
        <div class="mt-3">
          <p class="font-semibold">Deskripsi:</p>
          <p class="text-xs leading-relaxed text-gray-600">
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolore nihil, repudiandae accusantium omnis consequuntur praesentium corporis est maxime autem vitae quis et repellendus harum eligendi architecto sint ipsum cumque distinctio.
          </p>
        </div>

      </div>
    </div>

    <!-- BUTTON -->
    <div class="mt-5 text-right">
      <button onclick="closeModal()" class="rounded-lg bg-slate-500 px-4 py-2 text-sm text-white hover:bg-slate-600">
        Kembali
      </button>
    </div>

  </div>
</div> 

<!-- MODAL PINJAM -->
<div id="pinjamModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
  <div class="relative w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">

    <button onclick="closePinjamModal()" class="absolute right-3 top-2 text-xl">✖</button>

    <h5 class="mb-4 text-lg font-bold">Pinjam Buku</h5>

    <div class="grid gap-4 md:grid-cols-2">

      <div>
        <img id="pinjamImg" class="mb-2 w-full rounded-lg">
        <p class="text-sm">⭐ 4.8 (1.2k review)</p>
      </div>

      <div class="space-y-2 text-sm">
        <p><b>Kode Buku:</b> BP001</p>
        <p><b>Judul:</b> <span id="pinjamTitle"></span></p>
        <p><b>Pengarang:</b> <span id="pinjamAuthor"></span></p>

        <div>
          <label>Tanggal Pinjam</label>
          <input type="date" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label>Tanggal Kembali</label>
          <input type="date" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label>Denda</label>
          <input type="text" value="Rp2.000 / hari" disabled class="w-full rounded-lg border border-slate-300 bg-gray-100 px-2 py-1">
        </div>
      </div>

    </div>

    <div class="mt-4 flex justify-end gap-2">
      <button onclick="closePinjamModal()" class="rounded-lg bg-slate-500 px-4 py-2 text-sm text-white hover:bg-slate-600">
        Kembali
      </button>

      <button onclick="alert('Buku berhasil dipinjam!')" class="rounded-lg bg-emerald-500 px-4 py-2 text-sm text-white hover:bg-emerald-600">
        Pinjam
      </button>
    </div>

  </div>
</div>
@endsection

@push('scripts')
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
      <div class="rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
        <img src="${book.img}" class="h-40 w-full rounded-lg object-cover">
        
        <h6 class="mt-2 font-semibold text-slate-800">${book.title}</h6>
        <small class="text-gray-500">${book.author}</small><br>
        <small class="text-gray-500">${book.category}</small><br>
        <small class="text-gray-500">Tersedia: ${book.stock}</small>

        <div class="mt-2 space-y-1">
          <button onclick="openModal('${book.title}','${book.author}','${book.category}','${book.stock}','${book.img}')"
            class="w-full rounded-lg bg-blue-600 py-2 text-sm text-white hover:bg-blue-700 transition">
            Lihat Detail
          </button>

          ${
            book.status === "available"
            ? `<button onclick="openPinjamModal('${book.title}','${book.author}','${book.img}')"
                class="w-full rounded-lg bg-emerald-500 py-2 text-sm text-white hover:bg-emerald-600 transition">
                Pinjam Buku
              </button>`
            : `<button class="w-full rounded-lg bg-amber-300 py-2 text-sm text-amber-900">
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
@endpush