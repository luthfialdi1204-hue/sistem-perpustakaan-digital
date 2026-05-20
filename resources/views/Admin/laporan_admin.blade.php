@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')
@section('active_page', 'laporan')
@section('page_title', 'Laporan Peminjaman')
@section('page_subtitle', 'Kelola dan analisis data peminjaman buku secara mudah dan interaktif.')

@section('content')
<div class="space-y-6">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
    <div class="relative w-full sm:w-auto">
      <button type="button" id="exportAllDataBtn" data-dropdown-toggle="exportAllDropdown" data-dropdown-placement="bottom-end"
        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#1E376E] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162d5c] sm:w-auto">
        <i class="bi bi-download"></i> Unduh Laporan
        <i class="bi bi-chevron-down text-xs opacity-80"></i>
      </button>
      <div id="exportAllDropdown"
        class="z-20 hidden w-52 divide-y divide-gray-100 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
        <button type="button" id="exportAllPdfBtn"
          class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50">
          <i class="bi bi-file-pdf text-rose-500"></i> Export PDF
        </button>
        <button type="button" id="exportAllExcelBtn"
          class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50">
          <i class="bi bi-file-earmark-spreadsheet text-emerald-600"></i> Export Excel (CSV)
        </button>
      </div>
    </div>
  </div>

  {{-- Kartu statistik --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#1E376E] ring-4 ring-[#1E376E]/15">
        <i class="bi bi-journal-bookmark text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Total Peminjaman</p>
        <p id="statTotal" class="text-2xl font-bold text-slate-800">0</p>
        <p class="mt-0.5 text-xs text-emerald-600 flex items-center gap-0.5">
          <i class="bi bi-arrow-up-short text-base"></i> <span id="statTotalTrend">—</span>
        </p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-teal-600 ring-4 ring-teal-600/15">
        <i class="bi bi-check2-square text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Selesai</p>
        <p id="statSelesai" class="text-2xl font-bold text-slate-800">0</p>
        <p id="statSelesaiPct" class="mt-0.5 text-xs text-slate-500">0% dari total</p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-500 ring-4 ring-amber-500/15">
        <i class="bi bi-clock-history text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Terlambat</p>
        <p id="statTerlambat" class="text-2xl font-bold text-slate-800">0</p>
        <p id="statTerlambatPct" class="mt-0.5 text-xs text-slate-500">0% dari total</p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-rose-500 ring-4 ring-rose-500/15">
        <i class="bi bi-cash-coin text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Total Denda</p>
        <p id="statDenda" class="text-2xl font-bold text-slate-800">Rp0</p>
        <p class="mt-0.5 text-xs text-rose-500 flex items-center gap-0.5">
          <i class="bi bi-exclamation-circle"></i> <span id="statDendaNote">—</span>
        </p>
      </div>
    </div>
  </div>

  {{-- Filter --}}
  <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center gap-2">
      <i class="bi bi-funnel text-[#1E376E]"></i>
      <h3 class="font-semibold text-[#1E376E]">Filter Laporan</h3>
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-12 md:items-end">
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Mulai</label>
        <div class="relative">
          <i class="bi bi-calendar3 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input id="dateStart" type="date"
            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Selesai</label>
        <div class="relative">
          <i class="bi bi-calendar3 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input id="dateEnd" type="date"
            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
        <select id="reportStatus"
          class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
          <option value="all">Semua Status</option>
          <option value="Mengajukan">Mengajukan</option>
          <option value="Sedang Dipinjam">Sedang Dipinjam</option>
          <option value="Terlambat">Terlambat</option>
          <option value="Dikembalikan">Dikembalikan</option>
          <option value="Sudah Lunas">Sudah Lunas</option>
          <option value="Ditolak">Ditolak</option>
          <option value="Dibatalkan">Dibatalkan</option>
        </select>
      </div>
      <div class="md:col-span-4">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pencarian</label>
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
          <input id="reportSearch" type="search" placeholder="Cari buku atau nama anggota..."
            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="flex gap-2 md:col-span-2">
        <button type="button" id="btnResetFilter"
          class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
          Reset
        </button>
        <button type="button" id="btnApplyFilter"
          class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#1E376E] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#162d5c] transition">
          <i class="bi bi-funnel-fill text-xs"></i> Tampilkan
        </button>
      </div>
    </div>
  </div>

  {{-- Konten utama: tabel + sidebar --}}
  <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    <div class="xl:col-span-2">
      <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
        <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 via-[#1E376E]/5 to-teal-500/10 px-5 py-4">
          <i class="bi bi-journal-text text-[#1E376E] text-lg"></i>
          <h3 class="font-semibold text-[#1E376E]">Riwayat Peminjaman</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full min-w-[720px] text-sm">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-3 py-3 w-10">No</th>
                <th class="px-3 py-3">Anggota</th>
                <th class="px-3 py-3">Buku</th>
                <th class="px-3 py-3 whitespace-nowrap">Tgl Pinjam</th>
                <th class="px-3 py-3 whitespace-nowrap">Tgl Kembali</th>
                <th class="px-3 py-3 text-center">Status</th>
                <th class="px-3 py-3">Denda</th>
                <th class="px-3 py-3 text-center w-12">Aksi</th>
              </tr>
            </thead>
            <tbody id="reportTableBody" class="divide-y divide-slate-100"></tbody>
          </table>
        </div>
        <p id="reportEmpty" class="hidden px-5 py-10 text-center text-sm text-slate-500">
          <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
          Tidak ada data yang cocok dengan filter.
        </p>
        <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
          <p id="paginationInfo" class="text-xs text-slate-500">Menampilkan 0 dari 0 data</p>
          <div id="paginationContainer" class="flex flex-wrap items-center justify-center gap-1 text-sm"></div>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <h3 class="mb-4 font-semibold text-[#1E376E]">Ringkasan Peminjaman</h3>
        <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
          <div class="relative h-40 w-40 shrink-0">
            <canvas id="summaryChart"></canvas>
            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
              <span id="chartCenterTotal" class="text-2xl font-bold text-slate-800">0</span>
              <span class="text-xs text-slate-500">Total</span>
            </div>
          </div>
          <ul id="chartLegend" class="flex-1 space-y-2 text-sm"></ul>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <h3 class="mb-4 flex items-center gap-2 font-semibold text-[#1E376E]">
          <i class="bi bi-cash-stack"></i> Denda Tertinggi
        </h3>
        <ul id="topFinesList" class="space-y-3"></ul>
      </div>
    </div>
  </div>
</div>

<div id="reportDetailModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-6 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-[#1E376E]">Detail Peminjaman</h3>
      <button type="button" id="reportDetailClose" data-modal-hide="reportDetailModal" class="text-2xl leading-none text-slate-500 hover:text-slate-700" aria-label="Tutup">&times;</button>
    </div>
    <div class="grid gap-6 lg:grid-cols-2">
      <div class="space-y-3">
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Nama Peminjam</label>
        <input id="detailMember" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku</label>
        <input id="detailBookCode" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku</label>
        <input id="detailBookTitle" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang</label>
        <input id="detailAuthor" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
        <input id="detailCategory" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <img id="detailCover" src="" alt="" class="h-40 w-28 rounded-lg border border-slate-200 object-cover shadow-sm">
      </div>
      <div class="space-y-3">
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
        <input id="detailStatus" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Peminjaman</label>
        <input id="detailBorrowDisplay" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Kembali</label>
        <input id="detailDueDisplay" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Keterlambatan</label>
        <input id="detailTelat" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Denda</label>
        <input id="detailDenda" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
      </div>
    </div>
    <div class="mt-6 flex justify-end gap-2 border-t border-slate-200 pt-4">
      <button type="button" id="detailBtnBack" data-modal-hide="reportDetailModal" class="rounded-lg border border-slate-200 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Kembali</button>
      <div class="relative">
        <button type="button" id="exportDataBtn" data-dropdown-toggle="exportDropdown" data-dropdown-placement="bottom-end" class="inline-flex items-center gap-2 rounded-lg bg-[#1E376E] px-5 py-2 text-sm font-semibold text-white hover:bg-[#162d5c]">
          Export <i class="bi bi-chevron-down text-xs"></i>
        </button>
        <div id="exportDropdown" class="z-10 hidden w-44 divide-y divide-gray-100 rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
          <button type="button" id="exportPdfBtn" class="block w-full px-4 py-2 text-left text-sm hover:bg-slate-50">PDF</button>
          <button type="button" id="exportExcelBtn" class="block w-full px-4 py-2 text-left text-sm hover:bg-slate-50">Excel</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const MONTHS_ID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const ROWS_PER_PAGE = 5;
let currentPage = 1;
let filteredRows = [];
let summaryChartInstance = null;

function formatDisplayDate(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-').map(Number);
  if (!y || !m || !d) return '';
  return `${String(d).padStart(2, '0')}/${MONTHS_ID[m - 1]}/${y}`;
}

function parseDenda(val) {
  if (!val || val === '—') return 0;
  return parseInt(String(val).replace(/[^\d]/g, ''), 10) || 0;
}

function formatRp(n) {
  return 'Rp' + n.toLocaleString('id-ID');
}

const reportRows = [
  { id: 'r1', member: 'Luthfi Dwi Apriyaldi', bookCode: 'BPKSJ123', bookTitle: 'Tentang Kamu', bookAuthor: 'Tere Liye', category: 'Fiksi', borrowIso: '2026-03-23', dueIso: '2026-03-30', telat: '2 hari', denda: 'Rp4.000', telatNote: 'Telat 2 hari', status: 'Terlambat', cover: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg' },
  { id: 'r2', member: 'Muhammad Zaky Sadewa', bookCode: 'BPKSJ124', bookTitle: 'Atomic Habits', bookAuthor: 'James Clear', category: 'Pendidikan', borrowIso: '2026-03-21', dueIso: '2026-03-28', telat: '', denda: 'Rp0', status: 'Sudah Lunas', cover: 'https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg' },
  { id: 'r3', member: 'Nadia Rahma', bookCode: 'BPKSJ126', bookTitle: 'Filosofi Teras', bookAuthor: 'Henry Manampiring', category: 'Pendidikan', borrowIso: '2026-03-18', dueIso: '2026-03-25', telat: '3 hari', denda: 'Rp10.000', telatNote: 'Telat 3 hari', status: 'Terlambat', cover: 'https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg' },
  { id: 'r4', member: 'Putri Ayu', bookCode: 'BPKSJ127', bookTitle: 'Bumi', bookAuthor: 'Tere Liye', category: 'Fiksi', borrowIso: '2026-03-10', dueIso: '2026-03-17', telat: '', denda: 'Rp8.000', status: 'Dikembalikan', cover: 'https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg' },
  { id: 'r5', member: 'Salsa Putri', bookCode: 'BPKSJ128', bookTitle: 'Laskar Pelangi', bookAuthor: 'Andrea Hirata', category: 'Fiksi', borrowIso: '2026-03-15', dueIso: '2026-03-22', telat: '', denda: 'Rp6.000', status: 'Terlambat', cover: 'https://images-na.ssl-images-amazon.com/images/I/81af+MCATTL.jpg' },
  { id: 'r6', member: 'Cristh Velato Arioranga', bookCode: 'BPKSJ125', bookTitle: 'Filosofi Teras', bookAuthor: 'Henry Manampiring', category: 'Pendidikan', borrowIso: '2026-03-25', dueIso: '2026-04-01', telat: '', denda: 'Rp0', dueNote: '3 hari lagi', status: 'Sedang Dipinjam', cover: 'https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg' },
  { id: 'r7', member: 'Dewi Putri', bookCode: 'BPKSJ129', bookTitle: 'Rich Dad Poor Dad', bookAuthor: 'Robert Kiyosaki', category: 'Bisnis', borrowIso: '2026-03-28', dueIso: '2026-04-04', telat: '', denda: '—', status: 'Mengajukan', cover: 'https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg' },
  { id: 'r8', member: 'Rizky Fadillah', bookCode: 'BPKSJ130', bookTitle: 'Negeri 5 Menara', bookAuthor: 'Ahmad Fuadi', category: 'Pendidikan', borrowIso: '2026-03-20', dueIso: '2026-03-27', telat: '', denda: 'Rp0', status: 'Sudah Lunas', cover: 'https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg' },
  { id: 'r9', member: 'Luthfi Dwi Apriyaldi', bookCode: 'BPKSJ128', bookTitle: 'Laskar Pelangi', bookAuthor: 'Andrea Hirata', category: 'Fiksi', borrowIso: '2026-03-15', dueIso: '2026-03-22', telat: '', denda: '—', status: 'Dibatalkan', cover: 'https://images-na.ssl-images-amazon.com/images/I/81af+MCATTL.jpg' },
];

let currentDetailRow = null;

function escHtml(s) {
  return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}

function statusBadgeHtml(status) {
  const badges = {
    Mengajukan: '<span class="inline-flex items-center gap-1 rounded-md bg-violet-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-send-fill"></i> Mengajukan</span>',
    'Sedang Dipinjam': '<span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-book-fill"></i> Sedang Dipinjam</span>',
    Terlambat: '<span class="inline-flex items-center gap-1 rounded-md bg-red-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-exclamation-circle-fill"></i> Terlambat</span>',
    Dikembalikan: '<span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-box-arrow-in-left"></i> Dikembalikan</span>',
    'Sudah Lunas': '<span class="inline-flex items-center gap-1 rounded-md bg-teal-600 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-cash-coin"></i> Sudah Lunas</span>',
    Ditolak: '<span class="inline-flex items-center gap-1 rounded-md bg-rose-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-x-circle-fill"></i> Ditolak</span>',
    Dibatalkan: '<span class="inline-flex items-center gap-1 rounded-md bg-slate-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-slash-circle"></i> Dibatalkan</span>',
  };
  return badges[status] || `<span class="inline-flex rounded-md bg-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-700">${escHtml(status)}</span>`;
}

function dendaCell(row) {
  if (row.status === 'Mengajukan' || row.status === 'Ditolak' || row.status === 'Dibatalkan') {
    return '<span class="text-xs text-slate-400">—</span>';
  }
  if (row.status === 'Sudah Lunas') {
    return '<span class="font-semibold text-teal-600 text-xs">Rp0 <span class="text-[11px] font-normal text-slate-400">(lunas)</span></span>';
  }
  const hasFine = row.denda && row.denda !== 'Rp0' && row.denda !== '—';
  const cls = (row.status === 'Terlambat' || (row.status === 'Dikembalikan' && hasFine))
    ? 'font-semibold text-red-600 text-xs'
    : 'font-semibold text-emerald-600 text-xs';
  const extra = row.status === 'Terlambat' && row.telat ? `<span class="text-[11px] text-red-500 ml-1">(${escHtml(row.telat)})</span>` : '';
  return `<span class="${cls}">${escHtml(row.denda || 'Rp0')}</span>${extra}`;
}

function dueDateCell(row) {
  const du = formatDisplayDate(row.dueIso);
  let note = '';
  if (row.telatNote) note = `<p class="text-[11px] text-red-500">${escHtml(row.telatNote)}</p>`;
  else if (row.dueNote) note = `<p class="text-[11px] text-emerald-600">${escHtml(row.dueNote)}</p>`;
  return `<p class="text-xs text-slate-700 whitespace-nowrap">${escHtml(du)}</p>${note}`;
}

function memberInitials(name) {
  const p = name.trim().split(/\s+/);
  return p.length >= 2 ? (p[0][0] + p[1][0]).toUpperCase() : name.slice(0, 2).toUpperCase();
}

function rowHtml(row, index) {
  const br = formatDisplayDate(row.borrowIso);
  return `
    <tr class="hover:bg-slate-50/80 transition-colors">
      <td class="px-3 py-2.5 text-slate-500 text-xs">${index}</td>
      <td class="px-3 py-2.5">
        <div class="flex items-center gap-2">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#1E376E]/10 text-[10px] font-bold text-[#1E376E]">${memberInitials(row.member)}</div>
          <span class="font-medium text-slate-800 text-xs">${escHtml(row.member)}</span>
        </div>
      </td>
      <td class="px-3 py-2.5">
        <p class="font-medium text-slate-800 text-xs leading-tight">${escHtml(row.bookTitle)}</p>
        <p class="text-[11px] text-slate-500">${escHtml(row.bookAuthor)}</p>
      </td>
      <td class="px-3 py-2.5 text-xs text-slate-700 whitespace-nowrap">${escHtml(br)}</td>
      <td class="px-3 py-2.5">${dueDateCell(row)}</td>
      <td class="px-3 py-2.5 text-center">${statusBadgeHtml(row.status)}</td>
      <td class="px-3 py-2.5">${dendaCell(row)}</td>
      <td class="px-3 py-2.5 text-center">
        <button type="button" data-action="detail" data-report-id="${row.id}" class="report-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-[#1E376E]">
          <i class="bi bi-three-dots-vertical"></i>
        </button>
      </td>
    </tr>`;
}

function getFilteredRows() {
  const kw = (document.getElementById('reportSearch').value || '').toLowerCase().trim();
  const st = document.getElementById('reportStatus').value;
  const start = document.getElementById('dateStart').value;
  const end = document.getElementById('dateEnd').value;
  return reportRows.filter((row) => {
    const hay = (row.bookTitle + ' ' + row.bookAuthor + ' ' + row.member).toLowerCase();
    if (kw && !hay.includes(kw)) return false;
    if (st !== 'all' && row.status !== st) return false;
    if (start && row.borrowIso < start) return false;
    if (end && row.borrowIso > end) return false;
    return true;
  });
}

function countByStatus(status) {
  return reportRows.filter((r) => r.status === status).length;
}

function updateStats() {
  const total = reportRows.length;
  const selesai = countByStatus('Sudah Lunas') + countByStatus('Dikembalikan');
  const terlambat = countByStatus('Terlambat');
  const mengajukan = countByStatus('Mengajukan');
  const sedangDipinjam = countByStatus('Sedang Dipinjam');
  const ditolak = countByStatus('Ditolak');
  const dibatalkan = countByStatus('Dibatalkan');
  const totalDenda = reportRows.reduce((s, r) => s + parseDenda(r.denda), 0);
  const pct = (n) => (total ? ((n / total) * 100).toFixed(1) : '0');
  const set = (id, v) => { const e = document.getElementById(id); if (e) e.textContent = v; };
  set('statTotal', total);
  set('statTotalTrend', '+12% dari bulan lalu');
  set('statSelesai', selesai);
  set('statSelesaiPct', `${pct(selesai)}% dari total`);
  set('statTerlambat', terlambat);
  set('statTerlambatPct', `${pct(terlambat)}% dari total`);
  set('statDenda', formatRp(totalDenda));
  set('statDendaNote', `${terlambat} pinjaman terlambat`);
  set('chartCenterTotal', total);
  updateChart({ selesai, terlambat, mengajukan, sedangDipinjam, ditolak, dibatalkan, total });
  updateTopFines();
}

function updateChart(counts) {
  const ctx = document.getElementById('summaryChart');
  if (!ctx) return;
  const { selesai, terlambat, mengajukan, sedangDipinjam, ditolak, dibatalkan, total } = counts;
  const data = [selesai, terlambat, sedangDipinjam, mengajukan];
  const labels = ['Selesai', 'Terlambat', 'Sedang Dipinjam', 'Mengajukan'];
  const colors = ['#10b981', '#ef4444', '#0ea5e9', '#8b5cf6'];
  if (ditolak > 0) {
    data.push(ditolak);
    labels.push('Ditolak');
    colors.push('#f43f5e');
  }
  if (dibatalkan > 0) {
    data.push(dibatalkan);
    labels.push('Dibatalkan');
    colors.push('#64748b');
  }
  if (summaryChartInstance) summaryChartInstance.destroy();
  summaryChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 0, hoverOffset: 4 }] },
    options: {
      cutout: '68%',
      plugins: { legend: { display: false } },
      maintainAspectRatio: true,
    },
  });
  const legend = document.getElementById('chartLegend');
  if (legend) {
    legend.innerHTML = labels.map((label, i) => {
      const n = data[i];
      const p = total ? ((n / total) * 100).toFixed(1) : 0;
      return `<li class="flex items-center justify-between gap-2">
        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" style="background:${colors[i]}"></span>${label}</span>
        <span class="font-semibold text-slate-700">${n} <span class="text-slate-400 font-normal">(${p}%)</span></span>
      </li>`;
    }).join('');
  }
}

