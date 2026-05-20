@extends('auth.layout')

@section('title', 'Reset Password — Perpustakaan Digital')
@section('subtitle', 'Buat password baru')

@section('steps')
  @include('auth.partials.forgot-steps', ['currentStep' => 3])
@endsection

@section('content')
@php
  $inputClass = 'w-full rounded-xl border border-white/50 bg-white/90 py-2.5 pl-10 text-slate-800 outline-none transition-all focus:border-accent/60 focus:ring-[3px] focus:ring-accent/15';
@endphp

<div class="text-left">
  <p class="mb-4 flex items-start gap-2 text-sm text-white/80">
    <i class="bi bi-arrow-repeat mt-0.5 shrink-0 text-emerald-400"></i>
    <span>Buat password baru untuk <strong class="text-white">{{ $email ?? 'akun Anda' }}</strong></span>
  </p>

  <form method="POST" action="{{ route('password.reset') }}" class="space-y-4">
    @csrf
    <div>
      <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70"><i class="bi bi-lock text-amber-300/80"></i> Password baru</label>
      <div class="group relative">
        <i class="bi bi-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-accent"></i>
        <input type="password" name="password" required class="{{ $inputClass }}" placeholder="Minimal 6 karakter">
      </div>
      @error('password')
        <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>
      @enderror
    </div>
    <div>
      <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70"><i class="bi bi-lock-fill text-amber-300/80"></i> Konfirmasi password</label>
      <div class="group relative">
        <i class="bi bi-lock-fill pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-accent"></i>
        <input type="password" name="password_confirmation" required class="{{ $inputClass }}" placeholder="Ulangi password">
      </div>
    </div>
    <div class="flex items-center justify-between gap-2 pt-1">
      <a href="{{ route('password.forgot.otp') }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/20 px-4 py-2 text-sm text-white transition hover:bg-white/30">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
      <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-2 text-sm font-medium text-white shadow-lg transition hover:brightness-110">
        <i class="bi bi-check2-circle"></i> Simpan
      </button>
    </div>
  </form>
</div>
@endsection
