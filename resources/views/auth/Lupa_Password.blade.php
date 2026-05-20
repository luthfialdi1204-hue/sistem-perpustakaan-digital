@extends('auth.layout')

@section('title', 'Lupa Password — Perpustakaan Digital')
@section('subtitle', 'Reset password — kirim kode OTP')

@section('steps')
  @include('auth.partials.forgot-steps', ['currentStep' => 1])
@endsection

@section('content')
@php
  $inputClass = 'w-full rounded-xl border border-white/50 bg-white/90 py-2.5 pl-10 text-slate-800 outline-none transition-all focus:border-accent/60 focus:ring-[3px] focus:ring-accent/15';
  $tabActive = 'bg-gradient-to-br from-white to-slate-50 text-brand shadow-md';
@endphp

<div class="text-left">
  <div class="mb-4 grid grid-cols-2 rounded-full border border-white/10 bg-brand-dark/40 p-1 text-sm">
    <a href="{{ route('password.forgot', ['role' => 'mahasiswa']) }}"
      class="{{ $role === 'mahasiswa' ? $tabActive : 'text-white/90' }} flex items-center justify-center gap-1.5 rounded-full py-2 font-medium transition-all">
      <i class="bi bi-mortarboard-fill text-sm"></i> Mahasiswa
    </a>
    <a href="{{ route('password.forgot', ['role' => 'admin']) }}"
      class="{{ $role === 'admin' ? $tabActive : 'text-white/90 hover:text-white' }} flex items-center justify-center gap-1.5 rounded-full py-2 font-medium transition-all">
      <i class="bi bi-shield-lock-fill text-sm"></i> Admin
    </a>
  </div>

  <form method="POST" action="{{ route('password.otp.send') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="role" value="{{ $role }}">
    <div>
      <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70">
        <i class="bi bi-person-vcard text-amber-300/80"></i> {{ $label }}
      </label>
      <div class="group relative">
        <i class="bi bi-hash pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-accent"></i>
        <input type="text" name="identifier" value="{{ old('identifier', $identifier) }}" required
          class="{{ $inputClass }}" placeholder="Masukkan {{ $label }}" inputmode="numeric">
      </div>
      @error('forgot')
        <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>
      @enderror
      @error('identifier')
        <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>
      @enderror
    </div>
    <p class="flex items-start gap-1.5 text-xs text-white/60">
      <i class="bi bi-envelope mt-0.5 shrink-0 text-amber-400"></i>
      OTP akan dikirim ke email terdaftar pada akun Anda.
    </p>
    <div class="flex items-center justify-between gap-2 pt-1">
      <a href="{{ route('login.form') }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/20 px-4 py-2 text-sm text-white transition hover:bg-white/30">
        <i class="bi bi-arrow-left"></i> Kembali ke Login
      </a>
      <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-brand via-brand-light to-teal-600 px-5 py-2 text-sm font-medium text-white shadow-lg transition hover:brightness-105">
        <i class="bi bi-send"></i> Kirim OTP
      </button>
    </div>
  </form>
</div>
@endsection
