@extends('layouts.admin')

@section('title', 'Kelola Buku Admin')
@section('active_page', 'kelola-buku')
@section('page_title', 'Kelola Buku')

@section('content')
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Total Buku</p>
    <p class="mt-2 text-2xl font-bold text-slate-800">30</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Total Anggota</p>
    <p class="mt-2 text-2xl font-bold text-blue-700">2</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Buku Dipinjam</p>
    <p class="mt-2 text-2xl font-bold text-emerald-600">1</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Transaksi Hari Ini</p>
    <p class="mt-2 text-2xl font-bold text-rose-600">1</p>
  </div>
</div>

<div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
  <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
    <input id="searchInput" type="text" placeholder="Cari Judul, Pengarang, atau penerbit..."
      class="md:col-span-7 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
    <select id="categoryFilter"
      class="md:col-span-3 w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
      <option value="all">Semua Kategori</option>
      <option value="Pendidikan">Pendidikan</option>
      <option value="Fiksi">Fiksi</option>
      <option value="Bisnis">Bisnis</option>
    </select>
    <button onclick="openTambahModal()"
      class="md:col-span-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
      Tambah Buku
    </button>
  </div>
</div>

<div id="bookContainer" class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4"></div>

<div id="paginationContainer" class="mt-6 flex justify-center"></div>

<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
  <div class="w-full max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
      <h3 class="text-lg font-semibold">Detail Buku</h3>
      <button onclick="closeDetailModal()" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
      <div>
        <img id="detailImg" src="" alt="Cover Buku" class="mx-auto w-40 rounded-lg border border-slate-200 shadow-sm">
      </div>

      <div class="md:col-span-2 space-y-2 text-sm">
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Judul Buku</p>
          <p class="col-span-2 font-medium" id="detailTitle">-</p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Kode Buku</p>
          <p class="col-span-2 font-medium" id="detailCode">-</p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Pengarang</p>
          <p class="col-span-2 font-medium" id="detailAuthor">-</p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Penerbit</p>
          <p class="col-span-2 font-medium" id="detailPublisher">-</p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Tahun Terbit</p>
          <p class="col-span-2 font-medium" id="detailYear">-</p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Kategori</p>
          <p class="col-span-2"><span id="detailCategory" class="rounded bg-blue-100 px-2 py-1 text-xs text-blue-700"></span></p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Stok</p>
          <p class="col-span-2"><span id="detailStock" class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700"></span></p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Lokasi Rak</p>
          <p class="col-span-2 font-medium" id="detailRack">-</p>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">ISBN</p>
          <p class="col-span-2 font-medium" id="detailIsbn">-</p>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
      <button onclick="closeDetailModal()" class="rounded-lg bg-slate-500 px-4 py-2 text-sm text-white hover:bg-slate-600">Kembali</button>
      <button onclick="openEditModal()" class="rounded-lg bg-emerald-500 px-4 py-2 text-sm text-white hover:bg-emerald-600">Edit</button>
    </div>
  </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
  <div class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
      <h3 class="text-lg font-semibold">Edit Buku</h3>
      <button onclick="closeEditModal()" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
    </div>

    <h4 class="mb-3 text-sm font-semibold text-slate-700">Informasi Buku</h4>

    <div class="grid gap-x-6 gap-y-3 md:grid-cols-2">
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku<span class="text-rose-500">*</span></label>
        <input id="editCode" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">ISBN<span class="text-rose-500">*</span></label>
        <input id="editIsbn" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku<span class="text-rose-500">*</span></label>
        <input id="editTitle" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Penerbit<span class="text-rose-500">*</span></label>
        <input id="editPublisher" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang<span class="text-rose-500">*</span></label>
        <input id="editAuthor" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Lokasi Rak</label>
        <input id="editRack" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit</label>
        <select id="editYear" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
          <option>2018</option>
          <option>2019</option>
          <option>2020</option>
          <option>2021</option>
          <option>2022</option>
        </select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
        <select id="editCategory" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
          <option>Pendidikan</option>
          <option>Fiksi</option>
          <option>Bisnis</option>
        </select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Jumlah Buku<span class="text-rose-500">*</span></label>
        <input id="editStock" type="number" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Cover Buku</label>
        <label class="flex h-[90px] w-[110px] cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 text-slate-400 hover:bg-slate-100 transition">
          <img id="editPreviewImg" src="" alt="Preview Cover" class="h-full w-full rounded-lg object-cover">
          <input type="file" class="hidden" accept="image/*">
        </label>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
      <button onclick="closeEditModal()" class="rounded-lg bg-slate-500 px-4 py-2 text-sm text-white hover:bg-slate-600">Kembali</button>
      <button class="rounded-lg bg-violet-500 px-4 py-2 text-sm text-white hover:bg-violet-600">Simpan</button>
    </div>
  </div>
