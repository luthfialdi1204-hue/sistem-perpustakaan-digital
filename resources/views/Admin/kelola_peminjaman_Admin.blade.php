@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')
@section('active_page', 'kelola-peminjaman')
@section('page_title', 'Kelola Peminjaman')
@section('page_subtitle', 'Kelola dan pantau seluruh aktivitas peminjaman buku.')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
  <div class="flex flex-col gap-4 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 via-[#1E376E]/5 to-teal-500/10 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex items-center gap-2">
      <i class="bi bi-journal-text text-[#1E376E] text-lg"></i>
      <h2 class="font-semibold text-[#1E376E]">Daftar Peminjaman</h2>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <div class="relative w-full sm:max-w-xs">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input id="loanSearch" type="search" placeholder="Cari anggota, judul, atau pengarang..."
          class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
      </div>
      <select id="loanCategory"
        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20 sm:w-44">
        <option value="all">Semua Kategori</option>
        <option value="Fiksi">Fiksi</option>
        <option value="Pendidikan">Pendidikan</option>
        <option value="Bisnis">Bisnis</option>
      </select>
    </div>
  </div>

  <div class="overflow-x-auto px-5 pb-2">
    <div id="loanEmpty" class="hidden rounded-xl border border-dashed border-slate-200 bg-slate-50 py-12 text-center text-sm text-slate-500">
      <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
      Tidak ada data yang cocok dengan filter.
    </div>
    <table class="w-full min-w-[960px] text-sm table-auto">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
          <th class="px-3 py-3">Anggota</th>
          <th class="px-3 py-3 w-[70px]">Buku</th>
          <th class="px-3 py-3">Informasi</th>
          <th class="px-3 py-3 whitespace-nowrap">Tgl Pinjam</th>
          <th class="px-3 py-3 whitespace-nowrap">Tgl Kembali</th>
          <th class="px-3 py-3 text-center whitespace-nowrap">Status</th>
          <th class="px-3 py-3 whitespace-nowrap">Denda</th>
          <th class="px-3 py-3 text-center w-[100px]">Aksi</th>
        </tr>
      </thead>
      <tbody id="loanTableBody" class="divide-y divide-slate-100"></tbody>
    </table>
  </div>
</div>

