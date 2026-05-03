@extends('layouts.mahasiswa')

@section('title', 'Dashboard')
@section('active_page', 'beranda')
@section('page_title', 'Beranda')
@section('page_subtitle', 'Pantau aktivitas peminjaman buku Anda di sini.')

@section('content')
<!-- WELCOME -->
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-700 to-cyan-600 p-6 text-white shadow-lg">
  <h2 class="mb-1 text-xl font-semibold">Selamat Datang, Luthfi Dwi Apriyadi</h2>
  <p class="text-sm text-blue-100">Senin, 29 Maret 2026</p>
</div>

<!-- INFO -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Total Peminjaman</p>
    <p class="mt-2 text-2xl font-bold text-slate-800">1</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Sedang Dipinjam</p>
    <p class="mt-2 text-2xl font-bold text-blue-700">1</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Sudah Dikembalikan</p>
    <p class="mt-2 text-2xl font-bold text-emerald-600">1</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Terlambat</p>
    <p class="mt-2 text-2xl font-bold text-rose-600">1</p>
  </div>
</div>

<!-- DIPINJAM -->
<div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 p-4 font-semibold">Buku Yang Sedang Dipinjam</div>
  <div class="flex items-center p-4">
    <img src="https://m.media-amazon.com/images/I/81af+MCATTL.jpg" class="w-24 rounded-lg object-cover shadow-sm" alt="Sampul Tentang Kamu">
    <div class="ml-4">
      <h3 class="font-semibold text-slate-800">Tentang Kamu</h3>
      <p class="text-sm text-slate-500">Tere Liye</p>
      <p class="text-sm text-slate-500">Pinjam: 23 Maret 2026</p>
      <p class="text-sm text-slate-500">Kembali: 30 Maret 2026</p>
      <span class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
        Sedang Dipinjam
      </span>
    </div>
  </div>
</div>

<!-- REKOMENDASI -->
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 p-4 font-semibold">Rekomendasi Buku Untuk Anda</div>

  <div class="grid grid-cols-2 gap-3 p-4 md:grid-cols-3 xl:grid-cols-6" id="rekomendasi-container"></div>
</div>

