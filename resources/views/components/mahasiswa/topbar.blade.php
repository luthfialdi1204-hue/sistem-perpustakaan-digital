@php
    $title = $title ?? 'Halaman Mahasiswa';
    $subtitle = $subtitle ?? '';
@endphp

<div class="mb-6 flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-bold">{{ $title }}</h1>
    @if ($subtitle)
      <p class="text-sm text-slate-500">{{ $subtitle }}</p>
    @endif
  </div>

  <div class="relative">
    <button id="userMenuButton" type="button"
      class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 shadow-sm hover:shadow transition">
      <span class="font-medium text-gray-800">Nama Pengguna</span>
      <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-semibold">NP</div>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <div id="userDropdown"
      class="hidden absolute right-0 mt-2 w-44 rounded-xl border border-slate-200 bg-white py-2 shadow-lg z-50">
      <a href="Profil_Pengguna" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 transition text-sm">Profil</a>
      <a href="Landing_Page" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 transition text-sm text-red-500">Keluar</a>
    </div>
  </div>
</div>
