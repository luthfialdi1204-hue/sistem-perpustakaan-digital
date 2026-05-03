@extends('layouts.admin')

@section('title', 'Kelola Anggota Admin')
@section('active_page', 'kelola-anggota')
@section('page_title', 'Kelola Anggota')

@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
  <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div class="flex items-center gap-2">
      <h2 class="text-lg font-semibold text-slate-800">Daftar Anggota</h2>
      <button onclick="openTambahAnggotaModal()" class="inline-flex items-center gap-1 rounded-lg bg-emerald-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-600 transition">
        <span>+</span>
        <span>Daftar Anggota Baru</span>
      </button>
    </div>

    <div class="relative w-full md:w-72">
      <input id="searchInput" type="text" placeholder="Cari Anggota, NIM, atau Tipe..."
        class="w-full rounded-lg border border-slate-300 py-2 pl-3 pr-10 text-xs focus:border-blue-500 focus:outline-none">
      <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </span>
    </div>
  </div>

  <div class="overflow-x-auto rounded-xl border border-slate-200">
    <table class="min-w-full text-xs">
      <thead class="bg-slate-100 text-slate-700">
        <tr>
          <th class="px-3 py-2 text-left font-semibold">Nim</th>
          <th class="px-3 py-2 text-left font-semibold">Nama Lengkap</th>
          <th class="px-3 py-2 text-left font-semibold">Tipe</th>
          <th class="px-3 py-2 text-left font-semibold">Email</th>
          <th class="px-3 py-2 text-left font-semibold">Status</th>
          <th class="px-3 py-2 text-center font-semibold">Aksi</th>
        </tr>
      </thead>
      <tbody id="anggotaTableBody" class="divide-y divide-slate-200 bg-white"></tbody>
    </table>
  </div>

  <div id="paginationContainer" class="mt-4 flex items-center justify-center gap-1 text-xs"></div>
</div>

<div id="tambahAnggotaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
  <div class="w-full max-w-lg rounded-xl border border-slate-200 bg-white p-4 shadow-2xl">
    <h3 id="anggotaModalTitle" class="mb-3 border-b border-slate-200 pb-2 text-sm font-semibold text-slate-700">Informasi Anggota</h3>

    <div class="space-y-2 text-xs">
      <div>
        <label class="mb-1 block font-medium text-slate-600">Nama Lengkap</label>
        <input id="newNama" type="text" value="Luthfi Dwi Apriyadi"
          class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none">
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Nim</label>
        <input id="newNim" type="text" value="3312501077"
          class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none">
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Tipe</label>
        <select id="newTipe" class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none">
          <option>Mahasiswa</option>
          <option>Admin</option>
        </select>
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Email</label>
        <input id="newEmail" type="email" value="luthfiwidi204@gmail.com"
          class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none">
      </div>
    </div>

    <div class="mt-4 flex justify-end gap-2">
      <button onclick="closeTambahAnggotaModal()"
        class="rounded bg-slate-500 px-3 py-1 text-xs text-white hover:bg-slate-600">
        Kembali
      </button>
      <button id="anggotaSubmitButton" onclick="submitAnggotaForm()"
        class="rounded bg-violet-400 px-3 py-1 text-xs text-white hover:bg-violet-500">
        Tambah
      </button>
    </div>
  </div>
</div>

