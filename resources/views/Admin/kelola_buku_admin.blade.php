@extends('layouts.admin')

@section('title', 'Kelola Buku Admin')
@section('active_page', 'kelola-buku')
@section('page_title', 'Kelola Buku')

@section('content')
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <i class="bi bi-funnel text-[#1E376E]"></i>
    <h2 class="font-semibold text-[#1E376E]">Filter Buku</h2>
  </div>
  <div class="p-5">
    <p class="mb-4 text-sm text-slate-500">Cari berdasarkan judul, pengarang, penerbit, atau kategori.</p>
    <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
    <div class="relative md:col-span-7">
      <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
    <input id="searchInput" type="text" placeholder="Cari judul, pengarang, atau penerbit..."
      class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
    </div>
    <div class="relative md:col-span-3">
      <i class="bi bi-tags absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
    <select id="categoryFilter"
      class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
      <option value="all">Semua Kategori</option>
      <option value="Pendidikan">Pendidikan</option>
      <option value="Fiksi">Fiksi</option>
      <option value="Bisnis">Bisnis</option>
    </select>
    </div>
    <button type="button" onclick="openTambahModal()"
      class="md:col-span-2 inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E376E] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162d5c]">
      <i class="bi bi-plus-lg"></i> Tambah Buku
    </button>
    </div>
  </div>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center justify-between gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <div class="flex items-center gap-2">
      <i class="bi bi-grid-3x3-gap text-[#1E376E]"></i>
      <h3 class="font-semibold text-[#1E376E]">Koleksi Buku</h3>
    </div>
    <span id="bookCount" class="text-xs font-medium text-slate-500"></span>
  </div>
  <div class="p-5">
    <div id="book-empty" class="hidden rounded-xl border border-dashed border-slate-200 bg-slate-50 py-12 text-center text-sm text-slate-500">
      <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
      Tidak ada buku yang cocok dengan pencarian Anda.
    </div>
    <div id="bookContainer" class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4"></div>
    <div id="paginationContainer" class="mt-6 flex justify-center"></div>
  </div>
</div>

<div id="detailModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
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
        <div class="grid grid-cols-3 gap-2">
          <p class="text-slate-500">Deskripsi</p>
          <p class="col-span-2 text-slate-700" id="detailDescription">-</p>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
      <button type="button" onclick="openHapusBukuModal()" class="rounded-lg bg-rose-500 px-4 py-2 text-sm text-white hover:bg-rose-600">
        <i class="bi bi-trash"></i> Hapus
      </button>
      <button type="button" onclick="openEditModal()" class="rounded-lg bg-emerald-500 px-4 py-2 text-sm text-white hover:bg-emerald-600">Edit</button>
    </div>
  </div>
</div>

<div id="hapusBukuModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-[60] justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
      <i class="bi bi-exclamation-triangle text-2xl"></i>
    </div>
    <h3 class="text-center text-base font-semibold text-slate-800">Hapus Buku?</h3>
    <p class="mt-1 text-center text-xs text-slate-500">Buku <span id="hapusBukuTitle" class="font-semibold text-slate-700"></span> akan dihapus permanen.</p>

    <div class="mt-5 flex justify-center gap-2">
      <button type="button" onclick="closeHapusBukuModal()"
        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
        Batal
      </button>
      <button type="button" onclick="confirmDeleteBuku()"
        class="rounded-lg bg-rose-500 px-4 py-2 text-xs font-medium text-white hover:bg-rose-600">
        Hapus
      </button>
    </div>
  </div>
</div>

