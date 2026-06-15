<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Halaman Mahasiswa')</title>
<script src="https://cdn.tailwindcss.com"></script>
@include('partials.head-theme')
@include('partials.flowbite-assets')
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50/40 to-slate-100 font-sans text-slate-800 antialiased">
@include('components.mahasiswa.sidebar', ['activePage' => trim($__env->yieldContent('active_page'))])

<!-- Backdrop for mobile sidebar -->
<div id="sidebarBackdrop" class="fixed inset-0 z-30 hidden bg-slate-900/40 backdrop-blur-sm lg:hidden"></div>

<div class="lg:ml-64 p-6 md:p-8 min-h-screen">
  @include('components.mahasiswa.topbar', [
      'title' => trim($__env->yieldContent('page_title')) ?: 'Halaman Mahasiswa',
      'subtitle' => trim($__env->yieldContent('page_subtitle')),
  ])
  @if(session('success'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 shadow-sm">
      <div class="flex items-start gap-3">
        <i class="bi bi-check-circle-fill mt-0.5 text-lg"></i>
        <div class="text-sm font-medium">{{ session('success') }}</div>
      </div>
    </div>
  @endif
  @if(session('error'))
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-800 shadow-sm">
      <div class="flex items-start gap-3">
        <i class="bi bi-exclamation-triangle-fill mt-0.5 text-lg"></i>
        <div class="text-sm font-medium">{{ session('error') }}</div>
      </div>
    </div>
  @endif
  @yield('content')
  @include('components.mahasiswa.footer')
</div>

<script>
const yearElement = document.getElementById("year");
if (yearElement) yearElement.textContent = new Date().getFullYear();

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('mahasiswaSidebar');
  const toggleBtn = document.getElementById('sidebarToggle');
  const backdrop = document.getElementById('sidebarBackdrop');
  const closeBtn = document.getElementById('sidebarClose');

  function openSidebar() {
    if (sidebar) sidebar.classList.remove('-translate-x-full');
    if (backdrop) backdrop.classList.remove('hidden');
  }

  function closeSidebar() {
    if (sidebar) sidebar.classList.add('-translate-x-full');
    if (backdrop) backdrop.classList.add('hidden');
  }

  if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
  if (backdrop) backdrop.addEventListener('click', closeSidebar);
  if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
});
</script>
@stack('scripts')
</body>
</html>
