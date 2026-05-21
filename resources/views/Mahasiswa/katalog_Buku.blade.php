@extends('layouts.mahasiswa')

@section('title', 'Katalog Buku')
@section('active_page', 'katalog')
@section('page_title', 'Katalog Buku')
@section('page_subtitle', 'Temukan dan pinjam buku sesuai kebutuhan Anda.')

@section('content')

<!-- FILTER -->
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <i class="bi bi-funnel text-[#1E376E]"></i>
    <h2 class="font-semibold text-[#1E376E]">Filter Buku</h2>
  </div>
  <div class="p-5">
    <p class="mb-4 text-sm text-slate-500">Cari judul, pengarang, penerbit, kategori, nomor panggil, atau ISBN.</p>
    <div class="grid gap-3 md:grid-cols-2">
      <div class="relative">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" id="searchInput"
          class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20"
          placeholder="Cari nama buku, pengarang, nomor panggil...">
      </div>
      <div class="relative">
        <i class="bi bi-tags absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <select id="categoryFilter"
          class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
          <option value="all">Semua Kategori</option>
          <option value="Pendidikan">Pendidikan</option>
          <option value="Bisnis">Bisnis</option>
          <option value="Fiksi">Fiksi</option>
        </select>
      </div>
    </div>
  </div>
</div>

<!-- KATALOG -->
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
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4" id="book-container"></div>
  </div>
</div>

<!-- MODAL DETAIL -->
<div id="detailModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="relative max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <button type="button" data-modal-hide="detailModal" onclick="closeModal()" class="absolute right-4 top-4 text-2xl leading-none text-slate-500 hover:text-slate-800" aria-label="Tutup">&times;</button>
    <h2 class="mb-4 border-b border-slate-200 pb-3 pr-10 text-xl font-bold text-[#1E376E]">Detail Buku</h2>

    <div class="grid gap-6 md:grid-cols-2">
      <div>
        <img id="modalImg" src="" alt="" class="w-full max-w-xs rounded-xl border border-slate-200 object-cover shadow-md">
      </div>
      <div class="space-y-2 text-sm text-slate-700">
        <h3 id="modalTitle" class="text-lg font-bold text-slate-900"></h3>
        <p><span class="font-semibold text-slate-600">Nomor Panggil:</span> <span id="modalCode"></span></p>
        <p><span class="font-semibold text-slate-600">Pengarang:</span> <span id="modalAuthor"></span></p>
        <p><span class="font-semibold text-slate-600">Penerbit:</span> <span id="modalPublisher"></span></p>
        <p><span class="font-semibold text-slate-600">Tahun Terbit:</span> <span id="modalYear"></span></p>
        <p>
          <span class="font-semibold text-slate-600">Kategori:</span>
          <span id="modalCategory" class="ml-1 inline-block rounded-full bg-[#1E376E]/10 px-2.5 py-0.5 text-xs font-medium text-[#1E376E]"></span>
        </p>
        <p>
          <span class="font-semibold text-slate-600">Ketersediaan:</span>
          <span id="modalStock" class="ml-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium text-white"></span>
        </p>
        <p><span class="font-semibold text-slate-600">Lokasi Rak:</span> <span id="modalRack"></span></p>
        <p><span class="font-semibold text-slate-600">ISBN:</span> <span id="modalIsbn"></span></p>
        <div class="mt-3">
          <p class="font-semibold text-slate-600">Deskripsi</p>
          <p id="modalDesc" class="mt-1 text-xs leading-relaxed text-slate-600"></p>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end border-t border-slate-100 pt-4">
      <button type="button" data-modal-hide="detailModal" onclick="closeModal()"
        class="rounded-lg bg-[#1E376E] px-6 py-2 text-sm font-medium text-white hover:bg-[#162d5c] transition">
        Kembali
      </button>
    </div>
  </div>
</div>

