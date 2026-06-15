@extends('layouts.admin')

@section('title', 'Profil Pengguna')
@section('active_page', 'profil')
@section('page_title', 'Profil Pengguna')
@section('page_subtitle', 'Kelola informasi akun admin Anda.')

@section('content')
@php
  /** @var \App\Models\User|null $user */
  $user = auth()->user();
  $name = $user?->nama_pengguna ?? '—';
  $email = $user?->email ?? '—';
  $nip = $user?->loginIdentifier() ?? '—';
  $initials = $user?->initials() ?? 'AD';
@endphp
<div class="mx-auto max-w-4xl space-y-6">

  <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
    <div class="bg-gradient-to-br from-brand via-brand-light to-teal-600 relative px-6 py-8 md:px-8">
      <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
      <div class="pointer-events-none absolute -bottom-12 left-1/3 h-32 w-32 rounded-full bg-amber-400/10"></div>

      <div class="relative flex flex-col items-center gap-4 sm:flex-row sm:items-end sm:gap-6">
        <div class="relative">
          <form id="formFotoProfil" method="POST" action="{{ route('admin.profil.foto') }}" enctype="multipart/form-data" class="group relative flex h-24 w-24 shrink-0 cursor-pointer items-center justify-center rounded-2xl border-4 border-white/30 bg-white/15 shadow-lg backdrop-blur-sm transition hover:border-white/60">
            @csrf
            <input type="file" id="inputFotoProfil" name="foto_profil" class="hidden" accept="image/*">
            <label for="inputFotoProfil" class="absolute inset-0 flex cursor-pointer items-center justify-center rounded-xl bg-black/40 opacity-0 transition group-hover:opacity-100">
              <i class="bi bi-camera-fill text-2xl text-white"></i>
            </label>
            @if($user->foto_profil)
              <img src="{{ asset('storage/' . $user->foto_profil) }}" class="h-full w-full rounded-xl object-cover">
            @else
              <span class="text-3xl font-bold text-white">{{ $initials }}</span>
            @endif
          </form>
          @if($user->foto_profil)
            <form method="POST" action="{{ route('admin.profil.foto.hapus') }}" class="absolute -bottom-2 -right-2">
              @csrf
              @method('DELETE')
              <button type="submit" class="flex h-7 w-7 items-center justify-center rounded-full bg-rose-500 text-white shadow-md transition hover:bg-rose-600 hover:scale-105" title="Hapus Foto">
                <i class="bi bi-trash-fill text-xs"></i>
              </button>
            </form>
          @endif
        </div>
        <div class="text-center sm:text-left">
          <p class="text-xs font-medium uppercase tracking-wider text-amber-300/90">Akun Admin</p>
          <h2 class="mt-1 text-2xl font-bold text-white md:text-3xl">{{ $name }}</h2>
          <p class="mt-1 text-sm text-blue-100">NIP {{ $nip }} · {{ $email }}</p>
        </div>
        <span class="sm:ml-auto inline-flex items-center gap-1.5 rounded-full bg-emerald-500/90 px-3 py-1 text-xs font-semibold text-white shadow-sm">
          <i class="bi bi-check-circle-fill"></i> Aktif
        </span>
      </div>
    </div>
  </div>

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
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">NIP</p>
          <p class="mt-0.5 font-semibold text-slate-800">{{ $nip }}</p>
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
          <i class="bi bi-shield-check text-lg"></i>
        </div>
        <div class="min-w-0">
          <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tipe Keanggotaan</p>
          <p class="mt-0.5">
            <span class="inline-flex items-center gap-1 rounded-md bg-[#1E376E] px-2.5 py-0.5 text-sm font-semibold text-white">
              <i class="bi bi-shield-fill-check text-xs"></i> Admin
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

  <div class="flex flex-wrap items-center justify-between gap-3">
    <a href="{{ route('admin.dashboard') }}"
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

<!-- Crop Modal -->
<div id="cropModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCropModal()"></div>
  
  <!-- Panel -->
  <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden" style="animation: slideUp .22s ease">
    <div class="h-1.5 w-full bg-gradient-to-r from-[#1E376E] to-teal-500"></div>
    <div class="p-6">
      <div class="mb-4 flex items-center justify-between">
        <h3 class="text-lg font-bold text-[#1E376E]">Sesuaikan Foto Profil</h3>
        <button type="button" onclick="closeCropModal()" class="text-slate-400 hover:text-slate-700 text-xl leading-none">&times;</button>
      </div>
      <p class="text-xs text-slate-500 mb-4">Geser dan atur ukuran foto agar pas di dalam lingkaran.</p>
      
      <!-- Image Container -->
      <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center max-h-[350px]">
        <img id="cropImage" class="max-w-full max-h-[350px] block" src="" alt="Foto profil">
      </div>
      
      <!-- Buttons -->
      <div class="mt-6 flex justify-end gap-3">
        <button type="button" onclick="closeCropModal()" class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition active:scale-95">
          Batal
        </button>
        <button type="button" id="btnCropUpload" class="rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-teal-700 transition flex items-center gap-2 active:scale-95">
          <span id="btnCropText">Simpan Foto</span>
          <i class="bi bi-check-lg"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes slideUp {
  from { opacity: 0; transform: translateY(14px) scale(.97); }
  to   { opacity: 1; transform: translateY(0)   scale(1);    }
}
/* Make cropping box circular */
.cropper-view-box,
.cropper-face {
  border-radius: 50%;
}
</style>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
  const inputEl = document.getElementById('inputFotoProfil');
  const cropModal = document.getElementById('cropModal');
  const cropImage = document.getElementById('cropImage');
  let cropper = null;

  inputEl.addEventListener('change', function (e) {
    const files = e.target.files;
    if (files && files.length > 0) {
      const file = files[0];
      const reader = new FileReader();
      reader.onload = function (e) {
        cropImage.src = e.target.result;
        cropModal.classList.remove('hidden');
        if (cropper) {
          cropper.destroy();
        }
        cropper = new Cropper(cropImage, {
          aspectRatio: 1,
          viewMode: 1,
          autoCropArea: 1,
          dragMode: 'move',
          cropBoxMovable: true,
          cropBoxResizable: true,
          toggleDragModeOnDblclick: false,
        });
      };
      reader.readAsDataURL(file);
    }
  });

  document.getElementById('btnCropUpload').addEventListener('click', function () {
    if (!cropper) return;
    
    const btn = document.getElementById('btnCropUpload');
    const textEl = document.getElementById('btnCropText');
    btn.disabled = true;
    textEl.textContent = 'Menyimpan...';

    cropper.getCroppedCanvas({
      width: 400,
      height: 400,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high',
    }).toBlob(function (blob) {
      const formData = new FormData();
      formData.append('foto_profil', blob, 'avatar.jpg');
      formData.append('_token', '{{ csrf_token() }}');

      fetch('{{ route("admin.profil.foto") }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => {
        if (response.redirected) {
          window.location.href = response.url;
        } else {
          window.location.reload();
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengunggah foto profil.');
        btn.disabled = false;
        textEl.textContent = 'Simpan Foto';
      });
    }, 'image/jpeg', 0.9);
  });

  function closeCropModal() {
    cropModal.classList.add('hidden');
    inputEl.value = '';
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  }
</script>
@endpush
@endsection
