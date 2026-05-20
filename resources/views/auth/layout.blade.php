<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Perpustakaan Digital')</title>
<script src="https://cdn.tailwindcss.com"></script>
@include('partials.head-theme')
@include('partials.flowbite-assets')
@stack('styles')
</head>
<body class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[url('{{ asset('images/background.jpg') }}')] bg-cover bg-center bg-fixed bg-no-repeat p-4 font-sans antialiased">

<div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-[rgba(21,42,82,0.92)] via-[rgba(30,55,110,0.85)] to-[rgba(15,118,110,0.55)]"></div>

<div class="pointer-events-none absolute -left-20 -top-20 h-64 w-64 animate-float-slow rounded-full bg-amber-400/20 blur-3xl"></div>
<div class="pointer-events-none absolute bottom-10 right-10 h-48 w-48 animate-float-delay rounded-full bg-teal-400/15 blur-3xl"></div>

<div class="relative z-10 w-full max-w-md rounded-3xl border border-white/25 bg-gradient-to-br from-white/20 to-white/10 p-8 shadow-2xl shadow-black/35 backdrop-blur-xl ring-1 ring-inset ring-white/20">

  <div class="mb-5 text-center">
    <div class="mb-3 inline-flex h-20 w-20 items-center justify-center rounded-2xl border border-white/20 bg-white/15 shadow-lg">
      <img src="{{ asset('images/poltek.png') }}" class="w-14 object-contain" alt="Logo">
    </div>
    <div class="flex items-center justify-center gap-2">
      <i class="bi bi-book-half text-xl text-amber-400"></i>
      <h5 class="text-lg font-bold text-white">Perpustakaan Digital</h5>
    </div>
    <p class="mt-1 flex items-center justify-center gap-1 text-sm text-white/75">
      <i class="bi bi-door-open text-xs text-amber-300/90"></i>
      <span>@yield('subtitle', 'Silahkan masuk untuk melanjutkan')</span>
    </p>
  </div>

  @if (session('success'))
    <div id="auth-success-alert" class="mb-4 flex items-center rounded-lg border border-emerald-400/40 bg-emerald-500/20 p-4 text-sm text-emerald-100" role="alert">
      <i class="bi bi-check-circle-fill me-2 shrink-0 text-emerald-300"></i>
      <span class="flex-1">{{ session('success') }}</span>
      <button type="button" class="ms-auto inline-flex rounded-lg p-1.5 text-emerald-200 hover:bg-emerald-500/20" data-dismiss-target="#auth-success-alert" aria-label="Tutup">
        <i class="bi bi-x-lg text-sm"></i>
      </button>
    </div>
  @endif

  @hasSection('steps')
    @yield('steps')
  @endif

  @yield('content')
</div>

@stack('scripts')
</body>
</html>
