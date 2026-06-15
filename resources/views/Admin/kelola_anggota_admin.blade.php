@extends('layouts.admin')

@section('title', 'Kelola Anggota Admin')
@section('active_page', 'kelola-anggota')
@section('page_title', 'Kelola Anggota')
@section('page_subtitle', 'Kelola data mahasiswa dan anggota perpustakaan.')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
  <div class="flex flex-col gap-4 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex flex-wrap items-center gap-3">
      <div class="flex items-center gap-2">
        <i class="bi bi-people text-[#1E376E] text-lg"></i>
        <h2 class="font-semibold text-[#1E376E]">Daftar Anggota</h2>
      </div>
      <button type="button" onclick="openTambahAnggotaModal()"
        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-600">
        <i class="bi bi-plus-lg"></i> Daftar Anggota Baru
      </button>
    </div>
    <div class="relative w-full lg:max-w-xs">
      <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
      <input id="searchInput" type="text" placeholder="Cari Anggota, NIM, atau Tipe..."
        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
    </div>
  </div>

  <div class="overflow-x-auto px-5 pb-2">
    <div id="anggota-empty" class="hidden rounded-xl border border-dashed border-slate-200 bg-slate-50 py-12 text-center text-sm text-slate-500">
      <i class="bi bi-person-x mb-2 block text-3xl text-slate-300"></i>
      Tidak ada anggota yang cocok dengan pencarian.
    </div>
    <table class="w-full min-w-[800px] text-sm table-auto">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
          <th class="px-4 py-3 whitespace-nowrap">NIM/NIP</th>
          <th class="px-4 py-3">Nama Lengkap</th>
          <th class="px-4 py-3 whitespace-nowrap">Tipe</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3 text-center whitespace-nowrap">Status</th>
          <th class="px-4 py-3 text-center w-[90px]">Aksi</th>
        </tr>
      </thead>
      <tbody id="anggotaTableBody" class="divide-y divide-slate-100"></tbody>
    </table>
  </div>

  <div id="paginationContainer" class="flex items-center justify-center gap-1 border-t border-slate-100 px-5 py-4 text-sm"></div>
</div>

<div id="tambahAnggotaModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="w-full max-w-lg rounded-xl border border-slate-200 bg-white p-4 shadow-2xl">
    <h3 id="anggotaModalTitle" class="mb-3 border-b border-slate-200 pb-2 text-sm font-semibold text-slate-700">Informasi Anggota</h3>

    <div class="space-y-2 text-xs">
      <div>
        <label class="mb-1 block font-medium text-slate-600">Nama Lengkap</label>
        <input id="newNama" type="text" value=""
          class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none">
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Nim</label>
        <input id="newNim" type="text" value=""
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
        <input id="newEmail" type="email" value=""
          class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none">
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Kata Sandi</label>
        <input id="newPassword" type="password" value=""
          class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none"
          placeholder="Masukkan kata sandi">
        <p class="mt-1 text-[11px] text-slate-500">Jika kosong, kata sandi akan otomatis menggunakan NIM/NIP.</p>
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

<div id="hapusAnggotaModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
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
window.KelolaAnggotaCfg = {
  csrf: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
  listUrl: @json(route('admin.anggota.list')),
  showUrl: (id) => @json(url('/admin/anggota')) + '/' + encodeURIComponent(id),
  storeUrl: @json(route('admin.anggota.store')),
  perPage: 8,
};
</script>
<script src="{{ asset('js/admin/kelola-anggota.js') }}?v={{ filemtime(public_path('js/admin/kelola-anggota.js')) }}"></script>
@endpush