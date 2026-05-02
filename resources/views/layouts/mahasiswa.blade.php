<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Halaman Mahasiswa')</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-slate-100 text-slate-800">
@include('components.mahasiswa.sidebar', ['activePage' => trim($__env->yieldContent('active_page'))])

<div class="ml-64 p-6 md:p-8">
  @include('components.mahasiswa.topbar', [
      'title' => trim($__env->yieldContent('page_title')) ?: 'Halaman Mahasiswa',
      'subtitle' => trim($__env->yieldContent('page_subtitle')),
  ])

  @yield('content')

  @include('components.mahasiswa.footer')
</div>

<script>
const userMenuButton = document.getElementById("userMenuButton");
const userDropdown = document.getElementById("userDropdown");
const yearElement = document.getElementById("year");

if (yearElement) {
  yearElement.textContent = new Date().getFullYear();
}

if (userMenuButton && userDropdown) {
  userMenuButton.addEventListener("click", function () {
    userDropdown.classList.toggle("hidden");
  });

  document.addEventListener("click", function (e) {
    if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
      userDropdown.classList.add("hidden");
    }
  });
}
</script>

@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
</body>
</html>