<div id="editModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
      <h3 class="text-lg font-semibold">Edit Buku</h3>
      <button onclick="closeEditModal()" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
    </div>

    <h4 class="mb-3 text-sm font-semibold text-slate-700">Informasi Buku</h4>

    <div class="grid gap-x-6 gap-y-3 md:grid-cols-2">
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku<span class="text-rose-500">*</span></label>
        <input id="editCode" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm uppercase">
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
        <select id="editYear" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></select>
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
        <label class="flex h-[120px] w-[100px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border border-dashed border-slate-300 bg-slate-50 text-slate-400 hover:bg-slate-100 transition">
          <img id="editPreviewImg" src="" alt="Preview Cover" class="hidden h-full w-full object-cover">
          <span id="editCoverPlaceholder" class="text-center text-[10px] leading-tight"><span class="text-2xl block">🖼️</span>Upload Cover</span>
          <input id="editCover" type="file" class="hidden" accept="image/*">
        </label>
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Deskripsi</label>
        <textarea id="editDescription" rows="3" placeholder="Ringkasan atau sinopsis buku..."
          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
      <button onclick="closeEditModal()" class="rounded-lg bg-slate-500 px-4 py-2 text-sm text-white hover:bg-slate-600">Kembali</button>
      <button type="button" onclick="saveEditBook()" class="rounded-lg bg-violet-500 px-4 py-2 text-sm text-white hover:bg-violet-600">Simpan</button>
    </div>
  </div>
</div>

<div id="tambahModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
      <h3 class="text-lg font-semibold">Tambah Buku</h3>
      <button onclick="closeTambahModal()" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
    </div>

    <h4 class="mb-3 text-sm font-semibold text-slate-700">Informasi Buku</h4>

    <div class="grid gap-x-6 gap-y-3 md:grid-cols-2">
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku<span class="text-rose-500">*</span></label>
        <input id="tambahCode" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm uppercase">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">ISBN</label>
        <input id="tambahIsbn" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku<span class="text-rose-500">*</span></label>
        <input id="tambahTitle" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Penerbit<span class="text-rose-500">*</span></label>
        <input id="tambahPublisher" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang<span class="text-rose-500">*</span></label>
        <input id="tambahAuthor" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Lokasi Rak</label>
        <input id="tambahRack" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit<span class="text-rose-500">*</span></label>
        <select id="tambahYear" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori<span class="text-rose-500">*</span></label>
        <select id="tambahCategory" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
          <option value="Pendidikan">Pendidikan</option>
          <option value="Fiksi">Fiksi</option>
          <option value="Bisnis">Bisnis</option>
        </select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Jumlah Buku<span class="text-rose-500">*</span></label>
        <input id="tambahStock" type="number" min="0" value="0" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Upload Cover Buku</label>
        <label class="flex h-[120px] w-[100px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border border-dashed border-slate-300 bg-slate-50 text-slate-400 hover:bg-slate-100 transition">
          <img id="tambahPreviewImg" src="" alt="Preview Cover" class="hidden h-full w-full object-cover">
          <span id="tambahCoverPlaceholder" class="text-center text-[10px] leading-tight"><span class="text-2xl block">🖼️</span>Upload Cover</span>
          <input id="tambahCover" type="file" class="hidden" accept="image/*">
        </label>
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Deskripsi</label>
        <textarea id="tambahDescription" rows="3" placeholder="Ringkasan atau sinopsis buku..."
          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
      <button onclick="closeTambahModal()" class="rounded-lg bg-slate-500 px-4 py-2 text-sm text-white hover:bg-slate-600">Kembali</button>
      <button type="button" onclick="saveTambahBook()" class="rounded-lg bg-violet-500 px-4 py-2 text-sm text-white hover:bg-violet-600">Simpan</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let books = [];
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const defaultCover = "{{ asset('images/' . rawurlencode('Cover buku 1.jpg')) }}";

const bookContainer = document.getElementById("bookContainer");
const paginationContainer = document.getElementById("paginationContainer");
const bookCountEl = document.getElementById("bookCount");
const bookEmptyEl = document.getElementById("book-empty");
const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");

function escHtml(s) {
  return String(s).replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/</g, "&lt;");
}

function fillYearSelect(selectEl, selected) {
  if (!selectEl) return;
  const now = new Date().getFullYear();
  let html = "";
  for (let y = now; y >= 1990; y--) {
    html += `<option value="${y}"${String(selected) === String(y) ? " selected" : ""}>${y}</option>`;
  }
  selectEl.innerHTML = html;
}

