@extends('layouts.mahasiswa')

@section('title', 'Dashboard')
@section('active_page', 'beranda')
@section('page_title', 'Beranda')
@section('page_subtitle', 'Jelajahi rekomendasi buku dan koleksi perpustakaan digital kampus.')

@section('content')
<!-- WELCOME -->
<div class="mb-6 rounded-2xl bg-gradient-to-br from-brand via-brand-light to-teal-600 p-6 text-white shadow-lg">
  @php
    $authUser = auth()->user();
    $userName = $authUser?->nama_pengguna ?? 'Pengguna';
    try {
        $todayId = now()->locale('id')->translatedFormat('l, d F Y');
    } catch (\Throwable $e) {
        $todayId = now()->format('d/m/Y');
    }
  @endphp
  <h2 class="mb-1 text-xl font-semibold">Selamat Datang, {{ $userName }}</h2>
  <p class="text-sm text-blue-100">{{ $todayId }}</p>
</div>

<!-- REKOMENDASI -->
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center justify-between gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <div class="flex items-center gap-2">
      <i class="bi bi-stars text-[#1E376E]"></i>
      <h3 class="font-semibold text-[#1E376E]">Rekomendasi Buku Untuk Anda</h3>
    </div>
    <a href="{{ route('mahasiswa.katalog') }}" class="text-xs font-medium text-[#1E376E] hover:underline">
      Lihat semua <i class="bi bi-arrow-right"></i>
    </a>
  </div>

  <div id="rekomendasi-empty" class="{{ empty($rows) ? '' : 'hidden' }} px-5 pb-5 text-center text-sm text-slate-500">
    Belum ada buku di koleksi perpustakaan.
  </div>
  <div class="grid grid-cols-2 gap-4 p-5 md:grid-cols-3 xl:grid-cols-4">
    @foreach(($rows ?? []) as $book)
      <div class="rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
        <img src="{{ $book['img'] ?? '' }}" alt="{{ $book['title'] ?? '' }}" class="h-40 w-full rounded-lg object-cover">
        <h6 class="mt-2 truncate font-semibold text-slate-800" title="{{ $book['title'] ?? '' }}">{{ $book['title'] ?? '' }}</h6>
        <small class="text-slate-500">{{ $book['author'] ?? '' }}</small><br>
        <small class="text-slate-500">{{ $book['category'] ?? '' }}</small><br>
        <small class="text-slate-500">{{ $book['stockLabel'] ?? '' }}</small>
        <div class="mt-2 space-y-1">
          <button type="button" data-book='@json($book)' class="js-rekomendasi-detail w-full rounded-lg bg-[#1E376E] py-2 text-sm font-medium text-white hover:bg-[#162d5c] transition">
            Lihat Detail
          </button>
          <a href="{{ route('mahasiswa.katalog') }}" class="block w-full rounded-lg bg-emerald-500 py-2 text-sm font-medium text-white hover:bg-emerald-600 transition">
            Pinjam Buku
          </a>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- MODAL DETAIL BUKU -->
<div id="bookDetailModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="relative max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <button type="button" data-modal-hide="bookDetailModal" onclick="closeBookDetail()" class="absolute right-4 top-4 text-2xl leading-none text-slate-500 hover:text-slate-800" aria-label="Tutup">&times;</button>
    <h2 class="mb-4 border-b border-slate-200 pb-3 pr-10 text-xl font-bold text-slate-900">Detail Buku</h2>

    <div class="grid gap-6 md:grid-cols-2">
      <div>
        <img id="bookDetailImg" src="" alt="" class="w-full max-w-xs rounded-xl border border-slate-200 object-cover shadow-md">
      </div>
      <div class="space-y-2 text-sm text-slate-700">
        <h3 id="bookDetailTitle" class="text-lg font-bold text-slate-900"></h3>
        <p><span class="font-semibold text-slate-600">Nomor Panggil:</span> <span id="bookDetailCode"></span></p>
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
        class="rounded-lg bg-[#1E376E] px-6 py-2 text-sm font-medium text-white hover:bg-[#162d5c] transition">
        Kembali
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const modal = document.getElementById('bookDetailModal');

function escAttr(s) {
  return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}

function fillBookDetail(book) {
  document.getElementById('bookDetailImg').src = book.img;
  document.getElementById('bookDetailImg').alt = book.title;
  document.getElementById('bookDetailTitle').textContent = book.title;
  document.getElementById('bookDetailCode').textContent = book.nomor_panggil || book.code || '-';
  document.getElementById('bookDetailAuthor').textContent = book.author;
  document.getElementById('bookDetailPublisher').textContent = book.publisher;
  document.getElementById('bookDetailYear').textContent = book.year;
  document.getElementById('bookDetailCategory').textContent = book.category;
  document.getElementById('bookDetailRack').textContent = book.rack;
  document.getElementById('bookDetailIsbn').textContent = book.isbn;
  document.getElementById('bookDetailDesc').textContent = book.description;

  const stockEl = document.getElementById('bookDetailStock');
  const stockBg = book.available ? 'bg-emerald-500' : 'bg-rose-500';
  stockEl.textContent = book.stockLabel;
  stockEl.className = 'ml-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium text-white ' + stockBg;
}

function showModal(id) {
  if (typeof fbShow === 'function') {
    fbShow(id);
    return;
  }
  const el = document.getElementById(id);
  if (el) {
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.classList.add('overflow-hidden');
  }
}

function hideModal(id) {
  if (typeof fbHide === 'function') {
    fbHide(id);
    return;
  }
  const el = document.getElementById(id);
  if (el) {
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
  }
}

function openBookDetail(book) {
  if (!book) return;
  fillBookDetail(book);
  showModal('bookDetailModal');
}

function closeBookDetail() {
  hideModal('bookDetailModal');
}

window.openBookDetail = openBookDetail;
window.closeBookDetail = closeBookDetail;

document.addEventListener('click', (e) => {
  const btn = e.target.closest('.js-rekomendasi-detail');
  if (!btn) return;
  const raw = btn.getAttribute('data-book') || 'null';
  const book = JSON.parse(raw);
  if (book) openBookDetail(book);
});

modal.addEventListener('click', (e) => {
  if (e.target === modal) closeBookDetail();
});
</script>
@endpush