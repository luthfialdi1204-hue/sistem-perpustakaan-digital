@extends('layouts.mahasiswa')

@section('title', 'Riwayat Peminjaman')
@section('active_page', 'riwayat')
@section('page_title', 'Riwayat Peminjaman')
@section('page_subtitle', 'Lihat seluruh aktivitas peminjaman buku Anda.')

@section('content')

<!-- RINGKASAN STATISTIK -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#1E376E] ring-4 ring-[#1E376E]/15">
      <i class="bi bi-journal-bookmark text-white text-xl"></i>
    </div>
    <div class="min-w-0">
      <p class="text-sm text-slate-500">Total Peminjaman</p>
      <p id="statTotal" class="text-2xl font-bold text-slate-800">0</p>
      <p class="mt-0.5 text-xs text-emerald-600 flex items-center gap-0.5">
        <i class="bi bi-arrow-up-short text-base"></i> Semua transaksi tercatat
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
      <p id="statDendaNote" class="mt-0.5 text-xs text-rose-500 flex items-center gap-0.5">
        <i class="bi bi-exclamation-circle"></i> <span id="statDendaNoteText">Tidak ada denda</span>
      </p>
    </div>
  </div>
</div>

<!-- FILTER -->
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <i class="bi bi-funnel text-[#1E376E]"></i>
    <h2 class="font-semibold text-[#1E376E]">Filter Riwayat</h2>
  </div>
  <div class="p-5">
    <div class="grid gap-3 md:grid-cols-4">
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
        <select id="historyStatusFilter"
          class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2.5 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
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
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pencarian</label>
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
          <input id="historySearch" type="search" placeholder="Cari judul, pengarang, atau kode buku..."
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="flex gap-2 md:col-span-4">
        <button type="button" id="historyFilterReset"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
          Reset
        </button>
        <button type="button" id="historyFilterApply"
          class="inline-flex items-center gap-1.5 rounded-xl bg-[#1E376E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#162d5c]">
          <i class="bi bi-funnel-fill text-xs"></i> Tampilkan
        </button>
      </div>
    </div>
  </div>
</div>