<div id="hapusAnggotaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
  <div class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
      </svg>
    </div>
    <h3 class="text-center text-base font-semibold text-slate-800">Hapus Anggota?</h3>
    <p class="mt-1 text-center text-xs text-slate-500">Data yang dihapus tidak dapat dikembalikan.</p>

    <div class="mt-5 flex justify-center gap-2">
      <button onclick="closeHapusAnggotaModal()"
        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
        Batal
      </button>
      <button onclick="confirmDeleteAnggota()"
        class="rounded-lg bg-rose-500 px-4 py-2 text-xs font-medium text-white hover:bg-rose-600">
        Hapus
      </button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const anggotaData = [
  { nim: "3312501077", nama: "Luthfi Dwi Apriyadi", tipe: "Mahasiswa", email: "luthfiwidi204@gmail.com", status: "Aktif" },
  { nim: "3312601082", nama: "Cristh Velato Arioranga", tipe: "Mahasiswa", email: "crishtvelato@gmail.com", status: "Aktif" },
  { nim: "3312501085", nama: "Muhammad Zaky Sadewa", tipe: "Mahasiswa", email: "sdwevcoe44@gmail.com", status: "Aktif" },
  { nim: "3312501090", nama: "Andi Pratama", tipe: "Mahasiswa", email: "andi.pratama@gmail.com", status: "Aktif" },
  { nim: "3312501091", nama: "Salsa Putri", tipe: "Mahasiswa", email: "salsaputri@gmail.com", status: "Aktif" },
  { nim: "3312501092", nama: "Rafi Maulana", tipe: "Mahasiswa", email: "rafimaulana@gmail.com", status: "Aktif" },
  { nim: "3312501093", nama: "Nadia Rahma", tipe: "Mahasiswa", email: "nadiarahma@gmail.com", status: "Aktif" },
  { nim: "3312501094", nama: "Ihsan Fikri", tipe: "Mahasiswa", email: "ihsanfikri@gmail.com", status: "Aktif" },
  { nim: "3312501095", nama: "Putri Ayu", tipe: "Mahasiswa", email: "putriayu@gmail.com", status: "Aktif" },
  { nim: "3312501096", nama: "Dimas Saputra", tipe: "Mahasiswa", email: "dimassaputra@gmail.com", status: "Aktif" }
];

const rowsPerPage = 8;
let currentPage = 1;
let filteredData = [...anggotaData];
let editTargetNim = null;
let pendingDeleteNim = null;

const tbody = document.getElementById("anggotaTableBody");
const paginationContainer = document.getElementById("paginationContainer");
const searchInput = document.getElementById("searchInput");
const tambahAnggotaModal = document.getElementById("tambahAnggotaModal");
const hapusAnggotaModal = document.getElementById("hapusAnggotaModal");

function renderTable() {
  tbody.innerHTML = "";
  const start = (currentPage - 1) * rowsPerPage;
  const currentRows = filteredData.slice(start, start + rowsPerPage);

  currentRows.forEach((item) => {
    tbody.innerHTML += `
      <tr>
        <td class="px-3 py-2">${item.nim}</td>
        <td class="px-3 py-2">${item.nama}</td>
        <td class="px-3 py-2">${item.tipe}</td>
        <td class="px-3 py-2 underline decoration-slate-300">${item.email}</td>
        <td class="px-3 py-2">
          <span class="rounded bg-emerald-500 px-2 py-0.5 text-[10px] font-semibold text-white">${item.status}</span>
        </td>
        <td class="px-3 py-2">
          <div class="flex items-center justify-center gap-1">
            <button onclick="openEditAnggotaModal('${item.nim}')" class="rounded bg-blue-600 p-1 text-white hover:bg-blue-700" title="Edit">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L12 15l-4 1 1-4 8.586-8.586z" />
              </svg>
            </button>
            <button onclick="deleteAnggota('${item.nim}')" class="rounded bg-rose-500 p-1 text-white hover:bg-rose-600" title="Hapus">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a1 1 0 011-1h4a1 1 0 011 1v2" />
              </svg>
            </button>
          </div>
        </td>
      </tr>
    `;
  });

  renderPagination();
}