function updateTopFines() {
  const list = document.getElementById('topFinesList');
  if (!list) return;
  const ranked = [...reportRows]
    .map((r) => ({ member: r.member, amt: parseDenda(r.denda) }))
    .filter((x) => x.amt > 0)
    .sort((a, b) => b.amt - a.amt)
    .slice(0, 3);
  if (!ranked.length) {
    list.innerHTML = '<li class="text-sm text-slate-400">Belum ada denda tercatat</li>';
    return;
  }
  list.innerHTML = ranked.map((item, i) => `
    <li class="flex items-center justify-between gap-2">
      <span class="flex items-center gap-2 text-sm text-slate-700">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#1E376E]/10 text-xs font-bold text-[#1E376E]">${i + 1}</span>
        ${escHtml(item.member)}
      </span>
      <span class="text-sm font-semibold text-red-600">${formatRp(item.amt)}</span>
    </li>`).join('');
}

function renderPagination() {
  const total = filteredRows.length;
  const totalPages = Math.max(1, Math.ceil(total / ROWS_PER_PAGE));
  if (currentPage > totalPages) currentPage = totalPages;
  const start = (currentPage - 1) * ROWS_PER_PAGE;
  const pageRows = filteredRows.slice(start, start + ROWS_PER_PAGE);
  const tbody = document.getElementById('reportTableBody');
  const emptyMsg = document.getElementById('reportEmpty');
  if (tbody) {
    tbody.innerHTML = pageRows.map((r, i) => rowHtml(r, start + i + 1)).join('');
  }
  if (emptyMsg) emptyMsg.classList.toggle('hidden', total > 0);
  const info = document.getElementById('paginationInfo');
  if (info) {
    if (!total) info.textContent = 'Menampilkan 0 dari 0 data';
    else info.textContent = `Menampilkan ${start + 1} – ${Math.min(start + ROWS_PER_PAGE, total)} dari ${total} data`;
  }
  const pag = document.getElementById('paginationContainer');
  if (!pag) return;
  pag.innerHTML = '';
  const addBtn = (label, page, active, disabled) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = label;
    btn.disabled = disabled;
    btn.className = `min-w-[2rem] rounded-lg px-2.5 py-1.5 text-xs font-medium transition ${active ? 'bg-[#1E376E] text-white' : disabled ? 'text-slate-300' : 'text-slate-600 hover:bg-slate-100'}`;
    if (!disabled && page) btn.addEventListener('click', () => { currentPage = page; renderPagination(); });
    pag.appendChild(btn);
  };
  addBtn('‹', currentPage - 1, false, currentPage <= 1);
  for (let p = 1; p <= totalPages; p++) {
    if (totalPages > 7 && p > 2 && p < totalPages - 1 && Math.abs(p - currentPage) > 1) {
      if (p === 3 || p === totalPages - 2) {
        const span = document.createElement('span');
        span.className = 'px-1 text-slate-400';
        span.textContent = '…';
        pag.appendChild(span);
      }
      continue;
    }
    addBtn(String(p), p, p === currentPage, false);
  }
  addBtn('›', currentPage + 1, false, currentPage >= totalPages);
}

