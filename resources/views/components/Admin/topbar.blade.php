@php
    $title = $title ?? 'Halaman Admin';
    $subtitle = $subtitle ?? '';
    $authUser = auth()->user();
    $displayName = $authUser?->nama_pengguna ?? 'Admin';
    $initials = $authUser?->initials() ?? 'AD';
@endphp

<div class="mb-6 flex items-center justify-between gap-4">
  <div class="flex items-center gap-3">
    <button id="sidebarToggle" type="button" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 lg:hidden">
      <i class="bi bi-list text-xl"></i>
    </button>
    <div>
      <h1 class="flex items-center gap-2 text-xl sm:text-2xl font-bold text-brand">
        <i class="bi bi-grid-1x2-fill text-lg sm:text-xl text-teal-600"></i>
        {{ $title }}
      </h1>
      @if ($subtitle)
        <p class="mt-0.5 flex items-center gap-1 text-xs sm:text-sm text-slate-500"><i class="bi bi-info-circle text-slate-400"></i>{{ $subtitle }}</p>
      @endif
    </div>
  </div>

  <button id="dropdownAdminButton" type="button"
    data-dropdown-toggle="dropdownAdmin" data-dropdown-placement="bottom-end"
    class="inline-flex items-center gap-2 rounded-xl border border-slate-200/80 bg-white/90 px-4 py-2 text-sm font-medium text-slate-800 shadow-sm backdrop-blur transition hover:shadow-md">
    <span class="max-w-[140px] truncate">{{ $displayName }}</span>
    @if($authUser && $authUser->foto_profil)
      <img class="h-9 w-9 rounded-full object-cover" src="{{ asset('storage/' . $authUser->foto_profil) }}" alt="{{ $displayName }}">
    @else
      <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-brand to-teal-600 text-xs font-bold text-white">{{ $initials }}</span>
    @endif
    <i class="bi bi-chevron-down text-xs text-slate-400"></i>
  </button>
  <div id="dropdownAdmin" class="z-50 hidden w-48 divide-y divide-gray-100 rounded-xl border border-slate-200 bg-white shadow-xl">
    <ul class="py-1 text-sm text-slate-700">
      <li>
        <a href="{{ route('admin.profil') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50">
          <i class="bi bi-person text-brand"></i> Profil
        </a>
      </li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-rose-600 hover:bg-rose-50">
            <i class="bi bi-box-arrow-left"></i> Keluar
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>
