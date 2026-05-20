@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('active_page', 'dashboard')
@section('page_title', 'Beranda')

@section('content')

<!-- WELCOME -->
<div class="mb-6 rounded-2xl bg-gradient-to-br from-brand via-brand-light to-teal-600 p-6 text-white shadow-lg">
  @php
    $userName = auth()->user()->name ?? 'Admin';
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

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2 min-w-[140px]">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#1E376E]/10 text-[#1E376E] text-xs font-bold">LA</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Luthfi Dwi Apriyaldi</p>
                <p class="text-[11px] text-slate-500">3312501077</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/81af+MCATTL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Tentang Kamu</p>
            <p class="text-xs text-slate-500">Tere Liye</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">23 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">30 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-book-fill"></i> Dipinjam</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-500/10 text-teal-700 text-xs font-bold">MZ</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Muhammad Zaky Sadewa</p>
                <p class="text-[11px] text-slate-500">3312501088</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Atomic Habits</p>
            <p class="text-xs text-slate-500">James Clear</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">21 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">26 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-check-circle-fill"></i> Dikembalikan</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-violet-500/10 text-violet-700 text-xs font-bold">AR</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Aulia Rahmawati</p>
                <p class="text-[11px] text-slate-500">3312501092</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Filosofi Teras</p>
            <p class="text-xs text-slate-500">Henry Manampiring</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">20 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">27 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-red-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-exclamation-circle-fill"></i> Terlambat</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-500/10 text-amber-700 text-xs font-bold">DP</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Dewi Putri Lestari</p>
                <p class="text-[11px] text-slate-500">3312501101</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Rich Dad Poor Dad</p>
            <p class="text-xs text-slate-500">Robert Kiyosaki</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">28 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">4 Apr 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-violet-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-send-fill"></i> Mengajukan</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-rose-500/10 text-rose-700 text-xs font-bold">RF</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Rizky Fadillah</p>
                <p class="text-[11px] text-slate-500">3312501115</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Bumi</p>
            <p class="text-xs text-slate-500">Tere Liye</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">25 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">1 Apr 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-book-fill"></i> Dipinjam</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-700 text-xs font-bold">SN</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Siti Nurhaliza</p>
                <p class="text-[11px] text-slate-500">3312501120</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/81af+MCATTL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Laut Bercerita</p>
            <p class="text-xs text-slate-500">Leila S. Chudori</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">18 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">25 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-check-circle-fill"></i> Dikembalikan</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-cyan-500/10 text-cyan-700 text-xs font-bold">AP</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Andi Pratama</p>
                <p class="text-[11px] text-slate-500">3312501133</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/71g2ednj0JL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Psikologi Uang</p>
            <p class="text-xs text-slate-500">Morgan Housel</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">27 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">3 Apr 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-book-fill"></i> Dipinjam</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pink-500/10 text-pink-700 text-xs font-bold">NF</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Nadia Fitriani</p>
                <p class="text-[11px] text-slate-500">3312501144</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://images-na.ssl-images-amazon.com/images/I/81p1L85KinL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Deep Work</p>
            <p class="text-xs text-slate-500">Cal Newport</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">26 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">2 Apr 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-book-fill"></i> Dipinjam</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-lime-600/10 text-lime-700 text-xs font-bold">BS</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Budi Santoso</p>
                <p class="text-[11px] text-slate-500">3312501155</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Atomic Habits</p>
            <p class="text-xs text-slate-500">James Clear</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">22 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">29 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-check-circle-fill"></i> Dikembalikan</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange-500/10 text-orange-700 text-xs font-bold">FA</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Fajar Akbar</p>
                <p class="text-[11px] text-slate-500">3312501166</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/81af+MCATTL.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Tentang Kamu</p>
            <p class="text-xs text-slate-500">Tere Liye</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">19 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">26 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-red-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-exclamation-circle-fill"></i> Terlambat</span>
          </td>
        </tr>

        <tr class="hover:bg-slate-50/80 transition-colors">
          <td class="px-3 py-2.5 align-middle">
            <div class="flex items-center gap-2">
              <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-500/10 text-slate-700 text-xs font-bold">IK</div>
              <div>
                <p class="font-semibold text-slate-800 leading-tight">Indah Kartika</p>
                <p class="text-[11px] text-slate-500">3312501177</p>
              </div>
            </div>
          </td>
          <td class="px-3 py-2.5 align-middle">
            <img src="https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
          </td>
          <td class="px-3 py-2.5 align-middle">
            <p class="font-semibold text-slate-800">Filosofi Teras</p>
            <p class="text-xs text-slate-500">Henry Manampiring</p>
          </td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">29 Mar 2026</td>
          <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">5 Apr 2026</td>
          <td class="px-3 py-2.5 align-middle text-center">
            <span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white whitespace-nowrap"><i class="bi bi-book-fill"></i> Dipinjam</span>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>
@endsection