function applyFilters() {
  filteredRows = getFilteredRows();
  currentPage = 1;
  renderPagination();
}

function resetFilters() {
  document.getElementById('reportSearch').value = '';
  document.getElementById('reportStatus').value = 'all';
  document.getElementById('dateStart').value = '';
  document.getElementById('dateEnd').value = '';
  applyFilters();
}

function openDetail(id) {
  const row = reportRows.find((r) => r.id === id);
  if (!row) return;
  currentDetailRow = row;
  document.getElementById('detailMember').value = row.member;
  document.getElementById('detailBookCode').value = row.bookCode;
  document.getElementById('detailBookTitle').value = row.bookTitle;
  document.getElementById('detailAuthor').value = row.bookAuthor;
  document.getElementById('detailCategory').value = row.category;
  document.getElementById('detailCover').src = row.cover;
  document.getElementById('detailStatus').value = row.status;
  document.getElementById('detailBorrowDisplay').value = formatDisplayDate(row.borrowIso);
  document.getElementById('detailDueDisplay').value = formatDisplayDate(row.dueIso);
  document.getElementById('detailTelat').value = row.telat || '—';
  document.getElementById('detailDenda').value = row.denda || '—';
  fbShow('reportDetailModal');
}

function closeDetail() {
  currentDetailRow = null;
  fbHide('reportDetailModal');
}