</div>

<div id="tambahModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
  <div class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
      <h3 class="text-lg font-semibold">Tambah Buku</h3>
      <button onclick="closeTambahModal()" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
    </div>

    <h4 class="mb-3 text-sm font-semibold text-slate-700">Informasi Buku</h4>

    <div class="grid gap-x-6 gap-y-3 md:grid-cols-2">
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku<span class="text-rose-500">*</span></label>
        <input type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" value="">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">ISBN<span class="text-rose-500">*</span></label>
        <input type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" value="">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku<span class="text-rose-500">*</span></label>
        <input type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" value="">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Penerbit<span class="text-rose-500">*</span></label>
        <input type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" value="">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang<span class="text-rose-500">*</span></label>
        <input type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" value="">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Lokasi Rak</label>
        <input type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" value="">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit</label>
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
          <option>2018</option>
          <option>2019</option>
          <option>2020</option>
        </select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
        <select class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
          <option>Pendidikan</option>
          <option>Fiksi</option>
          <option>Bisnis</option>
        </select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Jumlah Buku<span class="text-rose-500">*</span></label>
        <input type="number" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" value="">
      </div>

      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Upload Cover Buku</label>
        <label class="flex h-[90px] w-[110px] cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 text-slate-400 hover:bg-slate-100 transition">
          <span class="text-2xl">🖼️</span>
          <span class="mt-1 text-[10px]">Upload Cover</span>
          <input type="file" class="hidden" accept="image/*">
        </label>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
      <button onclick="closeTambahModal()" class="rounded-lg bg-slate-500 px-4 py-2 text-sm text-white hover:bg-slate-600">Kembali</button>
      <button class="rounded-lg bg-violet-500 px-4 py-2 text-sm text-white hover:bg-violet-600">Simpan</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const books = [
  { code: "BPKSJ124", title: "Atomic Habits", author: "James Clear", category: "Pendidikan", publisher: "Avery Publishing", year: "2018", stock: 30, rack: "A5-01", isbn: "978-602-06-3317-6", img: "https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" },
  { code: "BPKSJ125", title: "Tentang Kamu", author: "Tere Liye", category: "Fiksi", publisher: "Republika", year: "2016", stock: 29, rack: "A5-02", isbn: "978-602-04-3317-7", img: "https://m.media-amazon.com/images/I/81af+MCATTL.jpg" },
  { code: "BPKSJ126", title: "Rich Dad Poor Dad", author: "Robert Kiyosaki", category: "Bisnis", publisher: "Plata", year: "2017", stock: 30, rack: "A5-03", isbn: "978-1612680194", img: "https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg" },
  { code: "BPKSJ127", title: "Filosofi Teras", author: "Henry Manampiring", category: "Pendidikan", publisher: "Kompas", year: "2019", stock: 30, rack: "A5-04", isbn: "978-6024125189", img: "https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg" },
  { code: "BPKSJ128", title: "Psikologi Uang", author: "Morgan Housel", category: "Bisnis", publisher: "Harriman", year: "2021", stock: 30, rack: "A5-05", isbn: "978-0857197689", img: "https://m.media-amazon.com/images/I/71g2ednj0JL.jpg" },
  { code: "BPKSJ129", title: "Bumi", author: "Tere Liye", category: "Fiksi", publisher: "Gramedia", year: "2014", stock: 30, rack: "A5-06", isbn: "978-6020324784", img: "https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg" },
  { code: "BPKSJ130", title: "Laut Bercerita", author: "Leila S Chudori", category: "Fiksi", publisher: "Kepustakaan Populer", year: "2017", stock: 20, rack: "A5-07", isbn: "978-6024246945", img: "https://m.media-amazon.com/images/I/81af+MCATTL.jpg" },
  { code: "BPKSJ131", title: "Sapiens", author: "Yuval Noah Harari", category: "Pendidikan", publisher: "Harper", year: "2015", stock: 15, rack: "A5-08", isbn: "978-0062316097", img: "https://m.media-amazon.com/images/I/713jIoMO3UL.jpg" },
  { code: "BPKSJ132", title: "The Psychology of Money", author: "Morgan Housel", category: "Bisnis", publisher: "Harriman House", year: "2020", stock: 17, rack: "A5-09", isbn: "978-0857197689", img: "https://m.media-amazon.com/images/I/71g2ednj0JL.jpg" },
  { code: "BPKSJ133", title: "Bicara Itu Ada Seninya", author: "Oh Su Hyang", category: "Pendidikan", publisher: "Bhuana Ilmu Populer", year: "2018", stock: 22, rack: "A5-10", isbn: "978-6024553920", img: "https://m.media-amazon.com/images/I/71f6DceqZSL.jpg" },
  { code: "BPKSJ134", title: "Negeri 5 Menara", author: "Ahmad Fuadi", category: "Fiksi", publisher: "Gramedia", year: "2010", stock: 12, rack: "A5-11", isbn: "978-9792248616", img: "https://m.media-amazon.com/images/I/81o9Qd7StXL.jpg" }
];

