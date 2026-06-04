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
  @include('components.admin.page_toast')
  @yield('content')
  @include('components.admin.footer')
</div>

<script>
const yearElement = document.getElementById("year");
if (yearElement) yearElement.textContent = new Date().getFullYear();
</script>

<script>
(function () {
  let toastTimer = null;

  function showToast(message, type = "success") {
    const toast = document.getElementById("pageToast");
    const box = document.getElementById("pageToastBox");
    const icon = document.getElementById("pageToastIcon");
    const text = document.getElementById("pageToastText");
    if (!toast || !box || !icon || !text) return;

    text.textContent = message || "";

    if (type === "error") {
      box.className = "flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 shadow-lg";
      icon.className = "bi bi-exclamation-triangle-fill text-lg";
    } else {
      box.className = "flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-lg";
      icon.className = "bi bi-check-circle-fill text-lg";
    }

    toast.classList.remove("translate-y-4", "opacity-0");
    toast.classList.add("translate-y-0", "opacity-100");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toast.classList.add("translate-y-4", "opacity-0");
      toast.classList.remove("translate-y-0", "opacity-100");
    }, 3200);
  }

  function showValidationErrors(json, fallback = "Validasi gagal") {
    const errors = json?.errors;
    if (!errors) {
      showToast(json?.message || fallback, "error");
      return;
    }
    const lines = [];
    Object.values(errors).forEach((msgs) => {
      (Array.isArray(msgs) ? msgs : [msgs]).forEach((m) => lines.push(m));
    });
    showToast(lines.length ? lines.join(" • ") : (json?.message || fallback), "error");
  }

  window.AdminUi = window.AdminUi || {};
  window.AdminUi.toast = showToast;
  window.AdminUi.validationToast = showValidationErrors;
})();
</script>
@stack('scripts')
</body>
</html>
