<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Halaman Admin')</title>
<script src="https://cdn.tailwindcss.com"></script>
@include('partials.head-theme')
@include('partials.flowbite-assets')
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-indigo-50/30 to-slate-100 font-sans text-slate-800 antialiased">
@include('components.admin.sidebar', ['activePage' => trim($__env->yieldContent('active_page'))])

<div class="ml-64 p-6 md:p-8 min-h-screen">
  @include('components.admin.topbar', [
      'title' => trim($__env->yieldContent('page_title')) ?: 'Halaman Admin',
      'subtitle' => trim($__env->yieldContent('page_subtitle')),
  ])
  @yield('content')
  @include('components.admin.footer')
</div>

<script>
const yearElement = document.getElementById("year");
if (yearElement) yearElement.textContent = new Date().getFullYear();
</script>
@stack('scripts')
</body>
</html>