<div id="editLoanModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <h3 class="mb-1 text-lg font-semibold text-[#1E376E]">Proses Pengembalian</h3>
    <p class="mb-6 text-xs text-slate-500">Pilih status setelah buku diterima di perpustakaan.</p>

    <div class="grid gap-6 lg:grid-cols-2">
      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Nama Peminjam</label>
          <input id="modalMember" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku</label>
          <input id="modalBookCode" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku</label>
          <input id="modalBookTitle" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Penerbit</label>
          <input id="modalPublisher" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang</label>
          <input id="modalAuthor" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
          <input id="modalCategory" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Sampul Buku</label>
          <img id="modalCover" src="" alt="Sampul" class="h-40 w-28 rounded-lg border border-slate-200 object-cover shadow-sm">
        </div>
      </div>

      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
          <select id="modalStatus"
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
            <option value="Dikembalikan">Dikembalikan</option>
            <option value="Sudah Lunas">Sudah Lunas</option>
          </select>
          <p class="mt-1 text-[11px] text-slate-500"><strong>Dikembalikan</strong> = buku sudah kembali. <strong>Sudah Lunas</strong> = buku kembali dan denda sudah dibayar.</p>
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit</label>
          <input id="modalYearPublished" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Peminjaman</label>
          <input id="modalBorrowDate" type="date"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Kembali</label>
          <input id="modalDueDate" type="date"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Keterlambatan</label>
          <input id="modalTelat" type="text"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Denda</label>
          <input id="modalDenda" type="text"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
    </div>

    <div class="mt-8 flex justify-end gap-2 border-t border-slate-200 pt-4">
      <button type="button" id="modalBtnBack"
        class="rounded-lg border border-slate-200 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Kembali</button>
      <button type="button" id="modalBtnSave"
        class="rounded-lg bg-[#1E376E] px-5 py-2 text-sm font-semibold text-white hover:bg-[#162d5c] transition">Simpan Status</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const MONTHS_ID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

function formatDisplayDate(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-').map(Number);
  if (!y || !m || !d) return '';
  return `${String(d).padStart(2, '0')}/${MONTHS_ID[m - 1]}/${y}`;
}

function formatShortDate(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-').map(Number);
  if (!y || !m || !d) return '';
  return `${d} ${MONTHS_SHORT[m - 1]} ${y}`;
}

function parseIsoFromDisplay(str) {
  if (!str || !str.includes('/')) return '';
  const parts = str.split('/');
  if (parts.length !== 3) return '';
  const d = parseInt(parts[0], 10);
  const monthName = parts[1];
  const y = parseInt(parts[2], 10);
  const mi = MONTHS_ID.findIndex((x) => x.toLowerCase() === String(monthName).toLowerCase());
  if (mi < 0 || !y || !d) return '';
  return `${y}-${String(mi + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
}

function escHtml(s) {
  return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}

const loans = [
  {
    id: 'l1',
    member: 'Luthfi Dwi Apriyaldi',
    nim: '3312501077',
    bookCode: 'BPKSJ123',
    bookTitle: 'Tentang Kamu',
    bookAuthor: 'Tere Liye',
    publisher: 'Republika Penerbit',
    category: 'Fiksi',
    yearPublished: '24/Oktober/2016',
    borrowIso: '2026-03-23',
    dueIso: '2026-03-30',
    telat: '2 hari',
    denda: 'Rp4.000',
    status: 'Terlambat',
    cover: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg',
  },
  {
    id: 'l2',
    member: 'Muhammad Zaky Sadewa',
    nim: '3312501085',
    bookCode: 'BPKSJ124',
    bookTitle: 'Atomic Habits',
    bookAuthor: 'James Clear',
    publisher: 'Avery Publishing',
    category: 'Pendidikan',
    yearPublished: '16/Oktober/2018',
    borrowIso: '2026-03-21',
    dueIso: '2026-03-28',
    telat: '',
    denda: 'Rp0',
    status: 'Sudah Lunas',
    cover: 'https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg',
  },
  {
    id: 'l3',
    member: 'Luthfi Dwi Apriyaldi',
    nim: '3312501077',
    bookCode: 'BPKSJ123',
    bookTitle: 'Tentang Kamu',
    bookAuthor: 'Tere Liye',
    publisher: 'Republika Penerbit',
    category: 'Fiksi',
    yearPublished: '24/Oktober/2016',
    borrowIso: '2026-03-23',
    dueIso: '2026-03-30',
    telat: '',
    denda: '—',
    status: 'Mengajukan',
    cover: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg',
  },
  {
    id: 'l4',
    member: 'Cristh Velato Arioranga',
    nim: '3312601082',
    bookCode: 'BPKSJ125',
    bookTitle: 'Filosofi Teras',
    bookAuthor: 'Henry Manampiring',
    publisher: 'Kompas',
    category: 'Pendidikan',
    yearPublished: '2018',
    borrowIso: '2026-03-25',
    dueIso: '2026-04-01',
    telat: '',
    dueNote: '3 hari lagi',
    denda: 'Rp0',
    status: 'Sedang Dipinjam',
    cover: 'https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg',
  },
  {
    id: 'l5',
    member: 'Andi Pratama',
    nim: '3312501090',
    bookCode: 'BPKSJ126',
    bookTitle: 'Bumi',
    bookAuthor: 'Tere Liye',
    publisher: 'Gramedia',
    category: 'Fiksi',
    yearPublished: '2014',
    borrowIso: '2026-03-10',
    dueIso: '2026-03-17',
    telat: '3 hari',
    denda: 'Rp6.000',
    status: 'Dikembalikan',
    cover: 'https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg',
  },
];

function loanBorrowDisplay(row) {
  return row.borrow || formatShortDate(row.borrowIso) || formatDisplayDate(row.borrowIso);
}
function loanDueDisplay(row) {
  return row.due || formatShortDate(row.dueIso) || formatDisplayDate(row.dueIso);
}

function statusBadgeHtml(status) {
  const badges = {
    Mengajukan: '<span class="inline-flex items-center gap-1 rounded-md bg-violet-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-send-fill"></i> Mengajukan</span>',
    'Sedang Dipinjam': '<span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-book-fill"></i> Sedang Dipinjam</span>',
    Terlambat: '<span class="inline-flex items-center gap-1 rounded-md bg-red-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-exclamation-circle-fill"></i> Terlambat</span>',
    Dikembalikan: '<span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-box-arrow-in-left"></i> Dikembalikan</span>',
    'Sudah Lunas': '<span class="inline-flex items-center gap-1 rounded-md bg-teal-600 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-cash-coin"></i> Sudah Lunas</span>',
    Ditolak: '<span class="inline-flex items-center gap-1 rounded-md bg-rose-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-x-circle-fill"></i> Ditolak</span>',
  };
  return badges[status] || `<span class="inline-flex rounded-md bg-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-700">${escHtml(status)}</span>`;
}

function dendaCell(row) {
  if (row.status === 'Mengajukan' || row.status === 'Ditolak') {
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
  const du = loanDueDisplay(row);
  let note = '';
  if (row.status === 'Terlambat' && row.telat) {
    note = `<p class="text-[11px] font-medium text-red-500">Telat ${escHtml(row.telat)}</p>`;
  } else if (row.dueNote) {
    note = `<p class="text-[11px] font-medium text-emerald-600">${escHtml(row.dueNote)}</p>`;
  }
  return `<p class="text-slate-700 text-xs whitespace-nowrap">${escHtml(du)}</p>${note}`;
}

function memberInitials(name) {
  const parts = name.trim().split(/\s+/);
  if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
  return name.slice(0, 2).toUpperCase();
}

function actionCell(row) {
  if (row.status === 'Mengajukan') {
    return `
      <div class="flex items-center justify-center gap-1.5">
        <button type="button" title="Setujui" data-action="approve" data-loan-id="${row.id}"
          class="loan-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-white hover:bg-emerald-600">
          <i class="bi bi-check-lg text-sm"></i>
        </button>
        <button type="button" title="Tolak" data-action="reject" data-loan-id="${row.id}"
          class="loan-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg bg-rose-500 text-white hover:bg-rose-600">
          <i class="bi bi-x-lg text-sm"></i>
        </button>
      </div>`;
  }
  if (row.status === 'Ditolak' || row.status === 'Sudah Lunas') {
    return `<span class="text-[11px] font-semibold text-slate-400">—</span>`;
  }
  return `
    <div class="flex justify-center">
      <button type="button" title="Edit" data-action="edit" data-loan-id="${row.id}"
        class="loan-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-500 text-white hover:bg-sky-600">
        <i class="bi bi-pencil text-sm"></i>
      </button>
    </div>`;
}

function rowHtml(row) {
  const initials = memberInitials(row.member);
  const br = loanBorrowDisplay(row);
  return `
    <tr class="group hover:bg-slate-50/80 transition-colors">
      <td class="px-3 py-2.5 align-middle">
        <div class="flex items-center gap-2 min-w-[150px]">
          <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#1E376E]/10 text-xs font-bold text-[#1E376E]">${initials}</div>
          <div>
            <p class="font-semibold text-slate-800 leading-tight">${escHtml(row.member)}</p>
            <p class="text-[11px] text-slate-500">${escHtml(row.nim || '')}</p>
          </div>
        </div>
      </td>
      <td class="px-3 py-2.5 align-middle">
        <img src="${escHtml(row.cover)}" alt="" class="w-14 h-[72px] object-cover rounded-lg border border-slate-200 shadow-sm">
      </td>
      <td class="px-3 py-2.5 align-middle">
        <p class="font-semibold text-slate-800 leading-tight">${escHtml(row.bookTitle)}</p>
        <p class="text-xs text-slate-500">${escHtml(row.bookAuthor)} · <span class="text-[#1E376E] font-medium">${escHtml(row.bookCode)}</span></p>
        <p class="text-[11px] text-slate-400 mt-0.5"><i class="bi bi-tag"></i> ${escHtml(row.category)}</p>
      </td>
      <td class="px-3 py-2.5 align-middle text-slate-700 whitespace-nowrap text-xs">${escHtml(br)}</td>
      <td class="px-3 py-2.5 align-middle">${dueDateCell(row)}</td>
      <td class="px-3 py-2.5 align-middle text-center">${statusBadgeHtml(row.status)}</td>
      <td class="px-3 py-2.5 align-middle whitespace-nowrap">${dendaCell(row)}</td>
      <td class="px-3 py-2.5 align-middle">${actionCell(row)}</td>
    </tr>`;
}

const tbody = document.getElementById('loanTableBody');
const emptyMsg = document.getElementById('loanEmpty');
const searchEl = document.getElementById('loanSearch');
const catEl = document.getElementById('loanCategory');
const editLoanModal = document.getElementById('editLoanModal');
let editingLoanId = null;

function openEditModal(loanId) {
  const row = loans.find((l) => l.id === loanId);
  if (!row) return;
  editingLoanId = loanId;
  document.getElementById('modalMember').value = row.member;
  document.getElementById('modalBookCode').value = row.bookCode;
  document.getElementById('modalBookTitle').value = row.bookTitle;
  document.getElementById('modalPublisher').value = row.publisher;
  document.getElementById('modalAuthor').value = row.bookAuthor;
  document.getElementById('modalCategory').value = row.category;
  document.getElementById('modalCover').src = row.cover;
  document.getElementById('modalCover').alt = row.bookTitle;
  document.getElementById('modalYearPublished').value = row.yearPublished;
  document.getElementById('modalBorrowDate').value = row.borrowIso || parseIsoFromDisplay(row.borrow || '');
  document.getElementById('modalDueDate').value = row.dueIso || parseIsoFromDisplay(row.due || '');
  document.getElementById('modalTelat').value = row.telat || '';
  document.getElementById('modalDenda').value = row.denda && row.denda !== '—' ? row.denda : 'Rp0';
  const statusEl = document.getElementById('modalStatus');
  statusEl.value = (row.status === 'Sudah Lunas' || row.status === 'Dikembalikan')
    ? row.status
    : 'Dikembalikan';
  fbShow('editLoanModal');
  editLoanModal.setAttribute('aria-hidden', 'false');
}

function closeEditModal() {
  editingLoanId = null;
  fbHide('editLoanModal');
  editLoanModal.setAttribute('aria-hidden', 'true');
}

function saveModalToLoan() {
  if (!editingLoanId) return;
  const row = loans.find((l) => l.id === editingLoanId);
  if (!row) return;
  const newStatus = document.getElementById('modalStatus').value;
  row.status = newStatus;
  row.borrowIso = document.getElementById('modalBorrowDate').value;
  row.dueIso = document.getElementById('modalDueDate').value;
  row.borrow = formatShortDate(row.borrowIso);
  row.due = formatShortDate(row.dueIso);
  row.dueNote = '';
  if (newStatus === 'Sudah Lunas') {
    row.telat = '';
    row.denda = 'Rp0';
  } else {
    row.telat = document.getElementById('modalTelat').value || '';
    row.denda = document.getElementById('modalDenda').value || 'Rp0';
  }
  closeEditModal();
  render();
}

function approveLoan(loanId) {
  const row = loans.find((l) => l.id === loanId);
  if (!row || row.status !== 'Mengajukan') return;
  row.status = 'Sedang Dipinjam';
  row.denda = 'Rp0';
  row.dueNote = '7 hari lagi';
  render();
}

function rejectLoan(loanId) {
  const row = loans.find((l) => l.id === loanId);
  if (!row || row.status !== 'Mengajukan') return;
  row.status = 'Ditolak';
  row.denda = '—';
  render();
}

function render() {
  const kw = (searchEl.value || '').toLowerCase().trim();
  const cat = catEl.value;
  const filtered = loans.filter((row) => {
    const haystack = (row.bookTitle + ' ' + row.bookAuthor + ' ' + row.member + ' ' + (row.nim || '')).toLowerCase();
    const matchKw = !kw || haystack.includes(kw);
    const matchCat = cat === 'all' || row.category === cat;
    return matchKw && matchCat;
  });

  tbody.innerHTML = filtered.map((r) => rowHtml(r)).join('');
  if (emptyMsg) emptyMsg.classList.toggle('hidden', filtered.length > 0);
}

tbody.addEventListener('click', (e) => {
  const btn = e.target.closest('.loan-action-btn');
  if (!btn) return;
  const id = btn.getAttribute('data-loan-id');
  const action = btn.getAttribute('data-action');
  if (action === 'edit') openEditModal(id);
  if (action === 'approve') approveLoan(id);
  if (action === 'reject') rejectLoan(id);
});

document.getElementById('modalBtnBack').addEventListener('click', closeEditModal);
document.getElementById('modalBtnSave').addEventListener('click', saveModalToLoan);

editLoanModal.addEventListener('click', (e) => {
  if (e.target === editLoanModal) closeEditModal();
});

searchEl.addEventListener('input', render);
catEl.addEventListener('change', render);
render();
</script>
@endpush