<!-- MODAL PINJAM -->
<div id="pinjamModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="relative w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <button type="button" data-modal-hide="pinjamModal" onclick="closePinjamModal()" class="absolute right-4 top-4 text-2xl leading-none text-slate-500 hover:text-slate-800" aria-label="Tutup">&times;</button>
    <h2 class="mb-4 border-b border-slate-200 pb-3 pr-10 text-xl font-bold text-[#1E376E]">Pinjam Buku</h2>

    <div class="grid gap-6 md:grid-cols-2">
      <div>
        <img id="pinjamImg" src="" alt="" class="w-full rounded-xl border border-slate-200 object-cover shadow-md">
      </div>
      <div class="space-y-3 text-sm">
        <p><span class="font-semibold text-slate-600">Nomor Panggil:</span> <span id="pinjamCode"></span></p>
        <p><span class="font-semibold text-slate-600">Judul:</span> <span id="pinjamTitle" class="font-medium text-slate-900"></span></p>
        <p><span class="font-semibold text-slate-600">Pengarang:</span> <span id="pinjamAuthor"></span></p>
        <div>
          <label class="mb-1 block font-semibold text-slate-600">Tanggal Pinjam</label>
          <input type="date" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
        <div>
          <label class="mb-1 block font-semibold text-slate-600">Tanggal Kembali</label>
          <input type="date" class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
        <div>
          <label class="mb-1 block font-semibold text-slate-600">Denda</label>
          <input type="text" value="Rp2.000 / hari" disabled class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-slate-600">
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
      <button type="button" onclick="closePinjamModal()"
        class="rounded-lg border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
        Kembali
      </button>
      <button type="button" onclick="alert('Buku berhasil dipinjam!')"
        class="rounded-lg bg-emerald-500 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-600 transition">
        <i class="bi bi-check2-circle"></i> Pinjam
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let books = [];
let searchDebounce = null;
const defaultCover = "{{ asset('images/' . rawurlencode('Cover buku 1.jpg')) }}";

const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const container = document.getElementById('book-container');
const emptyState = document.getElementById('book-empty');
const bookCount = document.getElementById('bookCount');
const detailModal = document.getElementById('detailModal');
const pinjamModal = document.getElementById('pinjamModal');

function escAttr(s) {
  return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}

function stockText(book) {
  return book.stockLabel || (book.available ? `Tersedia: ${book.stock}` : 'Dipinjam semua');
}

function updateCategoryFilter(categories) {
  const current = categoryFilter.value;
  const defaults = ['Pendidikan', 'Bisnis', 'Fiksi'];
  const merged = [...new Set([...defaults, ...(categories || [])])];
  let html = '<option value="all">Semua Kategori</option>';
  merged.forEach((cat) => {
    html += `<option value="${escAttr(cat)}">${escAttr(cat)}</option>`;
  });
  categoryFilter.innerHTML = html;
  if ([...categoryFilter.options].some((o) => o.value === current)) {
    categoryFilter.value = current;
  }
}

function renderBooks(data) {
  const list = data;
  container.innerHTML = '';
  emptyState.classList.toggle('hidden', list.length > 0);
  bookCount.textContent = list.length ? `${list.length} buku` : '';

  list.forEach((book) => {
    const pinjamBtn = book.status === 'available'
      ? `<button type="button" data-pinjam-id="${escAttr(book.id)}" class="js-pinjam w-full rounded-lg bg-emerald-500 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">Pinjam Buku</button>`
      : `<button type="button" disabled class="w-full cursor-not-allowed rounded-lg bg-amber-200 py-2 text-sm font-semibold text-amber-900">Sedang Dipinjam</button>`;

    container.innerHTML += `
      <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
        <img src="${escAttr(book.img)}" alt="${escAttr(book.title)}" class="h-44 w-full object-cover">
        <div class="p-3">
          <h6 class="mt-2 line-clamp-2 font-bold text-[#1E376E]" title="${escAttr(book.title)}">${escAttr(book.title)}</h6>
          <p class="mt-1 text-sm text-slate-500">${escAttr(book.author)}</p>
          <p class="text-sm text-slate-500">${escAttr(book.category)}</p>
          <p class="text-sm text-slate-500">${escAttr(stockText(book))}</p>
          <div class="mt-3 space-y-2">
            <button type="button" data-detail-id="${escAttr(book.id)}" class="js-detail w-full rounded-lg bg-[#1E376E] py-2 text-sm font-semibold text-white transition hover:bg-[#162d5c]">
              Lihat Detail
            </button>
            ${pinjamBtn}
          </div>
        </div>
      </article>
    `;
  });
}

