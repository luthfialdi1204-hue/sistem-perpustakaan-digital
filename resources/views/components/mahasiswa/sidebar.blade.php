@php
  $activePage = $activePage ?? '';
  $linkBase = 'flex items-center gap-2.5 rounded-xl px-4 py-3 text-lg transition-all';
  $linkInactive = $linkBase . ' text-slate-200 hover:bg-white/10 hover:text-white';
  $linkActive = $linkBase . ' bg-gradient-to-br from-brand-light to-teal-600 text-white shadow-[0_4px_12px_rgba(30,55,110,0.35)]';
@endphp

<aside id="mahasiswaSidebar" class="fixed top-0 left-0 z-40 h-screen w-64 -translate-x-full bg-gradient-to-b from-brand-dark via-brand to-[#0f4c5c] text-white shadow-2xl transition-transform lg:translate-x-0">
  <div class="border-b border-white/10 p-6 text-center relative">
    <button id="sidebarClose" type="button" class="absolute right-4 top-4 text-white/70 hover:text-white lg:hidden">
      <i class="bi bi-x-lg text-lg"></i>
    </button>
    <div class="mb-2 inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-white/15 bg-white/10">
      <img src="{{ asset('images/poltek.png') }}" class="w-11 object-contain" alt="Logo">
    </div>
    <p class="flex items-center justify-center gap-1 text-sm font-medium text-white/90">
      <i class="bi bi-book-half text-xs text-amber-400"></i> PERRRPUS
    </p>
    <p class="mt-1 text-xs text-white/50">Panel Mahasiswa</p>
  </div>

  <nav class="space-y-1 p-3">
    <a href="{{ route('mahasiswa.beranda') }}" class="{{ $activePage === 'beranda' ? $linkActive : $linkInactive }}">
      <i class="bi bi-house-door text-lg"></i> Beranda
    </a>
    <a href="{{ route('mahasiswa.katalog') }}" class="{{ $activePage === 'katalog' ? $linkActive : $linkInactive }}">
      <i class="bi bi-journal-bookmark text-lg"></i> Katalog Buku
    </a>
    <a href="{{ route('mahasiswa.riwayat') }}" class="{{ $activePage === 'riwayat' ? $linkActive : $linkInactive }}">
      <i class="bi bi-clock-history text-lg"></i> Riwayat Peminjaman
    </a>
  </nav>
</aside>