function csvEscape(val) {
  const s = String(val ?? '');
  return /[",\n]/.test(s) ? `"${s.replace(/"/g, '""')}"` : s;
}

function exportAllExcel() {
  const headers = ['No', 'Anggota', 'Buku', 'Tgl Pinjam', 'Tgl Kembali', 'Status', 'Denda'];
  const lines = [headers.join(',')];
  filteredRows.forEach((row, i) => {
    lines.push([i + 1, row.member, row.bookTitle, formatDisplayDate(row.borrowIso), formatDisplayDate(row.dueIso), row.status, row.denda].map(csvEscape).join(','));
  });
  const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = `laporan-peminjaman-${new Date().toISOString().slice(0, 10)}.csv`;
  a.click();
}

function exportAllPdf() {
  const rowsHtml = filteredRows.map((row, i) => `<tr><td>${i+1}</td><td>${escHtml(row.member)}</td><td>${escHtml(row.bookTitle)}</td><td>${escHtml(formatDisplayDate(row.borrowIso))}</td><td>${escHtml(formatDisplayDate(row.dueIso))}</td><td>${escHtml(row.status)}</td><td>${escHtml(row.denda)}</td></tr>`).join('');
  const w = window.open('', '_blank');
  if (w) {
    w.document.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><title>Laporan</title><style>body{font-family:system-ui;padding:24px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #e2e8f0;padding:8px;font-size:12px}th{background:#f1f5f9}</style></head><body><h1>Laporan Peminjaman</h1><table><thead><tr><th>No</th><th>Anggota</th><th>Buku</th><th>Pinjam</th><th>Kembali</th><th>Status</th><th>Denda</th></tr></thead><tbody>${rowsHtml}</tbody></table></body></html>`);
    w.document.close();
    w.focus();
    setTimeout(() => w.print(), 300);
  }
}

document.getElementById('reportTableBody').addEventListener('click', (e) => {
  const btn = e.target.closest('.report-action-btn');
  if (btn?.dataset.reportId) openDetail(btn.dataset.reportId);
});
document.getElementById('reportDetailClose').addEventListener('click', closeDetail);
document.getElementById('detailBtnBack').addEventListener('click', closeDetail);
document.getElementById('btnApplyFilter').addEventListener('click', applyFilters);
document.getElementById('btnResetFilter').addEventListener('click', resetFilters);
document.getElementById('exportAllPdfBtn').addEventListener('click', exportAllPdf);
document.getElementById('exportAllExcelBtn').addEventListener('click', exportAllExcel);

filteredRows = [...reportRows];
updateStats();
applyFilters();
</script>
@endpush