<!-- DAFTAR RIWAYAT -->
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
  <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 via-[#1E376E]/5 to-teal-500/10 px-5 py-4">
    <div class="flex items-center gap-2">
      <i class="bi bi-journal-text text-[#1E376E] text-lg"></i>
      <h3 class="font-semibold text-[#1E376E]">Daftar Riwayat Peminjaman</h3>
    </div>
    <span id="historyCount" class="text-xs font-medium text-slate-500"></span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm table-auto">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
          <th class="px-3 py-2 w-[70px]">Buku</th>
          <th class="px-3 py-2">Informasi</th>
          <th class="px-3 py-2 whitespace-nowrap">Tgl Pinjam</th>
          <th class="px-3 py-2 whitespace-nowrap">Tgl Kembali</th>
          <th class="px-3 py-2 text-center whitespace-nowrap">Status</th>
          <th class="px-3 py-2 whitespace-nowrap">Denda</th>
          <th class="px-3 py-2 min-w-[88px] text-center">Aksi</th>
        </tr>
      </thead>
      <tbody id="historyTableBody" class="divide-y divide-slate-100"></tbody>
    </table>
    <p id="historyEmpty" class="hidden px-5 py-12 text-center text-sm text-slate-500">
      <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
      Belum ada riwayat peminjaman.
    </p>
  </div>
</div>

<!-- MODAL DETAIL -->
<div id="detailModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="relative w-[850px] max-w-[95%] rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <h2 class="mb-4 border-b border-slate-200 pb-2 text-lg font-semibold text-[#1E376E]">Detail Riwayat</h2>
    <h3 class="mb-4 text-lg font-bold text-slate-800">Detail Buku</h3>
    <div class="grid grid-cols-1 gap-6 text-sm sm:grid-cols-2">
      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Kode Buku</label>
          <input type="text" id="modalBookCode" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Judul Buku</label>
          <input type="text" id="modalBookTitle" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Penerbit</label>
          <input type="text" id="modalPublisher" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Pengarang</label>
          <input type="text" id="modalAuthor" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Kategori</label>
          <input type="text" id="modalCategory" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <img id="modalCover" src="" alt="" class="mt-3 w-28 rounded-lg border border-slate-200 shadow-sm">
      </div>
      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Tahun Terbit</label>
          <input type="text" id="modalYearPublished" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Peminjaman</label>
          <input type="text" id="modalBorrowDate" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Kembali</label>
          <input type="text" id="modalDueDate" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
          <div id="modalStatusBadge" class="mt-1"></div>
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Telat</label>
          <input type="text" id="modalTelat" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Denda</label>
          <input type="text" id="modalDenda" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
      </div>
    </div>
    <div class="mt-6 flex flex-wrap justify-end gap-2">
      <button type="button" id="modalCancelBtn" class="hidden rounded-lg border border-rose-200 bg-rose-50 px-5 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
        <i class="bi bi-x-circle me-1"></i> Batalkan Pengajuan
      </button>
      <button type="button" onclick="closeDetailModal()"
        class="rounded-lg bg-slate-500 px-6 py-2 text-white transition hover:bg-slate-600">
        Kembali
      </button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI BATAL -->
<div id="cancelConfirmModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-rose-100 text-rose-600">
      <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
    </div>
    <h3 class="text-center text-lg font-bold text-slate-800">Batalkan Pengajuan?</h3>
    <p class="mt-2 text-center text-sm text-slate-500">
      Pengajuan peminjaman <span id="cancelBookTitle" class="font-semibold text-slate-700"></span> akan dibatalkan.
      Tindakan ini tidak dapat dibatalkan.
    </p>
    <div class="mt-6 flex gap-3">
      <button type="button" id="cancelConfirmNo"
        class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
        Tidak, Kembali
      </button>
      <button type="button" id="cancelConfirmYes"
        class="flex-1 rounded-xl bg-rose-500 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-600">
        Ya, Batalkan
      </button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="historyToast" class="pointer-events-none fixed bottom-6 right-6 z-[70] translate-y-4 opacity-0 transition-all duration-300">
  <div class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-lg">
    <i class="bi bi-check-circle-fill text-lg"></i>
    <span id="historyToastText">Pengajuan berhasil dibatalkan.</span>
  </div>
</div>

@endsection

@push('scripts')
<script>
const historyRows = [
  {
    id: 'h1',
    bookTitle: 'Tentang Kamu',
    bookAuthor: 'Tere Liye',
    bookCode: 'BPKSJ123',
    category: 'Fiksi',
    publisher: 'Republika Penerbit',
    yearPublished: '24/Oktober/2016',
    cover: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg',
    submittedAt: '23/03/2026 16:03',
    borrowIso: '2026-03-23',
    dueIso: '2026-03-30',
    dueNote: '',
    telat: '1 hari',
    denda: 'Rp4.000',
    status: 'Terlambat',
  },
  {
    id: 'h2',
    bookTitle: 'Tentang Kamu',
    bookAuthor: 'Tere Liye',
    bookCode: 'BPKSJ124',
    category: 'Fiksi',
    publisher: 'Republika Penerbit',
    yearPublished: '24/Oktober/2016',
    cover: 'https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg',
    submittedAt: '23/03/2026 16:03',
    borrowIso: '2026-03-23',
    dueIso: '2026-03-30',
    dueNote: '1 hari lagi',
    telat: '',
    denda: 'Rp0',
    status: 'Sedang Dipinjam',
  },
  {
    id: 'h3',
    bookTitle: 'Tentang Kamu',
    bookAuthor: 'Tere Liye',
    bookCode: 'BPKSJ124',
    category: 'Fiksi',
    publisher: 'Republika Penerbit',
    yearPublished: '24/Oktober/2016',
    cover: 'https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg',
    submittedAt: '23/03/2026 16:03',
    borrowIso: '2026-03-23',
    dueIso: '2026-03-30',
    dueNote: '',
    telat: '',
    denda: '—',
    status: 'Mengajukan',
  },
];

let pendingCancelId = null;
let viewingDetailId = null;
let toastTimer = null;

const tbody = document.getElementById('historyTableBody');
const emptyMsg = document.getElementById('historyEmpty');
const detailModal = document.getElementById('detailModal');
const cancelConfirmModal = document.getElementById('cancelConfirmModal');
const statusFilterEl = document.getElementById('historyStatusFilter');
const searchEl = document.getElementById('historySearch');
const countEl = document.getElementById('historyCount');

const INACTIVE_STATUSES = ['Ditolak', 'Dibatalkan'];
const NO_DENDA_STATUSES = ['Mengajukan', 'Ditolak', 'Dibatalkan'];

function escHtml(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function formatShortDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso + 'T00:00:00');
  if (Number.isNaN(d.getTime())) return iso;
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function formatLongDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso + 'T00:00:00');
  if (Number.isNaN(d.getTime())) return iso;
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

