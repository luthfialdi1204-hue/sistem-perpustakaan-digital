@extends('layouts.mahasiswa')

@section('title', 'Riwayat Peminjaman')
@section('active_page', 'riwayat')
@section('page_title', 'Riwayat Peminjaman')
@section('page_subtitle', 'Lihat seluruh aktivitas peminjaman buku Anda.')

@section('content')

<!-- HEADER -->
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-700 to-cyan-600 p-6 text-white shadow-lg">
  <h2 class="text-xl font-semibold">Riwayat Peminjaman Buku</h2>
  <p class="mt-1 text-sm text-blue-100">Pantau status pinjaman, keterlambatan, dan detail transaksi Anda.</p>
</div>

<!-- INFO -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Total Peminjaman</p>
    <p class="mt-2 text-2xl font-bold text-slate-800">3</p>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Sedang Dipinjam</p>
    <p class="mt-2 text-2xl font-bold text-blue-700">2</p>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Sudah Dikembalikan</p>
    <p class="mt-2 text-2xl font-bold text-emerald-600">0</p>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Terlambat</p>
    <p class="mt-2 text-2xl font-bold text-rose-600">1</p>
  </div>
</div>

<!-- SEARCH -->
<div class="mb-6 flex gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
  <input type="text"
         placeholder="Cari Judul, Pengarang, atau penerbit..."
         class="flex-1 rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">

  <select class="w-56 rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none">
    <option>Semua Kategori</option>
  </select>
</div>

<!-- TABLE -->
<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">

  <!-- HEADER TABLE -->
  <div class="grid min-w-[980px] grid-cols-7 bg-slate-50 p-4 text-sm font-bold border-b border-slate-200">
    <div>Kode Transaksi</div>
    <div>Buku</div>
    <div>Tentang</div>
    <div>Tanggal Pinjam</div>
    <div>Tanggal Kembali</div>
    <div>Status</div>
    <div>Aksi</div>
  </div>

  <!-- ITEM 1 -->
  <div class="grid min-w-[980px] grid-cols-7 gap-4 border-b border-slate-100 p-4 text-sm items-start">
    <div>
      <strong>BPKSJ124</strong><br>
      <small>23/03/2026 16:03</small>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-20 rounded-lg shadow-sm">
    </div>

    <div>
      <h6 class="font-semibold text-slate-800">Tentang Kamu</h6>
      <small>Tere Liye</small><br><br>

      <strong>BPKSJ124</strong><br>
      <small>23/Maret/2026</small><br>
      <small>29/Maret/2026</small><br>

      <span class="rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-700">
        Sedang Dipinjam
      </span>
    </div>

    <div>23/Maret/2026</div>

    <div>
      30/Maret/2026<br>
      <span class="text-red-500">Telat 1 hari</span>
    </div>

    <div>
      <span class="rounded-full bg-rose-100 px-2 py-1 text-xs text-rose-700">
        Terlambat
      </span>
    </div>

    <div>
      <button onclick="openModal()"
              class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 transition">
        👁
      </button>
    </div>
  </div>

  <!-- ITEM 2 -->
  <div class="grid min-w-[980px] grid-cols-7 gap-4 border-b border-slate-100 p-4 text-sm items-start">
    <div>
      <strong>BPKSJ124</strong><br>
      <small>23/03/2026 16:03</small>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-20 rounded-lg shadow-sm">
    </div>

    <div>
      <h6 class="font-semibold text-slate-800">Tentang Kamu</h6>
      <small>Tere Liye</small>
    </div>

    <div>23/Maret/2026</div>

    <div>
      30/Maret/2026<br>
      <span class="text-green-500">1 hari lagi</span>
    </div>

    <div>
      <span class="rounded-full bg-amber-100 px-2 py-1 text-xs text-amber-800">
        Sedang Dipinjam
      </span>
    </div>

    <div>
      <button onclick="openModal()"
              class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 transition">
        👁
      </button>
    </div>
  </div>

  <!-- ITEM 3 -->
  <div class="grid min-w-[980px] grid-cols-7 gap-4 p-4 text-sm items-start">
    <div>
      <strong>BPKSJ124</strong><br>
      <small>23/03/2026 16:03</small>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-20 rounded-lg shadow-sm">
    </div>

    <div>
      <h6 class="font-semibold text-slate-800">Tentang Kamu</h6>
      <small>Tere Liye</small>
    </div>

    <div>23/Maret/2026</div>

    <div>30/Maret/2026</div>

    <div>
      <span class="rounded-full bg-slate-200 px-2 py-1 text-xs text-slate-700">
        Pending
      </span>
    </div>

    <div>
      <button onclick="openModal()"
              class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700 transition">
        👁
      </button>
    </div>
  </div>

</div>

<!-- FOOTER -->
<!-- MODAL POPUP -->
<div id="detailModal" class="fixed inset-0 hidden items-center justify-center bg-black/40 p-4 z-50">

  <div class="relative w-[850px] max-w-[95%] rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">

    <h2 class="mb-4 border-b border-slate-200 pb-2 text-lg font-semibold">Detail Riwayat</h2>

    <h3 class="mb-4 text-lg font-bold">Detail Buku</h3>

    <div class="grid grid-cols-2 gap-6 text-sm">

      <!-- LEFT -->
      <div class="space-y-3">
        <div>
          <label class="block">Kode Buku</label>
          <input type="text" value="BPKSJ123" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Judul Buku</label>
          <input type="text" value="Tentang Kamu" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Penerbit</label>
          <input type="text" value="Republika Penerbit" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Pengarang</label>
          <input type="text" value="Tere Liye" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Kategori</label>
          <input type="text" value="Fiksi" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
             class="mt-3 w-28 rounded-lg shadow-sm">
      </div>

      <!-- RIGHT -->
      <div class="space-y-3">
        <div>
          <label class="block">Tahun Terbit</label>
          <input type="text" value="24/Oktober/2016" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Tanggal Peminjaman</label>
          <input type="text" value="23/Maret/2026" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Tanggal Kembali</label>
          <input type="text" value="30/Maret/2026" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Telat</label>
          <input type="text" value="2 Hari" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>

        <div>
          <label class="block">Denda</label>
          <input type="text" value="4.000 RP" class="w-full rounded-lg border border-slate-300 px-2 py-1">
        </div>
      </div>

    </div>

    <div class="flex justify-end mt-6">
      <button onclick="closeModal()"
              class="rounded-lg bg-slate-500 px-6 py-2 text-white hover:bg-slate-600 transition">
        Kembali
      </button>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
function openModal() {
    document.getElementById("detailModal").classList.remove("hidden");
    document.getElementById("detailModal").classList.add("flex");
}

function closeModal() {
    document.getElementById("detailModal").classList.remove("flex");
    document.getElementById("detailModal").classList.add("hidden");
}
</script>
@endpush