const bookContainer = document.getElementById("bookContainer");
const paginationContainer = document.getElementById("paginationContainer");
const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");
let selectedBook = null;
let filteredBooks = [...books];
let currentPage = 1;
const booksPerPage = 6;

function renderBooks(data) {
  bookContainer.innerHTML = "";
  const start = (currentPage - 1) * booksPerPage;
  const paginatedBooks = data.slice(start, start + booksPerPage);

  paginatedBooks.forEach((book) => {
    bookContainer.innerHTML += `
      <div class="rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm">
        <img src="${book.img}" class="mx-auto h-40 rounded-lg object-cover">
        <h4 class="mt-2 font-semibold text-slate-800">${book.title}</h4>
        <p class="text-xs text-slate-500">${book.author}</p>
        <p class="text-xs text-slate-500">${book.category}</p>
        <p class="text-xs text-slate-500">Tersedia : ${book.stock}</p>
        <button onclick='openDetailModal(${JSON.stringify(book)})'
          class="mt-2 inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
          <span>Lihat Detail</span>
        </button>
      </div>
    `;
  });

  renderPagination(data.length);
}

function renderPagination(totalItems) {
  const totalPages = Math.ceil(totalItems / booksPerPage);
  paginationContainer.innerHTML = "";

  if (totalPages <= 1) return;

  let html = `<div class="inline-flex overflow-hidden rounded-lg border border-slate-200">`;
  html += `<button onclick="changePage(${Math.max(1, currentPage - 1)})" class="border-r border-slate-200 bg-white px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">&lt;</button>`;

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button onclick="changePage(${i})"
        class="border-r border-slate-200 px-4 py-1.5 text-xs ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 hover:bg-slate-50'}">
        ${i}
      </button>
    `;
  }

  html += `<button onclick="changePage(${Math.min(totalPages, currentPage + 1)})" class="bg-white px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">&gt;</button>`;
  html += `</div>`;
  paginationContainer.innerHTML = html;
}

function changePage(page) {
  const totalPages = Math.ceil(filteredBooks.length / booksPerPage);
  if (page < 1 || page > totalPages) return;
  currentPage = page;
  renderBooks(filteredBooks);
}

function filterBooks() {
  const keyword = searchInput.value.toLowerCase();
  const category = categoryFilter.value;

  filteredBooks = books.filter((book) => {
    const matchKeyword =
      book.title.toLowerCase().includes(keyword) ||
      book.author.toLowerCase().includes(keyword) ||
      book.publisher.toLowerCase().includes(keyword);
    const matchCategory = category === "all" || book.category === category;
    return matchKeyword && matchCategory;
  });

  currentPage = 1;
  renderBooks(filteredBooks);
}

function openDetailModal(book) {
  selectedBook = book;
  document.getElementById("detailTitle").textContent = book.title;
  document.getElementById("detailCode").textContent = book.code;
  document.getElementById("detailAuthor").textContent = book.author;
  document.getElementById("detailPublisher").textContent = book.publisher;
  document.getElementById("detailYear").textContent = book.year;
  document.getElementById("detailCategory").textContent = book.category;
  document.getElementById("detailStock").textContent = `${book.stock} dari 30 tersedia`;
  document.getElementById("detailRack").textContent = book.rack;
  document.getElementById("detailIsbn").textContent = book.isbn;
  document.getElementById("detailImg").src = book.img;

  const modal = document.getElementById("detailModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

function closeDetailModal() {
  const modal = document.getElementById("detailModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

function openEditModal() {
  if (!selectedBook) return;

  document.getElementById("editCode").value = selectedBook.code;
  document.getElementById("editIsbn").value = selectedBook.isbn;
  document.getElementById("editTitle").value = selectedBook.title;
  document.getElementById("editPublisher").value = selectedBook.publisher;
  document.getElementById("editAuthor").value = selectedBook.author;
  document.getElementById("editRack").value = selectedBook.rack;
  document.getElementById("editYear").value = selectedBook.year;
  document.getElementById("editCategory").value = selectedBook.category;
  document.getElementById("editStock").value = selectedBook.stock;
  document.getElementById("editPreviewImg").src = selectedBook.img;

  closeDetailModal();
  const modal = document.getElementById("editModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

function closeEditModal() {
  const modal = document.getElementById("editModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

function openTambahModal() {
  const modal = document.getElementById("tambahModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

function closeTambahModal() {
  const modal = document.getElementById("tambahModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

searchInput.addEventListener("keyup", filterBooks);
categoryFilter.addEventListener("change", filterBooks);
renderBooks(filteredBooks);
</script>
@endpush
