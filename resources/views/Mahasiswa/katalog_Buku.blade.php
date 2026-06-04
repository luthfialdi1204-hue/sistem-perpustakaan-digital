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
    <p class="mb-4 text-sm text-slate-500">Cari berdasarkan judul, pengarang, atau kategori.</p>
    <form method="GET" action="{{ route('mahasiswa.katalog') }}" class="grid gap-3 md:grid-cols-2">
      <div class="relative">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
          class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20"
          placeholder="Cari judul, pengarang, atau kategori...">
      </div>
      <div class="relative">
        <i class="bi bi-tags absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <select name="category"
          class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
          <option value="all" @selected(($filters['category'] ?? 'all') === 'all')>Semua Kategori</option>
          @foreach(($categories ?? []) as $cat)
            <option value="{{ $cat }}" @selected(($filters['category'] ?? 'all') === $cat)>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-2 flex gap-2">
        <a href="{{ route('mahasiswa.katalog') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
          Reset
        </a>
        <button type="submit" class="rounded-xl bg-[#1E376E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#162d5c]">
          <i class="bi bi-funnel-fill text-xs"></i> Tampilkan
        </button>
      </div>
    </form>
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
    <div id="book-empty" class="{{ empty($rows) ? '' : 'hidden' }} rounded-xl border border-dashed border-slate-200 bg-slate-50 py-12 text-center text-sm text-slate-500">
      <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
      Tidak ada buku yang cocok dengan pencarian Anda.
    </div>
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
      @foreach(($rows ?? []) as $book)
        @php
          $available = ($book['status'] ?? '') === 'available';
        @endphp
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
          <img src="{{ $book['img'] ?? '' }}" alt="{{ $book['title'] ?? '' }}" class="h-44 w-full object-cover">
          <div class="p-3">
            <h6 class="mt-2 line-clamp-2 font-bold text-[#1E376E]" title="{{ $book['title'] ?? '' }}">{{ $book['title'] ?? '' }}</h6>
            <p class="mt-1 text-sm text-slate-500">{{ $book['author'] ?? '' }}</p>
            <p class="text-sm text-slate-500">{{ $book['category'] ?? '' }}</p>
            <p class="text-sm text-slate-500">{{ $book['stockLabel'] ?? '' }}</p>
            <div class="mt-3 space-y-2">
              <button type="button"
                class="js-detail w-full rounded-lg bg-[#1E376E] py-2 text-sm font-semibold text-white transition hover:bg-[#162d5c]"
                data-book='@json($book)'>
                Lihat Detail
              </button>
              @if($available)
                <form method="POST" action="{{ route('mahasiswa.peminjaman.store') }}">
                  @csrf
                  <input type="hidden" name="kode_buku" value="{{ $book['id'] ?? '' }}">
                  <button type="submit" class="w-full rounded-lg bg-emerald-500 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
                    Pinjam Buku
                  </button>
                </form>
              @else
                <button type="button" disabled class="w-full cursor-not-allowed rounded-lg bg-amber-200 py-2 text-sm font-semibold text-amber-900">
                  Sedang Dipinjam
                </button>
              @endif
            </div>
          </div>
        </article>
      @endforeach
    </div>

    @php
      $current = (int) ($meta['current_page'] ?? 1);
      $last = (int) ($meta['last_page'] ?? 1);
    @endphp
    @if($last > 1)
      <div class="mt-6 flex justify-center">
        <div class="inline-flex overflow-hidden rounded-lg border border-slate-200">
          @for($p = 1; $p <= $last; $p++)
            <a href="{{ route('mahasiswa.katalog', array_merge(request()->query(), ['page' => $p])) }}"
              class="border-r border-slate-200 px-4 py-1.5 text-xs {{ $p === $current ? 'bg-[#1E376E] text-white' : 'bg-white text-slate-700 hover:bg-slate-50' }}">
              {{ $p }}
            </a>
          @endfor
        </div>
      </div>
    @endif
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

<!-- MODAL PINJAM: removed (server-side form submit) -->
@endsection

@push('scripts')
<script>
const detailModal = document.getElementById('detailModal');
function openModal(book) {
  if (!book) return;

  document.getElementById('modalImg').src = book.img;
  document.getElementById('modalImg').alt = book.title;
  document.getElementById('modalTitle').textContent = book.title;
  document.getElementById('modalCode').textContent = book.nomor_panggil || book.code || '-';
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

window.closeModal = closeModal;

document.addEventListener('click', (e) => {
  const detailBtn = e.target.closest('.js-detail');
  if (!detailBtn) return;
  const raw = detailBtn.getAttribute('data-book') || 'null';
  const book = JSON.parse(raw);
  if (book) openModal(book);
});

detailModal.addEventListener('click', (e) => {
  if (e.target === detailModal) closeModal();
});
</script>
@endpush
