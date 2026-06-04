@extends('layouts.admin')

@section('title', 'Kelola Buku Admin')
@section('active_page', 'kelola-buku')
@section('page_title', 'Kelola Buku')

@section('content')
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-indigo-900/10 to-teal-500/10 px-5 py-3.5">
    <i class="bi bi-funnel text-indigo-900"></i>
    <h2 class="font-semibold text-indigo-900">Filter Buku</h2>
  </div>
  <div class="p-5">
    <p class="mb-4 text-sm text-slate-500">Cari berdasarkan nomor panggil, judul, pengarang, penerbit, atau kategori.</p>
    <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
    <div class="relative md:col-span-7">
      <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
    <input id="searchInput" type="text" placeholder="Cari nomor panggil, judul, pengarang..."
      class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-900/20">
    </div>
    <div class="relative md:col-span-3">
      <i class="bi bi-tags absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
    <select id="categoryFilter"
      class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-900/20">
      <option value="all">Semua Kategori</option>
      @include('partials.buku-kategori-options')
    </select>
    </div>
    <button type="button" onclick="openTambahModal()"
      class="md:col-span-2 inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-950">
      <i class="bi bi-plus-lg"></i> Tambah Buku
    </button>
    </div>
  </div>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center justify-between gap-2 border-b border-slate-200 bg-gradient-to-r from-indigo-900/10 to-teal-500/10 px-5 py-3.5">
    <div class="flex items-center gap-2">
      <i class="bi bi-grid-3x3-gap text-indigo-900"></i>
      <h3 class="font-semibold text-indigo-900">Koleksi Buku</h3>
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
          <p class="text-slate-500">Nomor Panggil</p>
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
        <label class="mb-1 block text-xs font-semibold text-slate-600">Nomor Panggil<span class="text-rose-500">*</span></label>
        <input id="editCode" name="code" type="text" required maxlength="50" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Contoh: BK0001">
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
          @include('partials.buku-kategori-options')
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
        <label class="mb-1 block text-xs font-semibold text-slate-600">Nomor Panggil<span class="text-rose-500">*</span></label>
        <input id="tambahCode" name="code" type="text" required maxlength="50" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Contoh: BK0001">
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
          @include('partials.buku-kategori-options')
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
window.KelolaBukuCfg = {
  csrf: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
  listUrl: @json(route('admin.buku.list')),
  showUrl: (id) => @json(url('/admin/buku')) + '/' + encodeURIComponent(id),
  storeUrl: @json(route('admin.buku.store')),
  defaultCover: @json(asset('images/' . rawurlencode('Cover buku 1.jpg'))),
  perPage: 6,
};
</script>
<script src="{{ asset('js/admin/kelola-buku.js') }}?v={{ filemtime(public_path('js/admin/kelola-buku.js')) }}"></script>
@endpush
