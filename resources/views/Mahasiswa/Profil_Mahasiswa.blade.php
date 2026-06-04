@extends('layouts.mahasiswa')

@section('title', 'Profil Pengguna')
@section('active_page', 'profil')
@section('page_title', 'Profil Pengguna')
@section('page_subtitle', 'Kelola informasi akun dan keamanan Anda.')

@section('content')
@php
  /** @var \App\Models\User|null $user */
  $user = auth()->user();
  $name = $user?->nama_pengguna ?? '—';
  $email = $user?->email ?? '—';
  $nim = $user?->loginIdentifier() ?? '—';
  $initials = $user?->initials() ?? 'MH';
@endphp
<div class="mx-auto max-w-4xl space-y-6">

  <!-- HEADER PROFIL -->
  <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
    <div class="bg-gradient-to-br from-brand via-brand-light to-teal-600 relative px-6 py-8 md:px-8">
      <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
      <div class="pointer-events-none absolute -bottom-12 left-1/3 h-32 w-32 rounded-full bg-amber-400/10"></div>

      <div class="relative flex flex-col items-center gap-4 sm:flex-row sm:items-end sm:gap-6">
        <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl border-4 border-white/30 bg-white/15 text-3xl font-bold text-white shadow-lg backdrop-blur-sm">
          {{ $initials }}
        </div>
        <div class="text-center sm:text-left">
          <p class="text-xs font-medium uppercase tracking-wider text-amber-300/90">Akun Mahasiswa</p>
          <h2 class="mt-1 text-2xl font-bold text-white md:text-3xl">{{ $name }}</h2>
          <p class="mt-1 text-sm text-blue-100">NIM {{ $nim }} · {{ $email }}</p>
        </div>
        <span class="sm:ml-auto inline-flex items-center gap-1.5 rounded-full bg-emerald-500/90 px-3 py-1 text-xs font-semibold text-white shadow-sm">
          <i class="bi bi-check-circle-fill"></i> Aktif
        </span>
      </div>
    </div>
  </div>

  <!-- INFORMASI AKUN -->
  <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
    <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
      <i class="bi bi-person-badge text-[#1E376E]"></i>
      <h3 class="font-semibold text-[#1E376E]">Informasi Akun</h3>
    </div>

    <div class="grid gap-4 p-5 sm:grid-cols-2">
      <div class="flex gap-4 rounded-xl border border-slate-100 bg-slate-50/80 p-4 transition hover:border-[#1E376E]/20 hover:shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#1E376E]/10 text-[#1E376E]">
          <i class="bi bi-person text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Pengguna</p>
          <p class="mt-0.5 font-semibold text-slate-800">{{ $name }}</p>
        </div>
      </div>

      <div class="flex gap-4 rounded-xl border border-slate-100 bg-slate-50/80 p-4 transition hover:border-[#1E376E]/20 hover:shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-500/10 text-teal-700">
          <i class="bi bi-hash text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">NIM</p>
          <p class="mt-0.5 font-semibold text-slate-800">{{ $nim }}</p>
        </div>
      </div>

      <div class="flex gap-4 rounded-xl border border-slate-100 bg-slate-50/80 p-4 transition hover:border-[#1E376E]/20 hover:shadow-sm sm:col-span-2">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-700">
          <i class="bi bi-shield-lock text-lg"></i>
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kata Sandi</p>
          <div class="mt-0.5 flex items-center justify-between gap-3">
            <p class="font-semibold text-slate-800">••••••••</p>
            <span class="text-xs font-medium text-slate-500">Tidak dapat ditampilkan</span>
          </div>
        </div>
      </div>

      <div class="flex gap-4 rounded-xl border border-slate-100 bg-slate-50/80 p-4 transition hover:border-[#1E376E]/20 hover:shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-500/10 text-violet-700">
          <i class="bi bi-mortarboard text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tipe Keanggotaan</p>
          <p class="mt-0.5">
            <span class="inline-flex items-center gap-1 rounded-md bg-[#1E376E] px-2.5 py-0.5 text-sm font-semibold text-white">
              <i class="bi bi-mortarboard-fill text-xs"></i> Mahasiswa
            </span>
          </p>
        </div>
      </div>

      <div class="flex gap-4 rounded-xl border border-slate-100 bg-slate-50/80 p-4 transition hover:border-[#1E376E]/20 hover:shadow-sm">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-500/10 text-sky-700">
          <i class="bi bi-envelope text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</p>
          <p class="mt-0.5 truncate font-semibold text-slate-800">{{ $email }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- AKSI -->
  <div class="flex flex-wrap items-center justify-between gap-3">
    <a href="{{ route('mahasiswa.beranda') }}"
      class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
      <i class="bi bi-arrow-left"></i> Kembali ke Beranda
    </a>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:brightness-110">
        <i class="bi bi-box-arrow-left"></i> Keluar
      </button>
    </form>
  </div>
</div>
@endsection
