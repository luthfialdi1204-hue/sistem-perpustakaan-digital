@extends('auth.layout')

@section('title', 'Verifikasi OTP — Perpustakaan Digital')
@section('subtitle', 'Verifikasi kode OTP')

@section('steps')
  @include('auth.partials.forgot-steps', ['currentStep' => 2])
@endsection

@section('content')
@php
  $otpInputClass = 'w-full rounded-xl border border-white/50 bg-white/90 px-4 py-2.5 text-center font-semibold tracking-[0.4em] text-slate-800 outline-none transition-all focus:border-accent/60 focus:ring-[3px] focus:ring-accent/15';
@endphp

<div class="text-left">
  <p class="mb-4 flex items-start gap-2 text-sm text-white/80">
    <i class="bi bi-envelope-check mt-0.5 shrink-0 text-amber-400"></i>
    <span>Masukkan kode OTP yang dikirim ke <strong class="text-white">{{ $email ?? 'email akun Anda' }}</strong></span>
  </p>
  @if ($identifier)
    <p class="mb-3 flex items-center gap-1 text-xs text-white/50">
      <i class="bi bi-person-badge"></i> {{ $label }}: {{ $identifier }}
    </p>
  @endif

  <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-4">
    @csrf
    <div>
      <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70"><i class="bi bi-123 text-amber-300/80"></i> Kode OTP</label>
      <input type="text" name="otp" value="{{ old('otp') }}" maxlength="6" required
        class="{{ $otpInputClass }}" placeholder="000000" inputmode="numeric">
      @error('otp')
        <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>
      @enderror
    </div>
    <div class="flex items-center justify-between gap-2 pt-1">
      <a href="{{ route('password.forgot', ['role' => $role]) }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/20 px-4 py-2 text-sm text-white transition hover:bg-white/30">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
      <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2 text-sm font-medium text-white shadow-lg transition hover:brightness-110">
        <i class="bi bi-patch-check"></i> Verifikasi
      </button>
    </div>
  </form>
</div>
@endsection
