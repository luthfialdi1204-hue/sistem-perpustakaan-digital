@extends('layouts.admin')

@section('title', 'Kelola Buku Admin')
@section('active_page', 'kelola-buku')
@section('page_title', 'Kelola Buku')

@section('content')
<!-- STATS -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Total Buku</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['total_buku'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Total Fisik Buku</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['total_stok'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Stok Habis</p>
    <p class="text-xl font-bold text-rose-600">{{ $stats['buku_stok_habis'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Jumlah Kategori</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['jumlah_kategori'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Status Pustaka</p>
    <p class="text-sm font-bold text-emerald-600 mt-1">Aktif</p>
  </div>
</div>

<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <i class="bi bi-funnel text-[#1E376E]"></i>
    <h2 class="font-semibold text-[#1E376E]">Filter Buku</h2>
  </div>
  <div class="p-5">
    <form method="GET" action="{{ route('admin.buku.index') }}" class="grid grid-cols-1 gap-3 md:grid-cols-12">
      <div class="relative md:col-span-7">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input name="q" value="{{ request('q') }}" type="text" placeholder="Cari nomor panggil, judul, pengarang..."
          class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
      </div>
      <div class="relative md:col-span-3">
        <i class="bi bi-tags absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <select name="category" onchange="this.form.submit()"
          class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
          <option value="all">Semua Kategori</option>
          @foreach(\App\Models\Buku::kategoriList() as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="hidden"></button>
      <button type="button" onclick="document.getElementById('tambahModal').classList.remove('hidden')"
        class="md:col-span-2 inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E376E] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162d5c]">
        <i class="bi bi-plus-lg"></i> Tambah Buku
      </button>
    </form>
  </div>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center justify-between gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <div class="flex items-center gap-2">
      <i class="bi bi-grid-3x3-gap text-[#1E376E]"></i>
      <h3 class="font-semibold text-[#1E376E]">Koleksi Buku</h3>
    </div>
    <span class="text-xs font-medium text-slate-500">{{ $books->total() }} buku</span>
  </div>
  <div class="p-5">
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
      @forelse($books as $book)
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
          <img src="{{ $book->cover_url }}" alt="{{ $book->judul_buku }}" onerror="this.onerror=null;this.src='{{ asset('images/Cover buku 1.jpg') }}';" class="h-44 w-full object-cover">
          <div class="p-3">
            <p class="text-[11px] font-semibold text-teal-700">{{ $book->nomor_panggil }}</p>
            <h6 class="line-clamp-2 font-bold text-[#1E376E]" title="{{ $book->judul_buku }}">{{ $book->judul_buku }}</h6>
            <p class="mt-1 text-sm text-slate-500">{{ $book->pengarang }}</p>
            <p class="text-sm text-slate-500">{{ $book->kategori_buku }}</p>
            <p class="text-sm text-slate-500">Tersedia : {{ $book->stok_buku }}</p>
            
            <div class="mt-3 flex gap-1.5 justify-center">
              <button type="button" onclick="document.getElementById('detailModal-{{ $book->kode_buku }}').classList.remove('hidden')" class="inline-flex flex-1 items-center justify-center gap-1 rounded-xl bg-slate-100 py-2 text-xs font-semibold text-[#1E376E] transition hover:bg-slate-200">
                <i class="bi bi-eye"></i> Detail
              </button>
              <button type="button" onclick="document.getElementById('editModal-{{ $book->kode_buku }}').classList.remove('hidden')" class="inline-flex flex-1 items-center justify-center gap-1 rounded-xl bg-[#1E376E] py-2 text-xs font-semibold text-white transition hover:bg-[#162d5c]">
                <i class="bi bi-pencil"></i> Edit
              </button>
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-full rounded-xl border border-dashed border-slate-200 bg-slate-50 py-12 text-center text-sm text-slate-500">
          <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
          Tidak ada buku yang ditemukan.
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
      {{ $books->links() }}
    </div>
  </div>
</div>

<!-- ==================== MODALS LOOP ==================== -->
@foreach($books as $book)
  <!-- DETAIL MODAL -->
  <div id="detailModal-{{ $book->kode_buku }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
    <div class="w-full max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
      <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
        <h3 class="text-lg font-semibold text-[#1E376E]">Detail Buku</h3>
        <button onclick="document.getElementById('detailModal-{{ $book->kode_buku }}').classList.add('hidden')" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
      </div>

      <div class="grid gap-6 md:grid-cols-3">
        <div>
          <img src="{{ $book->cover_url }}" alt="Cover Buku" onerror="this.onerror=null;this.src='{{ asset('images/Cover buku 1.jpg') }}';" class="mx-auto w-40 rounded-lg border border-slate-200 shadow-sm">
        </div>
        <div class="md:col-span-2 space-y-2 text-sm">
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Judul Buku</p>
            <p class="col-span-2 font-medium text-slate-800">{{ $book->judul_buku }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Nomor Panggil</p>
            <p class="col-span-2 font-medium text-slate-800">{{ $book->nomor_panggil }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Pengarang</p>
            <p class="col-span-2 font-medium text-slate-800">{{ $book->pengarang }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Penerbit</p>
            <p class="col-span-2 font-medium text-slate-800">{{ $book->penerbit }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Tahun Terbit</p>
            <p class="col-span-2 font-medium text-slate-800">{{ $book->tahun_terbit }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Kategori</p>
            <p class="col-span-2"><span class="rounded bg-blue-100 px-2 py-1 text-xs text-blue-700 font-semibold">{{ $book->kategori_buku }}</span></p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Stok</p>
            <p class="col-span-2"><span class="rounded bg-emerald-100 px-2 py-1 text-xs text-emerald-700 font-semibold">{{ $book->stok_buku }} tersedia</span></p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Lokasi Rak</p>
            <p class="col-span-2 font-medium text-slate-800">{{ $book->lokasi_rak }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">ISBN</p>
            <p class="col-span-2 font-medium text-slate-800">{{ $book->isbn }}</p>
          </div>
          <div class="grid grid-cols-3 gap-2">
            <p class="text-slate-500">Deskripsi</p>
            <p class="col-span-2 text-slate-700 leading-relaxed">{{ $book->parsed_description }}</p>
          </div>
        </div>
      </div>

      <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
        <button type="button" onclick="document.getElementById('hapusBukuModal-{{ $book->kode_buku }}').classList.remove('hidden')" class="rounded-lg bg-rose-500 px-4 py-2 text-sm text-white hover:bg-rose-600 font-semibold">
          <i class="bi bi-trash"></i> Hapus
        </button>
        <button type="button" onclick="document.getElementById('detailModal-{{ $book->kode_buku }}').classList.add('hidden'); document.getElementById('editModal-{{ $book->kode_buku }}').classList.remove('hidden')" class="rounded-lg bg-emerald-500 px-4 py-2 text-sm text-white hover:bg-emerald-600 font-semibold">Edit</button>
      </div>
    </div>
  </div>

  <!-- EDIT MODAL -->
  <div id="editModal-{{ $book->kode_buku }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
    <form method="POST" action="{{ route('admin.buku.update', $book->kode_buku) }}" enctype="multipart/form-data" class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
      @csrf
      @method('PUT')
      <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
        <h3 class="text-lg font-semibold text-[#1E376E]">Edit Buku</h3>
        <button type="button" onclick="document.getElementById('editModal-{{ $book->kode_buku }}').classList.add('hidden')" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
      </div>

      <h4 class="mb-3 text-sm font-semibold text-slate-700">Informasi Buku</h4>

      <div class="grid gap-x-6 gap-y-3 md:grid-cols-2 text-left">
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Nomor Panggil<span class="text-rose-500">*</span></label>
          <input name="code" value="{{ old('code', $book->nomor_panggil) }}" type="text" required maxlength="50" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">ISBN<span class="text-rose-500">*</span></label>
          <input name="isbn" value="{{ old('isbn', $book->isbn) }}" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku<span class="text-rose-500">*</span></label>
          <input name="title" value="{{ old('title', $book->judul_buku) }}" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Penerbit<span class="text-rose-500">*</span></label>
          <input name="publisher" value="{{ old('publisher', $book->penerbit) }}" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang<span class="text-rose-500">*</span></label>
          <input name="author" value="{{ old('author', $book->pengarang) }}" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Lokasi Rak</label>
          <input name="rack" value="{{ old('rack', $book->lokasi_rak) }}" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit</label>
          <input name="year" value="{{ old('year', $book->tahun_terbit) }}" type="number" min="1" max="9999" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
          <select name="category" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
            @foreach(\App\Models\Buku::kategoriList() as $cat)
              <option value="{{ $cat }}" {{ old('category', $book->kategori_buku) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Jumlah Buku<span class="text-rose-500">*</span></label>
          <input name="stock" value="{{ old('stock', $book->stok_buku) }}" type="number" min="0" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
        </div>

        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Cover Buku (Kosongkan jika tidak diganti)</label>
          <input name="cover" type="file" accept="image/png, image/jpeg, image/jpg" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>
        <div class="md:col-span-2">
          <label class="mb-1 block text-xs font-semibold text-slate-600">Deskripsi</label>
          <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">{{ old('description', $book->parsed_description) }}</textarea>
        </div>
      </div>

      <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
        <button type="button" onclick="document.getElementById('editModal-{{ $book->kode_buku }}').classList.add('hidden')" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Kembali</button>
        <button type="submit" class="rounded-lg bg-[#1E376E] px-4 py-2 text-sm font-medium text-white hover:bg-[#162d5c]">Simpan</button>
      </div>
    </form>
  </div>

  <!-- HAPUS MODAL -->
  <div id="hapusBukuModal-{{ $book->kode_buku }}" class="hidden fixed inset-0 z-[60] overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
    <form method="POST" action="{{ route('admin.buku.destroy', $book->kode_buku) }}" class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl text-center">
      @csrf
      @method('DELETE')
      <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
        <i class="bi bi-exclamation-triangle text-2xl"></i>
      </div>
      <h3 class="text-base font-semibold text-slate-800">Hapus Buku?</h3>
      <p class="mt-1 text-xs text-slate-500">Buku <span class="font-semibold text-slate-700">"{{ $book->judul_buku }}"</span> akan dihapus secara permanen.</p>

      <div class="mt-5 flex justify-center gap-2">
        <button type="button" onclick="document.getElementById('hapusBukuModal-{{ $book->kode_buku }}').classList.add('hidden')"
          class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
          Batal
        </button>
        <button type="submit"
          class="rounded-lg bg-rose-500 px-4 py-2 text-xs font-medium text-white hover:bg-rose-600">
          Hapus
        </button>
      </div>
    </form>
  </div>
@endforeach

<!-- TAMBAH MODAL -->
<div id="tambahModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
  <form method="POST" action="{{ route('admin.buku.store') }}" enctype="multipart/form-data" class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    @csrf
    <div class="mb-4 flex items-center justify-between border-b border-slate-200 pb-3">
      <h3 class="text-lg font-semibold text-[#1E376E]">Tambah Buku</h3>
      <button type="button" onclick="document.getElementById('tambahModal').classList.add('hidden')" class="text-xl text-slate-500 hover:text-slate-700">✖</button>
    </div>

    <h4 class="mb-3 text-sm font-semibold text-slate-700">Informasi Buku</h4>

    <div class="grid gap-x-6 gap-y-3 md:grid-cols-2 text-left">
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Nomor Panggil<span class="text-rose-500">*</span></label>
        <input name="code" value="{{ old('code') }}" type="text" required maxlength="50" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">ISBN</label>
        <input name="isbn" value="{{ old('isbn') }}" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku<span class="text-rose-500">*</span></label>
        <input name="title" value="{{ old('title') }}" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Penerbit<span class="text-rose-500">*</span></label>
        <input name="publisher" value="{{ old('publisher') }}" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang<span class="text-rose-500">*</span></label>
        <input name="author" value="{{ old('author') }}" type="text" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Lokasi Rak</label>
        <input name="rack" value="{{ old('rack') }}" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit<span class="text-rose-500">*</span></label>
        <input name="year" value="{{ old('year', date('Y')) }}" type="number" min="1" max="9999" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori<span class="text-rose-500">*</span></label>
        <select name="category" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
          @foreach(\App\Models\Buku::kategoriList() as $cat)
            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Jumlah Buku<span class="text-rose-500">*</span></label>
        <input name="stock" value="{{ old('stock', 0) }}" type="number" min="0" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">
      </div>
      <div>
        <label class="mb-1 block text-xs font-semibold text-slate-600">Upload Cover Buku<span class="text-rose-500">*</span></label>
        <input name="cover" type="file" required accept="image/png, image/jpeg, image/jpg" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Deskripsi</label>
        <textarea name="description" rows="3" placeholder="Ringkasan atau sinopsis buku..." required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:border-[#1E376E]">{{ old('description') }}</textarea>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-4">
      <button type="button" onclick="document.getElementById('tambahModal').classList.add('hidden')" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Kembali</button>
      <button type="submit" class="rounded-lg bg-[#1E376E] px-4 py-2 text-sm font-medium text-white hover:bg-[#162d5c]">Simpan</button>
    </div>
  </form>
</div>
@endsection