function renderPagination() {
  const totalPages = Math.ceil(filteredData.length / rowsPerPage) || 1;
  paginationContainer.innerHTML = "";

  paginationContainer.innerHTML += `
    <button onclick="setPage(${Math.max(1, currentPage - 1)})"
      class="px-2 py-1 text-slate-500 hover:text-slate-700">Previous</button>
  `;

  for (let i = 1; i <= totalPages; i++) {
    paginationContainer.innerHTML += `
      <button onclick="setPage(${i})"
        class="rounded px-2 py-1 ${i === currentPage ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'}">${i}</button>
    `;
  }

  paginationContainer.innerHTML += `
    <button onclick="setPage(${Math.min(totalPages, currentPage + 1)})"
      class="px-2 py-1 text-slate-500 hover:text-slate-700">Next</button>
  `;
}

function setPage(page) {
  const totalPages = Math.ceil(filteredData.length / rowsPerPage) || 1;
  if (page < 1 || page > totalPages) return;
  currentPage = page;
  renderTable();
}

function filterAnggota() {
  const keyword = searchInput.value.toLowerCase();
  filteredData = anggotaData.filter((item) =>
    item.nim.toLowerCase().includes(keyword) ||
    item.nama.toLowerCase().includes(keyword) ||
    item.tipe.toLowerCase().includes(keyword)
  );
  currentPage = 1;
  renderTable();
}

function openTambahAnggotaModal() {
  editTargetNim = null;
  document.getElementById("anggotaModalTitle").textContent = "Informasi Anggota";
  document.getElementById("anggotaSubmitButton").textContent = "Tambah";
  document.getElementById("newNama").value = "";
  document.getElementById("newNim").value = "";
  document.getElementById("newTipe").value = "Mahasiswa";
  document.getElementById("newEmail").value = "";

  tambahAnggotaModal.classList.remove("hidden");
  tambahAnggotaModal.classList.add("flex");
}

function closeTambahAnggotaModal() {
  tambahAnggotaModal.classList.remove("flex");
  tambahAnggotaModal.classList.add("hidden");
}

function submitAnggotaForm() {
  const nama = document.getElementById("newNama").value.trim();
  const nim = document.getElementById("newNim").value.trim();
  const tipe = document.getElementById("newTipe").value;
  const email = document.getElementById("newEmail").value.trim();

  if (!nama || !nim || !email) return;

  if (editTargetNim) {
    const index = anggotaData.findIndex((item) => item.nim === editTargetNim);
    if (index !== -1) {
      anggotaData[index] = { ...anggotaData[index], nim, nama, tipe, email };
    }
  } else {
    anggotaData.unshift({ nim, nama, tipe, email, status: "Aktif" });
  }

  filterAnggota();
  closeTambahAnggotaModal();
}

function openEditAnggotaModal(nim) {
  const data = anggotaData.find((item) => item.nim === nim);
  if (!data) return;

  editTargetNim = nim;
  document.getElementById("anggotaModalTitle").textContent = "Informasi Anggota";
  document.getElementById("anggotaSubmitButton").textContent = "Simpan";
  document.getElementById("newNama").value = data.nama;
  document.getElementById("newNim").value = data.nim;
  document.getElementById("newTipe").value = data.tipe;
  document.getElementById("newEmail").value = data.email;

  tambahAnggotaModal.classList.remove("hidden");
  tambahAnggotaModal.classList.add("flex");
}

function deleteAnggota(nim) {
  pendingDeleteNim = nim;
  hapusAnggotaModal.classList.remove("hidden");
  hapusAnggotaModal.classList.add("flex");
}

function closeHapusAnggotaModal() {
  pendingDeleteNim = null;
  hapusAnggotaModal.classList.remove("flex");
  hapusAnggotaModal.classList.add("hidden");
}

function confirmDeleteAnggota() {
  if (!pendingDeleteNim) return;
  const index = anggotaData.findIndex((item) => item.nim === pendingDeleteNim);
  if (index !== -1) {
    anggotaData.splice(index, 1);
    filterAnggota();
  }
  closeHapusAnggotaModal();
}

searchInput.addEventListener("keyup", filterAnggota);
renderTable();
</script>
@endpush
