@extends('layouts.admin')

@section('title', 'Kelola Anggota Admin')
@section('active_page', 'kelola-anggota')
@section('page_title', 'Kelola Anggota')
@section('page_subtitle', 'Kelola data mahasiswa dan anggota perpustakaan.')

@section('content')
<!-- STATS -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Total Pengguna</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['total_pengguna'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Total Mahasiswa</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['total_mahasiswa'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Total Admin</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['total_admin'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Mahasiswa Terdaftar NIM</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['mahasiswa_terdaftar'] }}</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
    <p class="text-xs text-slate-500 font-semibold">Admin Terdaftar NIP</p>
    <p class="text-xl font-bold text-slate-800">{{ $stats['admin_terdaftar'] }}</p>
  </div>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
  <div class="flex flex-col gap-4 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex flex-wrap items-center gap-3">
      <div class="flex items-center gap-2">
        <i class="bi bi-people text-[#1E376E] text-lg"></i>
        <h2 class="font-semibold text-[#1E376E]">Daftar Anggota</h2>
      </div>
      <button type="button" onclick="document.getElementById('tambahAnggotaModal').classList.remove('hidden')"
        class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-600">
        <i class="bi bi-plus-lg"></i> Daftar Anggota Baru
      </button>
    </div>
    
    <form method="GET" action="{{ route('admin.anggota.index') }}" class="relative w-full lg:max-w-xs">
      <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
      <input name="q" value="{{ request('q') }}" type="text" placeholder="Cari Anggota, NIM, atau Tipe..."
        class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
    </form>
  </div>

  <div class="overflow-x-auto px-5 pb-2">
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
      <tbody class="divide-y divide-slate-100">
        @forelse($users as $user)
          @php
            $isAdmin = ($user->role_user ?? $user->role) === \App\Models\User::ROLE_ADMIN;
            $nimNip = $isAdmin ? ($user->nip ? $user->nip : '—') : ($user->nim ? $user->nim : '—');
          @endphp
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-4 py-3 whitespace-nowrap text-slate-700 font-medium">{{ $nimNip }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#1E376E]/10 text-xs font-bold text-[#1E376E]">
                  {{ $user->initials() }}
                </div>
                <span class="font-semibold text-slate-800">{{ $user->nama_pengguna }}</span>
              </div>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
              <span class="rounded px-2 py-0.5 text-xs font-semibold {{ $isAdmin ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                {{ $isAdmin ? 'Admin' : 'Mahasiswa' }}
              </span>
            </td>
            <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
            <td class="px-4 py-3 text-center whitespace-nowrap">
              <span class="rounded bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">Aktif</span>
            </td>
            <td class="px-4 py-3 text-center">
              <div class="flex items-center justify-center gap-1">
                <button type="button" onclick="document.getElementById('editAnggotaModal-{{ $user->id_user }}').classList.remove('hidden')" class="rounded-lg bg-slate-100 p-1.5 text-slate-600 hover:bg-slate-200">
                  <i class="bi bi-pencil"></i>
                </button>
                <button type="button" onclick="document.getElementById('hapusAnggotaModal-{{ $user->id_user }}').classList.remove('hidden')" class="rounded-lg bg-rose-50 p-1.5 text-rose-600 hover:bg-rose-100">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 py-12 text-center text-sm text-slate-500">
              <i class="bi bi-person-x mb-2 block text-3xl text-slate-300"></i>
              Tidak ada anggota yang cocok dengan pencarian.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="flex items-center justify-center border-t border-slate-100 px-5 py-4">
    {{ $users->links() }}
  </div>
</div>

<!-- ==================== MODALS LOOP ==================== -->
@foreach($users as $user)
  @php
    $isAdmin = ($user->role_user ?? $user->role) === \App\Models\User::ROLE_ADMIN;
    $nimNipVal = $isAdmin ? $user->nip : $user->nim;
  @endphp
  <!-- EDIT ANGGOTA MODAL -->
  <div id="editAnggotaModal-{{ $user->id_user }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
    <form method="POST" action="{{ route('admin.anggota.update', $user->id_user) }}" class="w-full max-w-lg rounded-xl border border-slate-200 bg-white p-5 shadow-2xl">
      @csrf
      @method('PUT')
      <h3 class="mb-3 border-b border-slate-200 pb-2 text-sm font-semibold text-slate-700">Edit Informasi Anggota</h3>

      <div class="space-y-3 text-left text-xs">
        <div>
          <label class="mb-1 block font-medium text-slate-600">Nama Lengkap</label>
          <input name="nama" type="text" value="{{ old('nama', $user->nama_pengguna) }}" required class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block font-medium text-slate-600">NIM / NIP</label>
          <input name="nim" type="text" value="{{ old('nim', $nimNipVal) }}" required class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block font-medium text-slate-600">Tipe</label>
          <select name="tipe" class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
            <option value="Mahasiswa" {{ old('tipe', $isAdmin ? 'Admin' : 'Mahasiswa') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            <option value="Admin" {{ old('tipe', $isAdmin ? 'Admin' : 'Mahasiswa') == 'Admin' ? 'selected' : '' }}>Admin</option>
          </select>
        </div>
        <div>
          <label class="mb-1 block font-medium text-slate-600">Email</label>
          <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
        </div>
        <div>
          <label class="mb-1 block font-medium text-slate-600">Kata Sandi Baru (Kosongkan jika tidak diganti)</label>
          <input name="password" type="password" class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800" placeholder="Masukkan kata sandi baru">
        </div>
      </div>

      <div class="mt-5 flex justify-end gap-2 border-t border-slate-100 pt-3">
        <button type="button" onclick="document.getElementById('editAnggotaModal-{{ $user->id_user }}').classList.add('hidden')"
          class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
          Kembali
        </button>
        <button type="submit" class="rounded-lg bg-[#1E376E] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#162d5c]">
          Simpan
        </button>
      </div>
    </form>
  </div>

  <!-- HAPUS ANGGOTA MODAL -->
  <div id="hapusAnggotaModal-{{ $user->id_user }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
    <form method="POST" action="{{ route('admin.anggota.destroy', $user->id_user) }}" class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl text-center">
      @csrf
      @method('DELETE')
      <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
        <i class="bi bi-exclamation-triangle text-2xl"></i>
      </div>
      <h3 class="text-base font-semibold text-slate-800">Hapus Anggota?</h3>
      <p class="mt-1 text-xs text-slate-500">Anggota <span class="font-semibold text-slate-700">"{{ $user->nama_pengguna }}"</span> akan dihapus permanen.</p>

      <div class="mt-5 flex justify-center gap-2">
        <button type="button" onclick="document.getElementById('hapusAnggotaModal-{{ $user->id_user }}').classList.add('hidden')"
          class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
          Batal
        </button>
        <button type="submit" class="rounded-lg bg-rose-500 px-4 py-2 text-xs font-medium text-white hover:bg-rose-600">
          Hapus
        </button>
      </div>
    </form>
  </div>
@endforeach

<!-- TAMBAH ANGGOTA MODAL -->
<div id="tambahAnggotaModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
  <form method="POST" action="{{ route('admin.anggota.store') }}" class="w-full max-w-lg rounded-xl border border-slate-200 bg-white p-5 shadow-2xl">
    @csrf
    <h3 class="mb-3 border-b border-slate-200 pb-2 text-sm font-semibold text-slate-700">Informasi Anggota Baru</h3>

    <div class="space-y-3 text-left text-xs">
      <div>
        <label class="mb-1 block font-medium text-slate-600">Nama Lengkap</label>
        <input name="nama" type="text" required class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">NIM / NIP</label>
        <input name="nim" type="text" required class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Tipe</label>
        <select name="tipe" class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
          <option value="Mahasiswa">Mahasiswa</option>
          <option value="Admin">Admin</option>
        </select>
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Email</label>
        <input name="email" type="email" required class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800">
      </div>
      <div>
        <label class="mb-1 block font-medium text-slate-600">Kata Sandi</label>
        <input name="password" type="password" class="w-full rounded border border-slate-300 px-2 py-1.5 focus:border-blue-500 focus:outline-none text-sm text-slate-800" placeholder="Masukkan kata sandi">
        <p class="mt-1 text-[10px] text-slate-500">Jika kosong, kata sandi otomatis disamakan dengan NIM/NIP.</p>
      </div>
    </div>

    <div class="mt-5 flex justify-end gap-2 border-t border-slate-100 pt-3">
      <button type="button" onclick="document.getElementById('tambahAnggotaModal').classList.add('hidden')"
        class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50">
        Kembali
      </button>
      <button type="submit" class="rounded-lg bg-[#1E376E] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#162d5c]">
        Tambah
      </button>
    </div>
  </form>
</div>
@endsection