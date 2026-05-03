@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')
@section('active_page', 'laporan')
@section('page_title', 'Laporan Peminjaman')

@section('content')
<div class="space-y-6">
  <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4">
    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
    <div>
      <h2 class="text-lg font-bold uppercase tracking-wide text-slate-800">Riwayat Peminjaman</h2>
      <p class="text-sm text-slate-500">Lihat Seluruh Aktivitas Peminjaman Anda</p>
    </div>
  </div>

  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-xl bg-[#A0D2EB] px-5 py-4 text-white shadow-sm">
      <p class="text-3xl font-bold">3</p>
      <p class="mt-1 text-sm font-medium text-white/95">Total Peminjaman</p>
    </div>
    <div class="rounded-xl bg-[#A0D2EB] px-5 py-4 text-white shadow-sm">
      <p class="text-3xl font-bold">2</p>
      <p class="mt-1 text-sm font-medium text-white/95">Sedang Dipinjam</p>
    </div>
    <div class="rounded-xl bg-[#A0D2EB] px-5 py-4 text-white shadow-sm">
      <p class="text-3xl font-bold">0</p>
      <p class="mt-1 text-sm font-medium text-white/95">Sudah Dikembalikan</p>
    </div>
    <div class="rounded-xl bg-[#A0D2EB] px-5 py-4 text-white shadow-sm">
      <p class="text-3xl font-bold">1</p>
      <p class="mt-1 text-sm font-medium text-white/95">Terlambat</p>
    </div>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-md">
    <div class="grid grid-cols-1 gap-3 md:grid-cols-12 md:items-end">
      <div class="md:col-span-4">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pencarian</label>
        <input id="reportSearch" type="search" placeholder="Cari Judul, Pengarang, atau penerbit..."
          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
        <select id="reportCategory"
          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="all">Semua Kategori</option>
          <option value="Fiksi">Fiksi</option>
          <option value="Pendidikan">Pendidikan</option>
          <option value="Bisnis">Bisnis</option>
        </select>
      </div>
      <div class="md:col-span-3">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Peminjaman</label>
        <input id="reportBorrowDate" type="date"
          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
      </div>
      <div class="md:col-span-3">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
        <select id="reportStatus"
          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
          <option value="all">Semua Status</option>
          <option value="Dipinjam">Dipinjam</option>
          <option value="Dikembalikan">Dikembalikan</option>
          <option value="Pending">Pending</option>
        </select>
      </div>
    </div>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead>
          <tr class="bg-pink-100 text-left text-slate-900">
            <th class="whitespace-nowrap px-4 py-3 font-semibold">Anggota</th>
            <th class="whitespace-nowrap px-4 py-3 font-semibold">Buku</th>
            <th class="whitespace-nowrap px-4 py-3 font-semibold">Tanggal pinjam</th>
            <th class="whitespace-nowrap px-4 py-3 font-semibold">Tanggal Kembali</th>
            <th class="whitespace-nowrap px-4 py-3 font-semibold text-center">Status</th>
            <th class="whitespace-nowrap px-4 py-3 font-semibold text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="reportTableBody" class="divide-y divide-slate-100 bg-white text-slate-800"></tbody>
      </table>
    </div>
    <p id="reportEmpty" class="hidden px-4 py-8 text-center text-sm text-slate-500">Tidak ada data yang cocok dengan filter.</p>
  </div>
</div>

<div id="reportDetailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4" aria-hidden="true">
  <div class="max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-6 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-slate-900">Detail Peminjaman</h3>
      <button type="button" id="reportDetailClose" class="text-2xl leading-none text-slate-500 hover:text-slate-700" aria-label="Tutup">&times;</button>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Nama Peminjam</label>
          <input id="detailMember" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku</label>
          <input id="detailBookCode" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku</label>
          <input id="detailBookTitle" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Penerbit</label>
          <input id="detailPublisher" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang</label>
          <input id="detailAuthor" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
          <input id="detailCategory" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Sampul Buku</label>
          <img id="detailCover" src="" alt="Sampul" class="h-40 w-28 rounded-lg border border-slate-200 object-cover shadow-sm">
        </div>
      </div>

      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
          <select id="detailStatus" disabled
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
            <option value="Pending">Pending</option>
            <option value="Dipinjam">Dipinjam</option>
            <option value="Dikembalikan">Dikembalikan</option>
          </select>
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit</label>
          <input id="detailYearPublished" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Peminjaman</label>
          <input id="detailBorrowDisplay" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Kembali</label>
          <input id="detailDueDisplay" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Telat</label>
          <input id="detailTelat" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Denda</label>
          <input id="detailDenda" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
      </div>
    </div>

    <div class="mt-8 flex flex-wrap items-center justify-end gap-2 border-t border-slate-200 pt-6">
      <button type="button" id="detailBtnBack"
        class="rounded-lg bg-slate-500 px-5 py-2 text-sm font-medium text-white hover:bg-slate-600 transition">Kembali</button>
      <div class="relative">
        <button type="button" id="exportDataBtn"
          class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
          Export Data
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div id="exportDropdown"
          class="absolute right-0 z-10 mt-1 hidden w-48 overflow-hidden rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
          <button type="button" id="exportPdfBtn"
            class="block w-full px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50 transition">Export PDF</button>
          <button type="button" id="exportExcelBtn"
            class="block w-full px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50 transition">Export Excel</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const MONTHS_ID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

function formatDisplayDate(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-').map(Number);
  if (!y || !m || !d) return '';
  return `${String(d).padStart(2, '0')}/${MONTHS_ID[m - 1]}/${y}`;
}

const reportRows = [
  {
    id: 'r1',
    member: 'Luthfi Dwi Apriyaldi',
    bookCode: 'BPKSJ123',
    bookTitle: 'Tentang Kamu',
    bookAuthor: 'Tere Liye',
    publisher: 'Republika Penerbit',
    category: 'Fiksi',
    yearPublished: '24/Oktober/2016',
    borrowIso: '2026-03-23',
    dueIso: '2026-03-30',
    telat: '2 Hari',
    denda: '0 RP',
    status: 'Dipinjam',
    cover: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg',
  },
  {
    id: 'r2',
    member: 'Muhammad Zaky Sadewa',
    bookCode: 'BPKSJ124',
    bookTitle: 'Atomic Habits',
    bookAuthor: 'James Clear',
    publisher: 'Avery Publishing',
    category: 'Pendidikan',
    yearPublished: '16/Oktober/2018',
    borrowIso: '2026-03-21',
    dueIso: '2026-03-28',
    telat: '0 Hari',
    denda: '0 RP',
    status: 'Dikembalikan',
    cover: 'https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg',
  },
  {
    id: 'r3',
    member: 'Luthfi Dwi Apriyaldi',
    bookCode: 'BPKSJ123',
    bookTitle: 'Tentang Kamu',
    bookAuthor: 'Tere Liye',
    publisher: 'Republika Penerbit',
    category: 'Fiksi',
    yearPublished: '24/Oktober/2016',
    borrowIso: '2026-03-23',
    dueIso: '2026-03-30',
    telat: '0 Hari',
    denda: '0 RP',
    status: 'Pending',
    cover: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg',
  },
];

let currentDetailRow = null;

function statusBadgeClass(status) {
  if (status === 'Dipinjam') return 'bg-emerald-500 text-white';
  if (status === 'Dikembalikan') return 'bg-orange-500 text-white';
  if (status === 'Pending') return 'bg-emerald-200 text-emerald-900';
  return 'bg-slate-200 text-slate-800';
}

function memberInitials(name) {
  const parts = name.trim().split(/\s+/);
  if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
  return name.slice(0, 2).toUpperCase();
}

function actionEyeButton(row) {
  return `
    <div class="flex justify-center">
      <button type="button" title="Detail" data-action="detail" data-report-id="${row.id}"
        class="report-action-btn inline-flex h-9 w-9 items-center justify-center rounded-lg bg-sky-400 text-white shadow-sm hover:bg-sky-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.289m7.633 7.634l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        </svg>
      </button>
    </div>`;
}

function rowHtml(row) {
  const initials = memberInitials(row.member);
  const br = formatDisplayDate(row.borrowIso);
  const du = formatDisplayDate(row.dueIso);
  return `
    <tr class="hover:bg-slate-50/80 transition">
      <td class="px-4 py-3">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-200 text-xs font-semibold text-slate-700">${initials}</div>
          <span class="font-medium text-slate-800">${row.member}</span>
        </div>
      </td>
      <td class="px-4 py-3">
        <div class="flex items-center gap-3">
          <img src="${row.cover}" alt="" class="h-14 w-10 shrink-0 rounded object-cover shadow-sm">
          <div>
            <p class="font-medium text-slate-800">${row.bookTitle}</p>
            <p class="text-xs text-slate-500">${row.bookAuthor}</p>
          </div>
        </div>
      </td>
      <td class="whitespace-nowrap px-4 py-3 text-slate-700">${br}</td>
      <td class="whitespace-nowrap px-4 py-3 text-slate-700">${du}</td>
      <td class="px-4 py-3 text-center">
        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${statusBadgeClass(row.status)}">${row.status}</span>
      </td>
      <td class="px-4 py-3">${actionEyeButton(row)}</td>
    </tr>`;
}

const tbody = document.getElementById('reportTableBody');
const emptyMsg = document.getElementById('reportEmpty');
const searchEl = document.getElementById('reportSearch');
const catEl = document.getElementById('reportCategory');
const dateEl = document.getElementById('reportBorrowDate');
const statusEl = document.getElementById('reportStatus');
const detailModal = document.getElementById('reportDetailModal');
const exportDropdown = document.getElementById('exportDropdown');
const exportDataBtn = document.getElementById('exportDataBtn');

function openDetail(id) {
  const row = reportRows.find((r) => r.id === id);
  if (!row) return;
  currentDetailRow = row;
  exportDropdown.classList.add('hidden');

  document.getElementById('detailMember').value = row.member;
  document.getElementById('detailBookCode').value = row.bookCode;
  document.getElementById('detailBookTitle').value = row.bookTitle;
  document.getElementById('detailPublisher').value = row.publisher;
  document.getElementById('detailAuthor').value = row.bookAuthor;
  document.getElementById('detailCategory').value = row.category;
  document.getElementById('detailCover').src = row.cover;
  document.getElementById('detailCover').alt = row.bookTitle;
  document.getElementById('detailStatus').value = row.status;
  document.getElementById('detailYearPublished').value = row.yearPublished;
  document.getElementById('detailBorrowDisplay').value = formatDisplayDate(row.borrowIso);
  document.getElementById('detailDueDisplay').value = formatDisplayDate(row.dueIso);
  document.getElementById('detailTelat').value = row.telat;
  document.getElementById('detailDenda').value = row.denda;

  detailModal.classList.remove('hidden');
  detailModal.classList.add('flex');
  detailModal.setAttribute('aria-hidden', 'false');
}

function closeDetail() {
  currentDetailRow = null;
  exportDropdown.classList.add('hidden');
  detailModal.classList.add('hidden');
  detailModal.classList.remove('flex');
  detailModal.setAttribute('aria-hidden', 'true');
}

function toggleExportMenu() {
  exportDropdown.classList.toggle('hidden');
}

function closeExportMenu() {
  exportDropdown.classList.add('hidden');
}

function csvEscape(val) {
  const s = String(val ?? '');
  if (/[",\n]/.test(s)) return `"${s.replace(/"/g, '""')}"`;
  return s;
}

function exportDetailExcel() {
  if (!currentDetailRow) return;
  const row = currentDetailRow;
  const headers = ['Nama Peminjam', 'Kode Buku', 'Judul Buku', 'Penerbit', 'Pengarang', 'Kategori', 'Status', 'Tahun Terbit', 'Tanggal Peminjaman', 'Tanggal Kembali', 'Telat', 'Denda'];
  const values = [
    row.member, row.bookCode, row.bookTitle, row.publisher, row.bookAuthor, row.category, row.status,
    row.yearPublished, formatDisplayDate(row.borrowIso), formatDisplayDate(row.dueIso), row.telat, row.denda,
  ];
  const csv = '\uFEFF' + headers.map(csvEscape).join(',') + '\n' + values.map(csvEscape).join(',');
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `detail-peminjaman-${row.bookCode}.csv`;
  a.click();
  URL.revokeObjectURL(url);
  closeExportMenu();
}

function exportDetailPdf() {
  if (!currentDetailRow) return;
  const row = currentDetailRow;
  const br = formatDisplayDate(row.borrowIso);
  const du = formatDisplayDate(row.dueIso);
  const html = `<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Detail Peminjaman</title>
    <style>body{font-family:system-ui,sans-serif;padding:24px;color:#1e293b;font-size:14px;}h1{font-size:18px;margin:0 0 16px;}table{border-collapse:collapse;width:100%;max-width:560px;}td{padding:8px 12px;border:1px solid #e2e8f0;}td:first-child{background:#f8fafc;font-weight:600;width:40%;}</style></head><body>
    <h1>Detail Peminjaman</h1>
    <table>
      <tr><td>Nama Peminjam</td><td>${escapeHtml(row.member)}</td></tr>
      <tr><td>Kode Buku</td><td>${escapeHtml(row.bookCode)}</td></tr>
      <tr><td>Judul Buku</td><td>${escapeHtml(row.bookTitle)}</td></tr>
      <tr><td>Penerbit</td><td>${escapeHtml(row.publisher)}</td></tr>
      <tr><td>Pengarang</td><td>${escapeHtml(row.bookAuthor)}</td></tr>
      <tr><td>Kategori</td><td>${escapeHtml(row.category)}</td></tr>
      <tr><td>Status</td><td>${escapeHtml(row.status)}</td></tr>
      <tr><td>Tahun Terbit</td><td>${escapeHtml(row.yearPublished)}</td></tr>
      <tr><td>Tanggal Peminjaman</td><td>${escapeHtml(br)}</td></tr>
      <tr><td>Tanggal Kembali</td><td>${escapeHtml(du)}</td></tr>
      <tr><td>Telat</td><td>${escapeHtml(row.telat)}</td></tr>
      <tr><td>Denda</td><td>${escapeHtml(row.denda)}</td></tr>
    </table>
    </body></html>`;
  const w = window.open('', '_blank');
  if (w) {
    w.document.write(html);
    w.document.close();
    w.focus();
    setTimeout(() => w.print(), 300);
  }
  closeExportMenu();
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

function render() {
  const kw = (searchEl.value || '').toLowerCase().trim();
  const cat = catEl.value;
  const dateFilter = dateEl.value;
  const st = statusEl.value;

  const filtered = reportRows.filter((row) => {
    const haystack = (row.bookTitle + ' ' + row.bookAuthor + ' ' + row.member).toLowerCase();
    const matchKw = !kw || haystack.includes(kw);
    const matchCat = cat === 'all' || row.category === cat;
    const matchDate = !dateFilter || row.borrowIso === dateFilter;
    const matchStatus = st === 'all' || row.status === st;
    return matchKw && matchCat && matchDate && matchStatus;
  });

  tbody.innerHTML = filtered.map((r) => rowHtml(r)).join('');
  emptyMsg.classList.toggle('hidden', filtered.length > 0);
}

tbody.addEventListener('click', (e) => {
  const btn = e.target.closest('.report-action-btn');
  if (!btn) return;
  if (btn.getAttribute('data-action') === 'detail') {
    openDetail(btn.getAttribute('data-report-id'));
  }
});

document.getElementById('reportDetailClose').addEventListener('click', closeDetail);
document.getElementById('detailBtnBack').addEventListener('click', closeDetail);
exportDataBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  toggleExportMenu();
});
document.getElementById('exportPdfBtn').addEventListener('click', () => exportDetailPdf());
document.getElementById('exportExcelBtn').addEventListener('click', () => exportDetailExcel());
document.addEventListener('click', (e) => {
  if (!exportDropdown.classList.contains('hidden') && !e.target.closest('#exportDataBtn') && !e.target.closest('#exportDropdown')) {
    closeExportMenu();
  }
});
detailModal.addEventListener('click', (e) => {
  if (e.target === detailModal) closeDetail();
});

searchEl.addEventListener('input', render);
catEl.addEventListener('change', render);
dateEl.addEventListener('change', render);
statusEl.addEventListener('change', render);
render();
</script>
@endpush
