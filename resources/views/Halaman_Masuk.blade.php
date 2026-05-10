<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Masuk</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background: url('{{ asset("images/background.jpg") }}') no-repeat center center/cover;
}

/* overlay */
.overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(30,58,138,0.8), rgba(147,51,234,0.6));
}
</style>
</head>

<body class="relative min-h-screen flex items-center justify-center">

<div class="overlay"></div>

<!-- CARD -->
<div class="relative z-10 backdrop-blur-lg bg-white/20 border border-white/30 rounded-2xl shadow-2xl p-8 w-full max-w-md text-center">

  <!-- LOGO -->
  <img src="{{ asset('images/poltek.png') }}" class="w-20 mx-auto mb-3 drop-shadow-lg">

  <h5 class="font-bold text-lg text-white">Perpustakaan Digital</h5>
  <p class="mb-5 text-sm text-white/80">Silahkan Masuk Untuk Melanjutkan</p>

  <div class="mb-5 grid grid-cols-2 rounded-full bg-white/20 p-1 text-sm">
    <button id="tabMahasiswa" type="button" class="rounded-full bg-white text-slate-800 py-1.5 font-medium">Mahasiswa</button>
    <button id="tabAdmin" type="button" class="rounded-full text-white py-1.5 font-medium">Admin</button>
  </div>

  <form id="formMahasiswa" method="POST" action="{{ route('login.mahasiswa') }}" class="space-y-4">
    @csrf
    <div>
      <input
        type="text"
        name="nim"
        value="{{ old('nim') }}"
        required
        class="w-full px-4 py-2 rounded-full bg-white/80 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition"
        placeholder="Masukkan NIM">
      @if ($errors->mahasiswa->has('nim'))
        <p class="mt-1 text-left text-xs text-rose-200">{{ $errors->mahasiswa->first('nim') }}</p>
      @endif
    </div>

    <div class="relative">
      <input
        type="password"
        id="passwordMahasiswa"
        name="password"
        required
        class="w-full px-4 py-2 rounded-full bg-white/80 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none pr-10 transition"
        placeholder="Kata Sandi">
      <i data-target="passwordMahasiswa" class="toggle-password bi bi-eye-slash absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-600 hover:text-blue-600 transition"></i>
      @if ($errors->mahasiswa->has('password'))
        <p class="mt-1 text-left text-xs text-rose-200">{{ $errors->mahasiswa->first('password') }}</p>
      @endif
    </div>

    <button type="button" onclick="previewForgotFlow('mahasiswa')" class="block text-left text-sm text-blue-200 hover:text-blue-100 underline">
      Lupa password?
    </button>

    <div class="flex justify-between items-center">
      <a href="{{ url('/') }}"
         class="bg-white/70 px-4 py-1 rounded-full text-sm hover:bg-white transition shadow inline-block">
        Kembali
      </a>
      <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-1 rounded-full text-sm hover:scale-105 transition shadow-lg">
        Masuk
      </button>
    </div>
  </form>

  <form id="formAdmin" method="POST" action="{{ route('login.admin') }}" class="space-y-4 hidden">
    @csrf
    <div>
      <input
        type="text"
        name="nip"
        value="{{ old('nip') }}"
        required
        class="w-full px-4 py-2 rounded-full bg-white/80 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition"
        placeholder="Masukkan NIP">
      @if ($errors->admin->has('nip'))
        <p class="mt-1 text-left text-xs text-rose-200">{{ $errors->admin->first('nip') }}</p>
      @endif
    </div>

    <div class="relative">
      <input
        type="password"
        id="passwordAdmin"
        name="password"
        required
        class="w-full px-4 py-2 rounded-full bg-white/80 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none pr-10 transition"
        placeholder="Kata Sandi">
      <i data-target="passwordAdmin" class="toggle-password bi bi-eye-slash absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-gray-600 hover:text-blue-600 transition"></i>
      @if ($errors->admin->has('password'))
        <p class="mt-1 text-left text-xs text-rose-200">{{ $errors->admin->first('password') }}</p>
      @endif
    </div>

    <button type="button" onclick="previewForgotFlow('admin')" class="block text-left text-sm text-blue-200 hover:text-blue-100 underline">
      Lupa password?
    </button>

    <div class="flex justify-between items-center">
      <a href="{{ url('/') }}"
         class="bg-white/70 px-4 py-1 rounded-full text-sm hover:bg-white transition shadow inline-block">
        Kembali
      </a>
      <button type="submit" class="bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white px-5 py-1 rounded-full text-sm hover:scale-105 transition shadow-lg">
        Masuk
      </button>
    </div>
  </form>
</div>

<script>
const tabMahasiswa = document.getElementById('tabMahasiswa');
const tabAdmin = document.getElementById('tabAdmin');
const formMahasiswa = document.getElementById('formMahasiswa');
const formAdmin = document.getElementById('formAdmin');

function setActiveTab(type) {
  const isMahasiswa = type === 'mahasiswa';
  formMahasiswa.classList.toggle('hidden', !isMahasiswa);
  formAdmin.classList.toggle('hidden', isMahasiswa);

  tabMahasiswa.classList.toggle('bg-white', isMahasiswa);
  tabMahasiswa.classList.toggle('text-slate-800', isMahasiswa);
  tabMahasiswa.classList.toggle('text-white', !isMahasiswa);

  tabAdmin.classList.toggle('bg-white', !isMahasiswa);
  tabAdmin.classList.toggle('text-slate-800', !isMahasiswa);
  tabAdmin.classList.toggle('text-white', isMahasiswa);
}

tabMahasiswa.addEventListener('click', () => setActiveTab('mahasiswa'));
tabAdmin.addEventListener('click', () => setActiveTab('admin'));

function previewForgotFlow(role) {
  const identifierInput = role === 'mahasiswa'
    ? document.querySelector('#formMahasiswa input[name="nim"]')
    : document.querySelector('#formAdmin input[name="nip"]');
  const identifier = (identifierInput?.value || '').trim();

  if (!identifier) {
    alert(role === 'mahasiswa' ? 'Masukkan NIM terlebih dahulu.' : 'Masukkan NIP terlebih dahulu.');
    return;
  }

  const params = new URLSearchParams({ role, id: identifier });
  window.location.href = `{{ route('password.forgot.otp') }}?${params.toString()}`;
}

document.querySelectorAll('.toggle-password').forEach((toggleEl) => {
  toggleEl.addEventListener('click', function () {
    const inputId = this.getAttribute('data-target');
    const inputEl = document.getElementById(inputId);
    if (!inputEl) return;
    const type = inputEl.getAttribute('type') === 'password' ? 'text' : 'password';
    inputEl.setAttribute('type', type);
    this.classList.toggle('bi-eye');
    this.classList.toggle('bi-eye-slash');
  });
});

@if ($errors->admin->any())
setActiveTab('admin');
@else
setActiveTab('mahasiswa');
@endif
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>