@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('active_page', 'dashboard')
@section('page_title', 'Beranda')

@section('content')

<!-- WELCOME -->
<div class="mb-6 rounded-2xl bg-gradient-to-br from-brand via-brand-light to-teal-600 p-6 text-white shadow-lg">
  @php
    $userName = auth()->user()?->nama_pengguna ?? 'Admin';
    $todayId = now()->locale('id')->translatedFormat('l, d F Y');
  @endphp
  <h2 class="mb-1 text-xl font-semibold">Selamat Datang, {{ $userName }}</h2>
  <p class="text-sm text-blue-100">{{ $todayId }}</p> 
</div>

<!-- RINGKASAN STATISTIK -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#1E376E] ring-4 ring-[#1E376E]/15">
      <i class="bi bi-book text-white text-xl"></i>
    </div>
    <div class="min-w-0">
      <p class="text-sm text-slate-500">Total Buku</p>
      <p class="text-2xl font-bold text-slate-800">{{ $stats['total_buku'] ?? 0 }}</p>
      <p class="mt-0.5 text-xs text-slate-500">Koleksi perpustakaan</p>
    </div>
  </div>

  <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-blue-600 ring-4 ring-blue-600/15">
      <i class="bi bi-people text-white text-xl"></i>
    </div>
    <div class="min-w-0">
      <p class="text-sm text-slate-500">Total Anggota</p>
      <p class="text-2xl font-bold text-slate-800">{{ $stats['total_anggota'] ?? 0 }}</p>
      <p class="mt-0.5 text-xs text-slate-500">Mahasiswa terdaftar</p>
    </div>
  </div>

  <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-teal-600 ring-4 ring-teal-600/15">
      <i class="bi bi-journal-arrow-up text-white text-xl"></i>
    </div>
    <div class="min-w-0">
      <p class="text-sm text-slate-500">Buku Dipinjam</p>
      <p class="text-2xl font-bold text-slate-800">{{ $stats['buku_dipinjam'] ?? 0 }}</p>
      <p class="mt-0.5 text-xs text-emerald-600 flex items-center gap-0.5">
        <i class="bi bi-arrow-up-short text-base"></i> Aktif saat ini
      </p>
    </div>
  </div>

  <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-500 ring-4 ring-amber-500/15">
      <i class="bi bi-activity text-white text-xl"></i>
    </div>
    <div class="min-w-0">
      <p class="text-sm text-slate-500">Transaksi Hari Ini</p>
      <p class="text-2xl font-bold text-slate-800">{{ $stats['transaksi_hari_ini'] ?? 0 }}</p>
      <p class="mt-0.5 text-xs text-slate-500">Pinjam &amp; kembali</p>
    </div>
  </div>
</div>

<!-- DAFTAR RIWAYAT PEMINJAMAN -->
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
  <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 via-[#1E376E]/5 to-teal-500/10 px-5 py-4">
    <i class="bi bi-journal-text text-[#1E376E] text-lg"></i>
    <h3 class="font-semibold text-[#1E376E]">Daftar Riwayat Peminjaman</h3>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm table-auto">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
          <th class="px-3 py-2">Anggota</th>
          <th class="px-3 py-2 w-[70px]">Buku</th>
          <th class="px-3 py-2">Judul</th>
          <th class="px-3 py-2 whitespace-nowrap">Tgl Pinjam</th>
          <th class="px-3 py-2 whitespace-nowrap">Tgl Kembali</th>
          <th class="px-3 py-2 text-center whitespace-nowrap">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @php
          $statusBadges = [
            'Mengajukan' => ['bg' => 'bg-violet-500', 'icon' => 'bi-send-fill', 'label' => 'Mengajukan'],
            'Sedang Dipinjam' => ['bg' => 'bg-sky-500', 'icon' => 'bi-book-fill', 'label' => 'Dipinjam'],
            'Terlambat' => ['bg' => 'bg-red-500', 'icon' => 'bi-exclamation-circle-fill', 'label' => 'Terlambat'],
            'Dikembalikan' => ['bg' => 'bg-emerald-500', 'icon' => 'bi-check-circle-fill', 'label' => 'Dikembalikan'],
            'Sudah Lunas' => ['bg' => 'bg-teal-600', 'icon' => 'bi-cash-coin', 'label' => 'Sudah Lunas'],
            'Ditolak' => ['bg' => 'bg-rose-500', 'icon' => 'bi-x-circle-fill', 'label' => 'Ditolak'],
            'Dibatalkan' => ['bg' => 'bg-slate-500', 'icon' => 'bi-slash-circle', 'label' => 'Dibatalkan'],
          ];
        @endphp

        @forelse(($recentLoans ?? []) as $row)
          @php
            $badge = $statusBadges[$row['status'] ?? ''] ?? null;
            $borrow = !empty($row['borrowIso']) ? \Carbon\Carbon::parse($row['borrowIso'])->locale('id')->translatedFormat('d M Y') : '—';
            $due = !empty($row['dueIso']) ? \Carbon\Carbon::parse($row['dueIso'])->locale('id')->translatedFormat('d M Y') : '—';
            $cover = $row['cover'] ?? '';
          @endphp
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-3 py-2.5 align-middle">
              <div class="flex items-center gap-2 min-w-[140px]">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-900/10 text-indigo-900 text-xs font-bold">
                  {{ $row['initials'] ?? '—' }}
                </div>
                <div>
                  <p class="font-semibold text-slate-800 leading-tight">{{ $row['member'] ?? '—' }}</p>
                  <p class="text-[11px] text-slate-500">{{ $row['nim'] ?? '—' }}</p>
                </div>
              </div>
            </td>
            <td class="px-3 py-2.5 align-middle">
              @if($cover)
                <img src="{{ $cover }}" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
              @else
                <div class="flex h-16 w-12 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-300">
                  <i class="bi bi-image text-lg"></i>
                </div>
              @endif
            </td>
            <td class="px-3 py-2.5 align-middle">
              <p class="font-semibold text-slate-800">{{ $row['bookTitle'] ?? '—' }}</p>
              <p class="text-xs text-slate-500">{{ $row['bookAuthor'] ?? '—' }}</p>
            </td>
            <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">{{ $borrow }}</td>
            <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">{{ $due }}</td>
            <td class="px-3 py-2.5 align-middle text-center">
              @if($badge)
                <span class="inline-flex items-center gap-1 rounded-md {{ $badge['bg'] }} px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap">
                  <i class="bi {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                </span>
              @else
                <span class="inline-flex items-center rounded-md bg-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-700 whitespace-nowrap">
                  {{ $row['status'] ?? '—' }}
                </span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
              Belum ada riwayat peminjaman.
            </td>
          </tr>
        @endforelse

      </tbody>
    </table>
  </div>
</div>
@endsection
