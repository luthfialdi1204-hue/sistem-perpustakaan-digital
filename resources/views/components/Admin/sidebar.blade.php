@php
  $activePage = $activePage ?? '';
  $linkBase = 'flex items-center gap-2.5 rounded-xl px-4 py-3 text-lg transition-all';
  $linkInactive = $linkBase . ' text-slate-200 hover:bg-white/10 hover:text-white';
  $linkActive = $linkBase . ' bg-gradient-to-br from-brand-light to-teal-600 text-white shadow-[0_4px_12px_rgba(30,55,110,0.35)]';
@endphp

<aside class="fixed left-0 top-0 z-40 h-screen w-64 bg-gradient-to-b from-brand-dark via-brand to-indigo-900 text-white shadow-2xl">
  <div class="border-b border-white/10 p-6 text-center">
    <div class="mb-2 inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-white/15 bg-white/10">
      <img src="{{ asset('images/poltek.png') }}" class="w-11 object-contain" alt="Logo">
    </div>
    <p class="flex items-center justify-center gap-1 text-sm font-medium text-white/90">
      <i class="bi bi-shield-check text-xs text-violet-300"></i> PERRRPUS
    </p>
    <p class="mt-1 text-xs uppercase tracking-wide text-white/50">Panel Admin</p>
  </div>

  <nav class="space-y-1 p-3">
    <a href="{{ route('admin.dashboard') }}" class="{{ $activePage === 'dashboard' ? $linkActive : $linkInactive }}">
      <i class="bi bi-speedometer2 text-lg"></i> Beranda
    </a>
    <a href="{{ route('admin.buku.index') }}" class="{{ $activePage === 'kelola-buku' ? $linkActive : $linkInactive }}">
      <i class="bi bi-book text-lg"></i> Kelola Buku
    </a>
    <a href="{{ route('admin.anggota.index') }}" class="{{ $activePage === 'kelola-anggota' ? $linkActive : $linkInactive }}">
      <i class="bi bi-people text-lg"></i> Kelola Anggota
    </a>
    <a href="{{ route('admin.peminjaman') }}" class="{{ $activePage === 'kelola-peminjaman' ? $linkActive : $linkInactive }}">
      <i class="bi bi-arrow-left-right text-lg"></i> Kelola Peminjaman
    </a>
    <a href="{{ route('admin.laporan') }}" class="{{ $activePage === 'laporan' ? $linkActive : $linkInactive }}">
      <i class="bi bi-bar-chart-line text-lg"></i> Laporan
    </a>
  </nav>
</aside>
