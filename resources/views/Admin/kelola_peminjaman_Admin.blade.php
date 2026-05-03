@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')
@section('active_page', 'kelola-peminjaman')
@section('page_title', 'Kelola Peminjaman')

@section('content')
<div class="space-y-6">
  <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:gap-4">
    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3v4a1 1 0 001 1h4" />
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
    <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
      <input id="loanSearch" type="search" placeholder="Cari Judul, Pengarang, atau penerbit..."
        class="md:col-span-8 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
      <select id="loanCategory"
        class="md:col-span-4 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        <option value="all">Semua Kategori</option>
        <option value="Fiksi">Fiksi</option>
        <option value="Pendidikan">Pendidikan</option>
        <option value="Bisnis">Bisnis</option>
      </select>
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
            <th class="whitespace-nowrap px-4 py-3 font-semibold">Status</th>
            <th class="whitespace-nowrap px-4 py-3 font-semibold text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="loanTableBody" class="divide-y divide-slate-100 bg-white text-slate-800"></tbody>
      </table>
    </div>
    <p id="loanEmpty" class="hidden px-4 py-8 text-center text-sm text-slate-500">Tidak ada data yang cocok dengan filter.</p>
  </div>
</div>

<div id="editLoanModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 p-4" aria-hidden="true">
  <div class="max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <h3 class="mb-6 text-lg font-semibold text-slate-900">Edit Status Peminjaman</h3>

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
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="Menunggu konfirmasi">Menunggu konfirmasi</option>
            <option value="Dipinjam">Dipinjam</option>
            <option value="Dikembalikan">Dikembalikan</option>
          </select>
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tahun Terbit</label>
          <input id="modalYearPublished" type="text" readonly
            class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Peminjaman</label>
          <input id="modalBorrowDate" type="date"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Kembali</label>
          <input id="modalDueDate" type="date"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Telat</label>
          <input id="modalTelat" type="text"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold text-slate-600">Denda</label>
          <input id="modalDenda" type="text"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
      </div>
    </div>

    <div class="mt-8 flex justify-end gap-2 border-t border-slate-200 pt-4">
      <button type="button" id="modalBtnBack"
        class="rounded-lg bg-slate-500 px-5 py-2 text-sm font-medium text-white hover:bg-slate-600 transition">Kembali</button>
      <button type="button" id="modalBtnSave"
        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">Simpan</button>
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

const loans = [
  {
    id: 'l1',
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
    id: 'l2',
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
    id: 'l3',
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
    status: 'Menunggu konfirmasi',
    cover: 'https://m.media-amazon.com/images/I/81af+MCATTL.jpg',
  },
];

function loanBorrowDisplay(row) {
  return row.borrow || formatDisplayDate(row.borrowIso);
}
function loanDueDisplay(row) {
  return row.due || formatDisplayDate(row.dueIso);
}

function statusBadgeClass(status) {
  if (status === 'Dipinjam') return 'bg-emerald-500 text-white';
  if (status === 'Dikembalikan') return 'bg-orange-500 text-white';
  if (status === 'Menunggu konfirmasi') return 'bg-emerald-200 text-emerald-900';
  if (status === 'Ditolak') return 'bg-red-500 text-white';
  return 'bg-slate-200 text-slate-800';
}

function memberInitials(name) {
  const parts = name.trim().split(/\s+/);
  if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
  return name.slice(0, 2).toUpperCase();
}

function actionCell(row) {
  if (row.status === 'Menunggu konfirmasi') {
    return `
      <div class="flex items-center justify-center gap-2">
        <button type="button" title="Setujui" data-action="approve" data-loan-id="${row.id}"
          class="loan-action-btn inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </button>
        <button type="button" title="Tolak" data-action="reject" data-loan-id="${row.id}"
          class="loan-action-btn inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>`;
  }
  if (row.status === 'Ditolak') {
    return `
      <div class="flex flex-col items-center justify-center gap-1 text-red-600" title="Peminjaman ditolak">
        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-600 ring-1 ring-red-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </span>
        <span class="text-xs font-semibold">Ditolak</span>
      </div>`;
  }
  return `
    <div class="flex justify-center">
      <button type="button" title="Edit" data-action="edit" data-loan-id="${row.id}"
        class="loan-action-btn inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
      </button>
    </div>`;
}

function rowHtml(row) {
  const initials = memberInitials(row.member);
  const br = loanBorrowDisplay(row);
  const du = loanDueDisplay(row);
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
      <td class="px-4 py-3">
        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${statusBadgeClass(row.status)}">${row.status}</span>
      </td>
      <td class="px-4 py-3">${actionCell(row)}</td>
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
  document.getElementById('modalStatus').value = row.status;
  document.getElementById('modalYearPublished').value = row.yearPublished;
  document.getElementById('modalBorrowDate').value = row.borrowIso || parseIsoFromDisplay(row.borrow || '');
  document.getElementById('modalDueDate').value = row.dueIso || parseIsoFromDisplay(row.due || '');
  document.getElementById('modalTelat').value = row.telat;
  document.getElementById('modalDenda').value = row.denda;
  editLoanModal.classList.remove('hidden');
  editLoanModal.classList.add('flex');
  editLoanModal.setAttribute('aria-hidden', 'false');
}

function closeEditModal() {
  editingLoanId = null;
  editLoanModal.classList.add('hidden');
  editLoanModal.classList.remove('flex');
  editLoanModal.setAttribute('aria-hidden', 'true');
}

function saveModalToLoan() {
  if (!editingLoanId) return;
  const row = loans.find((l) => l.id === editingLoanId);
  if (!row) return;
  row.status = document.getElementById('modalStatus').value;
  row.borrowIso = document.getElementById('modalBorrowDate').value;
  row.dueIso = document.getElementById('modalDueDate').value;
  row.borrow = formatDisplayDate(row.borrowIso);
  row.due = formatDisplayDate(row.dueIso);
  row.telat = document.getElementById('modalTelat').value;
  row.denda = document.getElementById('modalDenda').value;
  closeEditModal();
  render();
}

function approveLoan(loanId) {
  const row = loans.find((l) => l.id === loanId);
  if (!row || row.status !== 'Menunggu konfirmasi') return;
  row.status = 'Dipinjam';
  render();
}

function rejectLoan(loanId) {
  const row = loans.find((l) => l.id === loanId);
  if (!row || row.status !== 'Menunggu konfirmasi') return;
  row.status = 'Ditolak';
  render();
}

function render() {
  const kw = (searchEl.value || '').toLowerCase().trim();
  const cat = catEl.value;
  const filtered = loans.filter((row) => {
    const haystack = (row.bookTitle + ' ' + row.bookAuthor + ' ' + row.member).toLowerCase();
    const matchKw = !kw || haystack.includes(kw);
    const matchCat = cat === 'all' || row.category === cat;
    return matchKw && matchCat;
  });

  tbody.innerHTML = filtered.map((r) => rowHtml(r)).join('');
  if (filtered.length === 0) {
    emptyMsg.classList.remove('hidden');
  } else {
    emptyMsg.classList.add('hidden');
  }
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