function parseDendaAmount(str) {
  if (!str || str === '—') return 0;
  const n = parseInt(String(str).replace(/\D/g, ''), 10);
  return Number.isNaN(n) ? 0 : n;
}

function formatRp(n) {
  return 'Rp' + n.toLocaleString('id-ID');
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
  if (NO_DENDA_STATUSES.includes(row.status)) {
    return '<span class="text-xs text-slate-400">—</span>';
  }
  if (row.status === 'Sudah Lunas') {
    return '<span class="font-semibold text-teal-600 text-xs">Rp0 <span class="text-[11px] font-normal text-slate-400">(lunas)</span></span>';
  }
  const hasFine = row.denda && row.denda !== 'Rp0' && row.denda !== '—';
  const cls = (row.status === 'Terlambat' || (row.status === 'Dikembalikan' && hasFine))
    ? 'font-semibold text-red-600 text-xs'
    : 'font-semibold text-emerald-600 text-xs';
  const extra = row.status === 'Terlambat' && row.telat
    ? `<span class="text-[11px] text-red-500 ml-1">(${escHtml(row.telat)})</span>`
    : '';
  return `<span class="${cls}">${escHtml(row.denda || 'Rp0')}</span>${extra}`;
}

function dueDateCell(row) {
  const due = formatShortDate(row.dueIso);
  let note = '';
  if (row.status === 'Terlambat' && row.telat) {
    note = `<p class="text-[11px] font-medium text-red-500">Telat ${escHtml(row.telat)}</p>`;
  } else if (row.dueNote && !INACTIVE_STATUSES.includes(row.status)) {
    note = `<p class="text-[11px] font-medium text-emerald-600">${escHtml(row.dueNote)}</p>`;
  }
  return `<p class="text-slate-700 text-xs whitespace-nowrap">${escHtml(due)}</p>${note}`;
}

function borrowDateCell(row) {
  if (row.status === 'Mengajukan') {
    return '<span class="text-xs text-violet-600 font-medium">Menunggu</span>';
  }
  if (INACTIVE_STATUSES.includes(row.status)) {
    return '<span class="text-xs text-slate-400">—</span>';
  }
  return escHtml(formatShortDate(row.borrowIso));
}

function telatDisplay(row) {
  if (NO_DENDA_STATUSES.includes(row.status)) return '—';
  if (row.telat) return row.telat;
  return 'Tidak ada';
}

function dendaDisplay(row) {
  if (NO_DENDA_STATUSES.includes(row.status)) return '—';
  if (row.status === 'Sudah Lunas') return 'Rp0 (lunas)';
  return row.denda && row.denda !== '—' ? row.denda : 'Rp0';
}

function rowClass(row) {
  if (row.status === 'Dibatalkan' || row.status === 'Ditolak') {
    return 'group bg-slate-50/60 hover:bg-slate-50 transition-colors';
  }
  return 'group hover:bg-slate-50/80 transition-colors';
}

function getFilteredRows() {
  const status = statusFilterEl.value;
  const kw = (searchEl.value || '').toLowerCase().trim();
  return historyRows.filter((row) => {
    const matchStatus = status === 'all' || row.status === status;
    const haystack = `${row.bookTitle} ${row.bookAuthor} ${row.bookCode} ${row.category}`.toLowerCase();
    const matchKw = !kw || haystack.includes(kw);
    return matchStatus && matchKw;
  });
}