function findBook(id) {
  return books.find((b) => String(b.id) === String(id));
}

function openModal(bookId) {
  const book = findBook(bookId);
  if (!book) return;

  document.getElementById('modalImg').src = book.img;
  document.getElementById('modalImg').alt = book.title;
  document.getElementById('modalTitle').textContent = book.title;
  document.getElementById('modalCode').textContent = book.code;
  document.getElementById('modalAuthor').textContent = book.author;
  document.getElementById('modalPublisher').textContent = book.publisher;
  document.getElementById('modalYear').textContent = book.year;
  document.getElementById('modalCategory').textContent = book.category;
  document.getElementById('modalRack').textContent = book.rack;
  document.getElementById('modalIsbn').textContent = book.isbn;
  document.getElementById('modalDesc').textContent = book.description || '';

  const stockEl = document.getElementById('modalStock');
  const available = book.status === 'available';
  stockEl.textContent = available ? (book.stockLabel || `${book.stock} tersedia`) : (book.stockLabel || 'Dipinjam semua');
  stockEl.className = 'ml-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium text-white ' + (available ? 'bg-emerald-500' : 'bg-amber-500');

  fbShow('detailModal');
}

function closeModal() {
  fbHide('detailModal');
}

function openPinjamModal(bookId) {
  const book = findBook(bookId);
  if (!book) return;

  document.getElementById('pinjamImg').src = book.img;
  document.getElementById('pinjamImg').alt = book.title;
  document.getElementById('pinjamCode').textContent = book.code;
  document.getElementById('pinjamTitle').textContent = book.title;
  document.getElementById('pinjamAuthor').textContent = book.author;

  fbShow('pinjamModal');
}

function closePinjamModal() {
  fbHide('pinjamModal');
}

window.closeModal = closeModal;
window.closePinjamModal = closePinjamModal;

container.addEventListener('click', (e) => {
  const detailBtn = e.target.closest('.js-detail');
  if (detailBtn) {
    const id = detailBtn.getAttribute('data-detail-id');
    if (id) openModal(id);
    return;
  }
  const pinjamBtn = e.target.closest('.js-pinjam');
  if (pinjamBtn) {
    const id = pinjamBtn.getAttribute('data-pinjam-id');
    if (id) openPinjamModal(id);
  }
});

detailModal.addEventListener('click', (e) => {
  if (e.target === detailModal) closeModal();
});
pinjamModal.addEventListener('click', (e) => {
  if (e.target === pinjamModal) closePinjamModal();
});

function buildBooksListUrl() {
  const params = new URLSearchParams();
  const q = searchInput.value.trim();
  const cat = categoryFilter.value;
  if (q) params.set('q', q);
  if (cat && cat !== 'all') params.set('category', cat);
  const qs = params.toString();
  return "{{ route('mahasiswa.buku.list') }}" + (qs ? `?${qs}` : '');
}

function scheduleBookSearch() {
  clearTimeout(searchDebounce);
  searchDebounce = setTimeout(() => fetchBooks(), 350);
}

searchInput.addEventListener('keyup', scheduleBookSearch);
searchInput.addEventListener('search', scheduleBookSearch);
categoryFilter.addEventListener('change', fetchBooks);

async function fetchBooks() {
  const res = await fetch(buildBooksListUrl(), {
    headers: { Accept: 'application/json' },
  });
  const json = await res.json();
  books = json.data || [];
  updateCategoryFilter(json.categories || []);
  renderBooks(books);
}

fetchBooks();
</script>
@endpush