<!-- MODAL DETAIL BUKU -->
<div id="bookDetailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4" aria-hidden="true">
  <div class="relative max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <button type="button" onclick="closeBookDetail()" class="absolute right-4 top-4 text-2xl leading-none text-slate-500 hover:text-slate-800" aria-label="Tutup">&times;</button>
    <h2 class="mb-4 border-b border-slate-200 pb-3 pr-10 text-xl font-bold text-slate-900">Detail Buku</h2>

    <div class="grid gap-6 md:grid-cols-2">
      <div>
        <img id="bookDetailImg" src="" alt="" class="w-full max-w-xs rounded-xl border border-slate-200 object-cover shadow-md">
      </div>
      <div class="space-y-2 text-sm text-slate-700">
        <h3 id="bookDetailTitle" class="text-lg font-bold text-slate-900"></h3>
        <p><span class="font-semibold text-slate-600">Kode Buku:</span> <span id="bookDetailCode"></span></p>
        <p><span class="font-semibold text-slate-600">Pengarang:</span> <span id="bookDetailAuthor"></span></p>
        <p><span class="font-semibold text-slate-600">Penerbit:</span> <span id="bookDetailPublisher"></span></p>
        <p><span class="font-semibold text-slate-600">Tahun Terbit:</span> <span id="bookDetailYear"></span></p>
        <p>
          <span class="font-semibold text-slate-600">Kategori:</span>
          <span id="bookDetailCategory" class="ml-1 inline-block rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"></span>
        </p>
        <p>
          <span class="font-semibold text-slate-600">Ketersediaan:</span>
          <span id="bookDetailStock" class="ml-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium text-white"></span>
        </p>
        <p><span class="font-semibold text-slate-600">Lokasi Rak:</span> <span id="bookDetailRack"></span></p>
        <p><span class="font-semibold text-slate-600">ISBN:</span> <span id="bookDetailIsbn"></span></p>
        <div class="mt-4">
          <p class="font-semibold text-slate-600">Deskripsi</p>
          <p id="bookDetailDesc" class="mt-1 text-xs leading-relaxed text-slate-600"></p>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end border-t border-slate-100 pt-4">
      <button type="button" onclick="closeBookDetail()"
        class="rounded-lg bg-slate-600 px-6 py-2 text-sm font-medium text-white hover:bg-slate-700 transition">
        Kembali
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const books = [
  { id: 'ah', title: 'Atomic Habits', author: 'James Clear', publisher: 'Avery Publishing', year: '2018', yearLabel: '16 Oktober 2018', category: 'Pendidikan', code: 'BPKSJ124', rack: 'A5-01', isbn: '978-0735211292', available: true, stockLabel: '12 tersedia', img: 'https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg', description: 'Panduan membangun kebiasaan baik kecil yang membawa perubahan besar dalam hidup.' },
  { id: 'rd', title: 'Rich Dad Poor Dad', author: 'Robert T. Kiyosaki', publisher: 'Plata Publishing', year: '2017', yearLabel: '11 April 2017', category: 'Bisnis', code: 'BPKSJ125', rack: 'A5-03', isbn: '978-1612680194', available: true, stockLabel: '8 tersedia', img: 'https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg', description: 'Pemikiran tentang literasi keuangan dan investasi dari sudut pandang dua figur ayah.' },
  { id: 'ft', title: 'Filosofi Teras', author: 'Henry Manampiring', publisher: 'Kompas Gramedia', year: '2018', yearLabel: '3 Maret 2018', category: 'Pendidikan', code: 'BPKSJ126', rack: 'A5-04', isbn: '978-6024125189', available: false, stockLabel: 'Dipinjam semua', img: 'https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg', description: 'Pengenalan filosofi Stoa untuk menghadapi hidup modern dengan lebih tenang.' },
  { id: 'pu', title: 'Psikologi Uang', author: 'Morgan Housel', publisher: 'Harriman House', year: '2020', yearLabel: '8 September 2020', category: 'Bisnis', code: 'BPKSJ127', rack: 'A5-05', isbn: '978-0857197689', available: true, stockLabel: '5 tersedia', img: 'https://m.media-amazon.com/images/I/71g2ednj0JL.jpg', description: 'Cerita pendek tentang bagaimana manusia berpikir tentang uang dan kekayaan.' },
  { id: 'lb', title: 'Laut Bercerita', author: 'Leila S. Chudori', publisher: 'Kepustakaan Populer Gramedia', year: '2017', yearLabel: '20 Januari 2017', category: 'Fiksi', code: 'BPKSJ128', rack: 'A5-06', isbn: '978-6024246945', available: true, stockLabel: '6 tersedia', img: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg', description: 'Novel sejarah Indonesia pasca-1965 yang mengisahkan persahabatan dan pengasingan.' },
  { id: 'bm', title: 'Bumi', author: 'Tere Liye', publisher: 'Gramedia Pustaka Utama', year: '2014', yearLabel: '28 Januari 2014', category: 'Fiksi', code: 'BPKSJ129', rack: 'A5-07', isbn: '978-6020324784', available: false, stockLabel: 'Dipinjam semua', img: 'https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg', description: 'Seri petualangan dunia paralel yang memadukan fiksi ilmiah dan persahabatan.' },
];

function shuffle(array) {
  return [...array].sort(() => 0.5 - Math.random());
}

let rekomendasi = [...books.filter((b) => b.available), ...books.filter((b) => !b.available)];
rekomendasi = shuffle(rekomendasi).slice(0, 6);

const container = document.getElementById('rekomendasi-container');
const modal = document.getElementById('bookDetailModal');

function fillBookDetail(book) {
  document.getElementById('bookDetailImg').src = book.img;
  document.getElementById('bookDetailImg').alt = book.title;
  document.getElementById('bookDetailTitle').textContent = book.title;
  document.getElementById('bookDetailCode').textContent = book.code;
  document.getElementById('bookDetailAuthor').textContent = book.author;
  document.getElementById('bookDetailPublisher').textContent = book.publisher;
  document.getElementById('bookDetailYear').textContent = book.yearLabel || book.year;
  document.getElementById('bookDetailCategory').textContent = book.category;
  document.getElementById('bookDetailRack').textContent = book.rack;
  document.getElementById('bookDetailIsbn').textContent = book.isbn;
  document.getElementById('bookDetailDesc').textContent = book.description;

  const stockEl = document.getElementById('bookDetailStock');
  const stockBg = book.available ? 'bg-emerald-500' : 'bg-rose-500';
  stockEl.textContent = book.stockLabel || (book.available ? 'Tersedia' : 'Tidak tersedia');
  stockEl.className = 'ml-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium text-white ' + stockBg;
}

function openBookDetail(bookId) {
  const book = books.find((b) => b.id === bookId);
  if (!book) return;
  fillBookDetail(book);
  modal.classList.remove('hidden');
  modal.classList.add('flex');
  modal.setAttribute('aria-hidden', 'false');
  document.body.classList.add('overflow-hidden');
}

function closeBookDetail() {
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  modal.setAttribute('aria-hidden', 'true');
  document.body.classList.remove('overflow-hidden');
}

window.openBookDetail = openBookDetail;
window.closeBookDetail = closeBookDetail;

rekomendasi.forEach((book) => {
  const escAttr = (s) => String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
  container.innerHTML += `
    <div class="rounded-xl border border-slate-200 bg-white p-2 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
      <img src="${book.img}" alt="${escAttr(book.title)}" class="h-32 w-full rounded-lg object-cover">
      <small class="mt-2 block truncate font-medium text-slate-700" title="${escAttr(book.title)}">${escAttr(book.title)}</small>
      <button type="button" data-book-id="${escAttr(book.id)}" class="js-rekomendasi-detail mt-2 w-full rounded-lg bg-blue-600 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition">
        Detail
      </button>
    </div>
  `;
});

container.addEventListener('click', (e) => {
  const btn = e.target.closest('.js-rekomendasi-detail');
  if (!btn) return;
  const id = btn.getAttribute('data-book-id');
  if (id) openBookDetail(id);
});

modal.addEventListener('click', (e) => {
  if (e.target === modal) closeBookDetail();
});
</script>
@endpush