function actionCell(row) {
  const detailBtn = `
    <button type="button" title="Lihat detail" data-action="detail" data-id="${row.id}"
      class="history-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#1E376E] to-teal-600 text-white shadow-sm hover:brightness-110 transition">
      <i class="bi bi-eye text-sm"></i>
    </button>`;
  if (row.status === 'Mengajukan') {
    return `
      <div class="flex items-center justify-center gap-1.5">
        ${detailBtn}
        <button type="button" title="Batalkan pengajuan" data-action="cancel" data-id="${row.id}"
          class="history-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-600 shadow-sm transition hover:bg-rose-100">
          <i class="bi bi-x-circle text-sm"></i>
        </button>
      </div>`;
  }
  return `<div class="flex justify-center">${detailBtn}</div>`;
}

function rowHtml(row) {
  return `
    <tr class="${rowClass(row)}" data-id="${row.id}">
      <td class="px-3 py-2.5 align-middle">
        <img src="${escHtml(row.cover)}" alt="${escHtml(row.bookTitle)}"
          class="w-14 h-[72px] object-cover rounded-lg border border-slate-200 shadow-sm">
      </td>
      <td class="px-3 py-2.5 align-middle">
        <p class="font-semibold text-slate-800 leading-tight">${escHtml(row.bookTitle)}</p>
        <p class="text-xs text-slate-500">${escHtml(row.bookAuthor)} · <span class="text-[#1E376E] font-medium">${escHtml(row.bookCode)}</span></p>
        <p class="text-[11px] text-slate-400 mt-0.5"><i class="bi bi-clock"></i> ${escHtml(row.submittedAt)}</p>
      </td>
      <td class="px-3 py-2.5 align-middle text-slate-700 whitespace-nowrap text-xs">${borrowDateCell(row)}</td>
      <td class="px-3 py-2.5 align-middle">${dueDateCell(row)}</td>
      <td class="px-3 py-2.5 align-middle text-center">${statusBadgeHtml(row.status)}</td>
      <td class="px-3 py-2.5 align-middle whitespace-nowrap">${dendaCell(row)}</td>
      <td class="px-3 py-2.5 align-middle text-center">${actionCell(row)}</td>
    </tr>`;
}

function updateStats() {
  const active = historyRows.filter((r) => !INACTIVE_STATUSES.includes(r.status));
  const total = active.length;
  const selesai = historyRows.filter((r) => r.status === 'Dikembalikan' || r.status === 'Sudah Lunas').length;
  const terlambat = historyRows.filter((r) => r.status === 'Terlambat').length;
  const totalDenda = historyRows.reduce((sum, r) => {
    if (r.status === 'Terlambat' || (r.status === 'Dikembalikan' && parseDendaAmount(r.denda) > 0)) {
      return sum + parseDendaAmount(r.denda);
    }
    return sum;
  }, 0);

  const pct = (n) => (total ? ((n / total) * 100).toFixed(1).replace('.0', '') : '0');

  document.getElementById('statTotal').textContent = total;
  document.getElementById('statSelesai').textContent = selesai;
  document.getElementById('statSelesaiPct').textContent = `${pct(selesai)}% dari total aktif`;
  document.getElementById('statTerlambat').textContent = terlambat;
  document.getElementById('statTerlambatPct').textContent = `${pct(terlambat)}% dari total aktif`;
  document.getElementById('statDenda').textContent = formatRp(totalDenda);
  document.getElementById('statDendaNoteText').textContent = terlambat
    ? `${terlambat} pinjaman terlambat`
    : 'Tidak ada denda';
}

function render() {
  const visible = getFilteredRows();
  tbody.innerHTML = visible.map((r) => rowHtml(r)).join('');
  const hasAny = historyRows.length > 0;
  const hasVisible = visible.length > 0;
  emptyMsg.classList.toggle('hidden', hasVisible);
  if (countEl) {
    countEl.textContent = hasAny
      ? `Menampilkan ${visible.length} dari ${historyRows.length} transaksi`
      : '';
  }
  if (!hasVisible && hasAny) {
    emptyMsg.innerHTML = '<i class="bi bi-funnel mb-2 block text-3xl text-slate-300"></i>Tidak ada data sesuai filter.';
    emptyMsg.classList.remove('hidden');
  } else if (!hasAny) {
    emptyMsg.innerHTML = '<i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>Belum ada riwayat peminjaman.';
  }
  updateStats();
}

