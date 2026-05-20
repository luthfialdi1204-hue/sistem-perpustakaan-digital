<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Halaman Mahasiswa')</title>
<script src="https://cdn.tailwindcss.com"></script>
@include('partials.head-theme')
@include('partials.flowbite-assets')
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-blue-50/40 to-slate-100 font-sans text-slate-800 antialiased">
@include('components.mahasiswa.sidebar', ['activePage' => trim($__env->yieldContent('active_page'))])

<div class="ml-64 p-6 md:p-8 min-h-screen">
  @include('components.mahasiswa.topbar', [
      'title' => trim($__env->yieldContent('page_title')) ?: 'Halaman Mahasiswa',
      'subtitle' => trim($__env->yieldContent('page_subtitle')),
  ])
  @yield('content')
  @include('components.mahasiswa.footer')
</div>

<script>
const yearElement = document.getElementById("year");
if (yearElement) yearElement.textContent = new Date().getFullYear();
</script>
@stack('scripts')
</body>
</html>