function updateCategoryFilter(categories) {
  const current = categoryFilter.value;
  let html = `<option value="all">Semua Kategori</option>`;
  const defaults = ["Pendidikan", "Fiksi", "Bisnis"];
  const merged = [...new Set([...defaults, ...(categories || [])])];
  merged.forEach((cat) => {
    html += `<option value="${escHtml(cat)}">${escHtml(cat)}</option>`;
  });
  categoryFilter.innerHTML = html;
  if ([...categoryFilter.options].some((o) => o.value === current)) {
    categoryFilter.value = current;
  }
}

let selectedBook = null;
let filteredBooks = [];
let currentPage = 1;
const booksPerPage = 6;

async function fetchBooks() {
  const res = await fetch("{{ route('admin.buku.list') }}", { headers: { Accept: "application/json" } });
  const json = await res.json();
  books = json.data || [];
  filteredBooks = [...books];
  updateCategoryFilter(json.categories || []);
  currentPage = 1;
  renderBooks(filteredBooks);
}

function previewCoverInput(input, imgEl, placeholderEl) {
  const file = input.files?.[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (e) => {
    imgEl.src = e.target.result;
    imgEl.classList.remove("hidden");
    if (placeholderEl) placeholderEl.classList.add("hidden");
  };
  reader.readAsDataURL(file);
}

function resetCoverPreview(imgEl, placeholderEl, inputEl) {
  if (inputEl) inputEl.value = "";
  if (imgEl) {
    imgEl.src = "";
    imgEl.classList.add("hidden");
  }
  if (placeholderEl) placeholderEl.classList.remove("hidden");
}

function showCoverPreview(imgEl, placeholderEl, url) {
  if (!imgEl) return;
  imgEl.src = url || defaultCover;
  imgEl.classList.remove("hidden");
  if (placeholderEl) placeholderEl.classList.add("hidden");
}

function appendBookFields(formData, payload) {
  Object.entries(payload).forEach(([key, value]) => formData.append(key, value ?? ""));
}

function showValidationErrors(json) {
  const errors = json?.errors;
  if (!errors) {
    alert(json?.message || "Gagal menyimpan data.");
    return;
  }
  const lines = [];
  Object.values(errors).forEach((msgs) => {
    (Array.isArray(msgs) ? msgs : [msgs]).forEach((m) => lines.push(m));
  });
  alert(lines.length ? lines.join("\n") : (json.message || "Validasi gagal"));
}

function bookPayloadFromEdit() {
  return {
    code: document.getElementById("editCode").value.trim(),
    title: document.getElementById("editTitle").value.trim(),
    author: document.getElementById("editAuthor").value.trim(),
    publisher: document.getElementById("editPublisher").value.trim(),
    category: document.getElementById("editCategory").value,
    year: document.getElementById("editYear").value,
    stock: document.getElementById("editStock").value,
    isbn: document.getElementById("editIsbn").value.trim(),
    rack: document.getElementById("editRack").value.trim(),
    description: document.getElementById("editDescription").value.trim(),
  };
}

function bookPayloadFromTambah() {
  return {
    code: document.getElementById("tambahCode").value.trim(),
    title: document.getElementById("tambahTitle").value.trim(),
    author: document.getElementById("tambahAuthor").value.trim(),
    publisher: document.getElementById("tambahPublisher").value.trim(),
    category: document.getElementById("tambahCategory").value,
    year: document.getElementById("tambahYear").value,
    stock: document.getElementById("tambahStock").value,
    isbn: document.getElementById("tambahIsbn").value.trim(),
    rack: document.getElementById("tambahRack").value.trim(),
    description: document.getElementById("tambahDescription").value.trim(),
  };
}

async function saveEditBook() {
  if (!selectedBook) return;
  const payload = bookPayloadFromEdit();
  if (!payload.code || !payload.title || !payload.author || !payload.publisher) {
    alert("Kode buku, judul, pengarang, dan penerbit wajib diisi.");
    return;
  }

  const formData = new FormData();
  appendBookFields(formData, payload);
  formData.append("_method", "PUT");
  const coverFile = document.getElementById("editCover")?.files?.[0];
  if (coverFile) formData.append("cover", coverFile);

  const res = await fetch(`{{ route('admin.buku.list') }}/${encodeURIComponent(selectedBook.id)}`, {
    method: "POST",
    headers: {
      Accept: "application/json",
      "X-CSRF-TOKEN": csrf,
    },
    body: formData,
  });

  const json = await res.json();
  if (!res.ok) {
    showValidationErrors(json);
    return;
  }

  closeEditModal();
  await fetchBooks();
  alert(json.message || "Buku berhasil diperbarui.");
}

async function saveTambahBook() {
  const payload = bookPayloadFromTambah();
  if (!payload.code || !payload.title || !payload.author || !payload.publisher) {
    alert("Kode buku, judul, pengarang, dan penerbit wajib diisi.");
    return;
  }
  if (payload.stock === "" || Number(payload.stock) < 0) {
    alert("Jumlah buku wajib diisi (minimal 0).");
    return;
  }
  if (!payload.year) {
    alert("Tahun terbit wajib dipilih.");
    return;
  }

  const formData = new FormData();
  appendBookFields(formData, payload);
  const coverFile = document.getElementById("tambahCover")?.files?.[0];
  if (coverFile) formData.append("cover", coverFile);

  const res = await fetch("{{ route('admin.buku.store') }}", {
    method: "POST",
    headers: {
      Accept: "application/json",
      "X-CSRF-TOKEN": csrf,
    },
    body: formData,
  });

  const json = await res.json();
  if (!res.ok) {
    alert(json.message || "Gagal menambah buku.");
    return;
  }

  closeTambahModal();
  await fetchBooks();
  alert(json.message || "Buku berhasil ditambahkan.");
}

function renderBooks(data) {
  bookContainer.innerHTML = "";
  if (bookEmptyEl) bookEmptyEl.classList.toggle("hidden", data.length > 0);
  if (bookCountEl) bookCountEl.textContent = data.length ? `${data.length} buku` : "";
  const start = (currentPage - 1) * booksPerPage;
  const paginatedBooks = data.slice(start, start + booksPerPage);

  paginatedBooks.forEach((book) => {
    bookContainer.innerHTML += `
      <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
        <img src="${escHtml(book.img)}" alt="${escHtml(book.title)}" onerror="this.onerror=null;this.src=defaultCover;" class="h-44 w-full object-cover">
        <div class="p-3">
          <h6 class="line-clamp-2 font-bold text-[#1E376E]" title="${escHtml(book.title)}">${escHtml(book.title)}</h6>
          <p class="mt-1 text-sm text-slate-500">${escHtml(book.author)}</p>
          <p class="text-sm text-slate-500">${escHtml(book.category)}</p>
          <p class="text-sm text-slate-500">Tersedia : ${book.stock}</p>
          <button type="button" data-id="${escHtml(book.id)}" class="js-detail-book mt-3 inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-[#1E376E] py-2 text-sm font-semibold text-white transition hover:bg-[#162d5c]">
            <i class="bi bi-eye"></i> Lihat Detail
          </button>
        </div>
      </article>
    `;
  });

  renderPagination(data.length);
}

function renderPagination(totalItems) {
  const totalPages = Math.ceil(totalItems / booksPerPage);
  paginationContainer.innerHTML = "";

  if (totalPages <= 1) return;

  let html = `<div class="inline-flex overflow-hidden rounded-lg border border-slate-200">`;
  html += `<button type="button" onclick="changePage(${Math.max(1, currentPage - 1)})" class="border-r border-slate-200 bg-white px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">&lt;</button>`;

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button type="button" onclick="changePage(${i})"
        class="border-r border-slate-200 px-4 py-1.5 text-xs ${i === currentPage ? 'bg-[#1E376E] text-white' : 'bg-white text-slate-700 hover:bg-slate-50'}">
        ${i}
      </button>
    `;
  }

  html += `<button type="button" onclick="changePage(${Math.min(totalPages, currentPage + 1)})" class="bg-white px-4 py-1.5 text-xs text-slate-700 hover:bg-slate-50">&gt;</button>`;
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
  document.getElementById("detailStock").textContent = `${book.stock} tersedia`;
  document.getElementById("detailRack").textContent = book.rack;
  document.getElementById("detailIsbn").textContent = book.isbn;
  document.getElementById("detailDescription").textContent =
    book.description && book.description !== "-" ? book.description : "-";
  document.getElementById("detailImg").src = book.img;

  fbShow('detailModal');
}

function closeDetailModal() {
  fbHide('detailModal');
}

function openHapusBukuModal() {
  if (!selectedBook) return;
  document.getElementById("hapusBukuTitle").textContent = selectedBook.title;
  fbShow('hapusBukuModal');
}

function closeHapusBukuModal() {
  fbHide('hapusBukuModal');
}

async function confirmDeleteBuku() {
  if (!selectedBook) return;

  const res = await fetch(`{{ route('admin.buku.list') }}/${encodeURIComponent(selectedBook.id)}`, {
    method: "DELETE",
    headers: {
      Accept: "application/json",
      "X-CSRF-TOKEN": csrf,
    },
  });

  const json = await res.json().catch(() => ({}));
  if (!res.ok) {
    alert(json.message || "Gagal menghapus buku.");
    return;
  }

  closeHapusBukuModal();
  closeDetailModal();
  selectedBook = null;
  await fetchBooks();
  alert(json.message || "Buku berhasil dihapus.");
}

function openEditModal() {
  if (!selectedBook) return;

  document.getElementById("editCode").value = selectedBook.code || "";
  document.getElementById("editIsbn").value = selectedBook.isbn === "-" ? "" : selectedBook.isbn;
  document.getElementById("editTitle").value = selectedBook.title;
  document.getElementById("editPublisher").value = selectedBook.publisher;
  document.getElementById("editAuthor").value = selectedBook.author;
  document.getElementById("editRack").value = selectedBook.rack === "-" ? "" : selectedBook.rack;
  fillYearSelect(document.getElementById("editYear"), selectedBook.year);
  document.getElementById("editCategory").value = selectedBook.category;
  document.getElementById("editStock").value = selectedBook.stock;
  document.getElementById("editDescription").value =
    selectedBook.description && selectedBook.description !== "-" ? selectedBook.description : "";
  showCoverPreview(
    document.getElementById("editPreviewImg"),
    document.getElementById("editCoverPlaceholder"),
    selectedBook.img
  );
  document.getElementById("editCover").value = "";

  closeDetailModal();
  fbShow('editModal');
}

function closeEditModal() {
  fbHide('editModal');
}

function openTambahModal() {
  document.getElementById("tambahCode").value = "";
  document.getElementById("tambahTitle").value = "";
  document.getElementById("tambahIsbn").value = "";
  document.getElementById("tambahPublisher").value = "";
  document.getElementById("tambahAuthor").value = "";
  document.getElementById("tambahRack").value = "";
  document.getElementById("tambahStock").value = "0";
  document.getElementById("tambahDescription").value = "";
  resetCoverPreview(
    document.getElementById("tambahPreviewImg"),
    document.getElementById("tambahCoverPlaceholder"),
    document.getElementById("tambahCover")
  );
  fillYearSelect(document.getElementById("tambahYear"), new Date().getFullYear());
  document.getElementById("tambahCategory").value = "Pendidikan";
  fbShow('tambahModal');
}

function closeTambahModal() {
  fbHide('tambahModal');
}

bookContainer.addEventListener("click", (e) => {
  const btn = e.target.closest(".js-detail-book");
  if (!btn) return;
  const id = btn.getAttribute("data-id");
  const book = books.find((b) => String(b.id) === String(id));
  if (book) openDetailModal(book);
});

searchInput.addEventListener("keyup", filterBooks);
categoryFilter.addEventListener("change", filterBooks);
document.getElementById("tambahCover")?.addEventListener("change", (e) => {
  previewCoverInput(e.target, document.getElementById("tambahPreviewImg"), document.getElementById("tambahCoverPlaceholder"));
});
document.getElementById("editCover")?.addEventListener("change", (e) => {
  previewCoverInput(e.target, document.getElementById("editPreviewImg"), document.getElementById("editCoverPlaceholder"));
});
fillYearSelect(document.getElementById("editYear"), new Date().getFullYear());
fillYearSelect(document.getElementById("tambahYear"), new Date().getFullYear());
fetchBooks();
</script>
@endpush
