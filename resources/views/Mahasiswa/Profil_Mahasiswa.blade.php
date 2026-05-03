@extends('layouts.mahasiswa')

@section('title', 'Profil Pengguna')
@section('active_page', '')
@section('page_title', 'Profil Pengguna')
@section('page_subtitle', 'Kelola informasi akun Anda.')

@section('content')
<div class="flex min-h-[calc(100vh-12rem)] justify-center px-2 py-4">
  <div class="w-full max-w-3xl rounded-[28px] border border-slate-300 bg-slate-200/95 p-8 shadow-lg md:p-10">
    <div class="mb-8 text-center">
      <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full border-[3px] border-slate-900 text-slate-900">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-slate-900 md:text-3xl">Nama Pengguna</h2>
    </div>

    <div class="mb-2">
      <h3 class="mb-4 text-lg font-semibold text-slate-800">Informasi Akun</h3>

      <div class="space-y-4">
        <div class="rounded-2xl border border-slate-100 bg-white px-5 py-4 shadow-md shadow-slate-300/40">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Pengguna</p>
          <p class="mt-1 text-base font-semibold text-slate-900">Luthfi Dwi Apriyaldi</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white px-5 py-4 shadow-md shadow-slate-300/40">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">NIM</p>
          <p class="mt-1 text-base font-semibold text-slate-900">3312501077</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white px-5 py-4 shadow-md shadow-slate-300/40">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kata Sandi</p>
          <div class="mt-1 flex items-center justify-between gap-3">
            <p id="profilePasswordDisplay" class="min-w-0 flex-1 text-base font-semibold text-slate-900" data-plain="www">••••••</p>
            <button type="button" id="profilePasswordToggle"
              class="shrink-0 rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
              aria-label="Tampilkan kata sandi" aria-pressed="false">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 icon-eye" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye-off hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.289m7.633 7.634l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
              </svg>
            </button>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white px-5 py-4 shadow-md shadow-slate-300/40">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tipe Keanggotaan</p>
          <p class="mt-1 text-base font-semibold text-slate-900">Mahasiswa</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white px-5 py-4 shadow-md shadow-slate-300/40">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</p>
          <p class="mt-1 text-base font-semibold text-slate-900">luthfi@gmail.com</p>
        </div>
      </div>
    </div>

    <div class="mt-8 flex justify-end">
      <a href="/Beranda_Mahasiswa"
        class="rounded-full border-2 border-slate-900 bg-slate-300 px-8 py-2.5 text-base font-bold text-slate-900 shadow-sm hover:bg-slate-400 transition">
        Kembali
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
  const display = document.getElementById('profilePasswordDisplay');
  const btn = document.getElementById('profilePasswordToggle');
  if (!display || !btn) return;
  const plain = display.getAttribute('data-plain') || '';
  const masked = '••••••';
  const eye = btn.querySelector('.icon-eye');
  const eyeOff = btn.querySelector('.icon-eye-off');
  let shown = false;
  btn.addEventListener('click', function () {
    shown = !shown;
    display.textContent = shown ? plain : masked;
    btn.setAttribute('aria-pressed', shown ? 'true' : 'false');
    btn.setAttribute('aria-label', shown ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    eye.classList.toggle('hidden', shown);
    eyeOff.classList.toggle('hidden', !shown);
  });
})();
</script>
@endpush
