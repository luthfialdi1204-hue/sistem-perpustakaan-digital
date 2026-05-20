@extends('auth.layout')

@section('title', 'Masuk — Perpustakaan Digital')
@section('subtitle', 'Silahkan masuk untuk melanjutkan')

@section('content')
@php
  $mahasiswaErrors = $errors->getBag('mahasiswa');
  $adminErrors = $errors->getBag('admin');
  $inputClass = 'w-full rounded-xl border border-white/50 bg-white/90 py-2.5 pl-10 text-slate-800 outline-none transition-all focus:border-accent/60 focus:ring-[3px] focus:ring-accent/15';
@endphp

<ul class="mb-5 flex rounded-full border border-white/10 bg-brand-dark/40 p-1 text-sm font-medium" id="loginTab" data-tabs-toggle="#loginTabPanels" role="tablist">
  <li class="flex-1" role="presentation">
    <button class="inline-flex w-full items-center justify-center gap-1.5 rounded-full py-2 font-medium text-white/90 aria-selected:bg-gradient-to-br aria-selected:from-white aria-selected:to-slate-50 aria-selected:text-brand aria-selected:shadow-md" id="mahasiswa-tab" data-tabs-target="#panel-mahasiswa" type="button" role="tab" aria-controls="panel-mahasiswa" aria-selected="true">
      <i class="bi bi-mortarboard-fill text-sm"></i> Mahasiswa
    </button>
  </li>
  <li class="flex-1" role="presentation">
    <button class="inline-flex w-full items-center justify-center gap-1.5 rounded-full py-2 font-medium text-white/90 aria-selected:bg-gradient-to-br aria-selected:from-white aria-selected:to-slate-50 aria-selected:text-brand aria-selected:shadow-md" id="admin-tab" data-tabs-target="#panel-admin" type="button" role="tab" aria-controls="panel-admin" aria-selected="false">
      <i class="bi bi-shield-lock-fill text-sm"></i> Admin
    </button>
  </li>
</ul>

<div id="loginTabPanels">
  <div id="panel-mahasiswa" role="tabpanel" aria-labelledby="mahasiswa-tab">
    <form method="POST" action="{{ route('login.mahasiswa') }}" class="space-y-4 text-left">
      @csrf
      <div>
        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70"><i class="bi bi-person-vcard text-amber-300/80"></i> NIM</label>
        <div class="group relative">
          <i class="bi bi-hash pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-accent"></i>
          <input type="text" name="nim" value="{{ old('nim') }}" required class="{{ $inputClass }}" placeholder="Masukkan NIM">
        </div>
        @if ($mahasiswaErrors->has('nim'))
          <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $mahasiswaErrors->first('nim') }}</p>
        @endif
      </div>
      <div>
        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70"><i class="bi bi-key text-amber-300/80"></i> Kata Sandi</label>
        <div class="group relative">
          <i class="bi bi-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-accent"></i>
          <input type="password" id="passwordMahasiswa" name="password" required class="{{ $inputClass }} pr-10" placeholder="Kata sandi">
          <button type="button" data-target="passwordMahasiswa" class="toggle-password absolute right-3.5 top-1/2 -translate-y-1/2 text-lg text-slate-500 hover:text-white">
            <i class="bi bi-eye-slash"></i>
          </button>
        </div>
        @if ($mahasiswaErrors->has('password'))
          <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $mahasiswaErrors->first('password') }}</p>
        @endif
      </div>
      <a href="{{ route('password.forgot', ['role' => 'mahasiswa']) }}" class="flex items-center gap-1.5 text-sm text-amber-100 transition hover:text-white">
        <i class="bi bi-question-circle"></i> Lupa password?
      </a>
      <div class="flex items-center justify-between pt-1">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/20 px-4 py-2 text-sm text-white transition hover:bg-white/30">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-brand via-brand-light to-teal-600 px-5 py-2 text-sm font-medium text-white shadow-lg transition hover:brightness-105">
          <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>
      </div>
    </form>
  </div>

  <div class="hidden" id="panel-admin" role="tabpanel" aria-labelledby="admin-tab">
    <form method="POST" action="{{ route('login.admin') }}" class="space-y-4 text-left">
      @csrf
      <div>
        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70"><i class="bi bi-person-badge text-amber-300/80"></i> NIP</label>
        <div class="group relative">
          <i class="bi bi-hash pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-accent"></i>
          <input type="text" name="nip" value="{{ old('nip') }}" required class="{{ $inputClass }}" placeholder="Masukkan NIP">
        </div>
        @if ($adminErrors->has('nip'))
          <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $adminErrors->first('nip') }}</p>
        @endif
      </div>
      <div>
        <label class="mb-1.5 flex items-center gap-1 text-xs font-medium text-white/70"><i class="bi bi-key text-amber-300/80"></i> Kata Sandi</label>
        <div class="group relative">
          <i class="bi bi-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-accent"></i>
          <input type="password" id="passwordAdmin" name="password" required class="{{ $inputClass }} pr-10" placeholder="Kata sandi">
          <button type="button" data-target="passwordAdmin" class="toggle-password absolute right-3.5 top-1/2 -translate-y-1/2 text-lg text-slate-500 hover:text-white">
            <i class="bi bi-eye-slash"></i>
          </button>
        </div>
        @if ($adminErrors->has('password'))
          <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200"><i class="bi bi-exclamation-circle"></i>{{ $adminErrors->first('password') }}</p>
        @endif
      </div>
      <a href="{{ route('password.forgot', ['role' => 'admin']) }}" class="flex items-center gap-1.5 text-sm text-violet-200 transition hover:text-white">
        <i class="bi bi-question-circle"></i> Lupa password?
      </a>
      <div class="flex items-center justify-between pt-1">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/20 px-4 py-2 text-sm text-white transition hover:bg-white/30">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-violet-900 via-violet-700 to-violet-600 px-5 py-2 text-sm font-medium text-white shadow-lg transition hover:brightness-110">
          <i class="bi bi-shield-check"></i> Masuk
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-password').forEach((btn) => {
  btn.addEventListener('click', function () {
    const input = document.getElementById(this.getAttribute('data-target'));
    const icon = this.querySelector('i');
    if (!input || !icon) return;
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
  });
});
@if ($adminErrors->any())
document.addEventListener('DOMContentLoaded', () => document.getElementById('admin-tab')?.click());
@endif
</script>
@endpush