function openDetailModal(id) {
  const row = historyRows.find((r) => r.id === id);
  if (!row) return;
  viewingDetailId = id;
  document.getElementById('modalBookCode').value = row.bookCode;
  document.getElementById('modalBookTitle').value = row.bookTitle;
  document.getElementById('modalPublisher').value = row.publisher;
  document.getElementById('modalAuthor').value = row.bookAuthor;
  document.getElementById('modalCategory').value = row.category;
  document.getElementById('modalYearPublished').value = row.yearPublished;
  document.getElementById('modalBorrowDate').value = formatLongDate(row.borrowIso);
  document.getElementById('modalDueDate').value = formatLongDate(row.dueIso);
  document.getElementById('modalTelat').value = telatDisplay(row);
  document.getElementById('modalDenda').value = dendaDisplay(row);
  document.getElementById('modalStatusBadge').innerHTML = statusBadgeHtml(row.status);
  document.getElementById('modalCover').src = row.cover;
  document.getElementById('modalCover').alt = row.bookTitle;

  const cancelBtn = document.getElementById('modalCancelBtn');
  if (row.status === 'Mengajukan') {
    cancelBtn.classList.remove('hidden');
    cancelBtn.onclick = () => {
      closeDetailModal();
      openCancelConfirm(id);
    };
  } else {
    cancelBtn.classList.add('hidden');
    cancelBtn.onclick = null;
  }

  fbShow('detailModal');
  detailModal.setAttribute('aria-hidden', 'false');
}

function closeDetailModal() {
  viewingDetailId = null;
  fbHide('detailModal');
  detailModal.setAttribute('aria-hidden', 'true');
}

function openCancelConfirm(id) {
  const row = historyRows.find((r) => r.id === id);
  if (!row || row.status !== 'Mengajukan') return;
  pendingCancelId = id;
  document.getElementById('cancelBookTitle').textContent = `"${row.bookTitle}"`;
  fbShow('cancelConfirmModal');
  cancelConfirmModal.setAttribute('aria-hidden', 'false');
}

function closeCancelConfirm() {
  pendingCancelId = null;
  fbHide('cancelConfirmModal');
  cancelConfirmModal.setAttribute('aria-hidden', 'true');
}

function cancelApplication(id) {
  const row = historyRows.find((r) => r.id === id);
  if (!row || row.status !== 'Mengajukan') return;
  row.status = 'Dibatalkan';
  row.denda = '—';
  row.dueNote = '';
  row.telat = '';
  closeCancelConfirm();
  if (viewingDetailId === id) closeDetailModal();
  render();
  showToast('Pengajuan peminjaman berhasil dibatalkan.');
}

function showToast(message) {
  const toast = document.getElementById('historyToast');
  document.getElementById('historyToastText').textContent = message;
  toast.classList.remove('translate-y-4', 'opacity-0');
  toast.classList.add('translate-y-0', 'opacity-100');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    toast.classList.add('translate-y-4', 'opacity-0');
    toast.classList.remove('translate-y-0', 'opacity-100');
  }, 3200);
}

tbody.addEventListener('click', (e) => {
  const btn = e.target.closest('.history-action-btn');
  if (!btn) return;
  const id = btn.dataset.id;
  const action = btn.dataset.action;
  if (action === 'detail') openDetailModal(id);
  if (action === 'cancel') openCancelConfirm(id);
});

document.getElementById('cancelConfirmNo').addEventListener('click', closeCancelConfirm);
document.getElementById('cancelConfirmYes').addEventListener('click', () => {
  if (pendingCancelId) cancelApplication(pendingCancelId);
});

detailModal.addEventListener('click', (e) => {
  if (e.target === detailModal) closeDetailModal();
});
cancelConfirmModal.addEventListener('click', (e) => {
  if (e.target === cancelConfirmModal) closeCancelConfirm();
});

document.addEventListener('keydown', (e) => {
  if (e.key !== 'Escape') return;
  if (!cancelConfirmModal.classList.contains('hidden')) closeCancelConfirm();
  else if (!detailModal.classList.contains('hidden')) closeDetailModal();
});

document.getElementById('historyFilterApply').addEventListener('click', render);
document.getElementById('historyFilterReset').addEventListener('click', () => {
  statusFilterEl.value = 'all';
  searchEl.value = '';
  render();
});
searchEl.addEventListener('keydown', (e) => {
  if (e.key === 'Enter') render();
});

render();
</script>
@endpush
