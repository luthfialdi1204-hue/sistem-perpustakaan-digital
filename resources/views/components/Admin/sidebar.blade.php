@php
    $activePage = $activePage ?? '';
@endphp

<aside class="fixed left-0 top-0 h-screen w-64 bg-blue-900 text-white shadow-xl">
  <div class="border-b border-slate-700 p-6 text-center">
    <img src="{{ asset('images/poltek.png') }}" class="mx-auto w-16" alt="Logo Polibatam">
    <p class="mt-3 text-sm text-slate-200">Perpustakaan Digital</p>
    <p class="mt-1 text-xs uppercase tracking-wide text-slate-400">Panel Admin</p>
  </div>

  <nav class="space-y-2 p-3">
    <a href="Dashboard_Admin"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'dashboard' ? 'bg-blue-600 font-medium text-white' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Beranda
    </a>

    <a href="Kelola_Buku"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'kelola-buku' ? 'bg-blue-600 font-medium text-white' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Kelola Buku
    </a>

    <a href="Kelola_Anggota"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'kelola-anggota' ? 'bg-blue-600 font-medium text-white' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Kelola Anggota
    </a>

    <a href="/Kelola_Peminjaman"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'kelola-peminjaman' ? 'bg-blue-600 font-medium text-white' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Kelola Peminjaman
    </a>

    <a href="/Laporan_Admin"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'laporan' ? 'bg-blue-600 font-medium text-white' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Laporan
    </a>
  </nav>
</aside>
