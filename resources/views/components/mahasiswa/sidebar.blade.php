@php
    $activePage = $activePage ?? '';
@endphp

<div class="fixed top-0 left-0 h-screen w-64 bg-blue-900 text-white shadow-xl">
  <div class="border-b border-slate-700 text-center p-6">
    <img src="{{ asset('images/poltek.png') }}" class="w-16 mx-auto" alt="Logo Polibatam">
    <p class="mt-3 text-sm text-slate-200">Perpustakaan Digital</p>
  </div>

  <div class="p-3 space-y-2">
    <a href="Beranda_Mahasiswa"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'beranda' ? 'bg-blue-600 font-medium' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Beranda
    </a>
    <a href="Katalog_Buku"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'katalog' ? 'bg-blue-600 font-medium' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Katalog Buku
    </a>
    <a href="Riwayat_Peminjaman"
      class="block rounded-lg px-4 py-3 {{ $activePage === 'riwayat' ? 'bg-blue-600 font-medium' : 'text-slate-200 hover:bg-slate-800 transition' }}">
      Riwayat Peminjaman
    </a>
  </div>
</div>
