@extends('layouts.mahasiswa')

@section('title', 'Dashboard')
@section('active_page', 'beranda')
@section('page_title', 'Beranda')
@section('page_subtitle', 'Pantau aktivitas peminjaman buku Anda di sini.')

@section('content')
<!-- WELCOME -->
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-700 to-cyan-600 p-6 text-white shadow-lg">
  <h2 class="mb-1 text-xl font-semibold">Selamat Datang, Luthfi Dwi Apriyadi</h2>
  <p class="text-sm text-blue-100">Senin, 29 Maret 2026</p>
</div>

<!-- INFO -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Total Peminjaman</p>
    <p class="mt-2 text-2xl font-bold text-slate-800">1</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Sedang Dipinjam</p>
    <p class="mt-2 text-2xl font-bold text-blue-700">1</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Sudah Dikembalikan</p>
    <p class="mt-2 text-2xl font-bold text-emerald-600">1</p>
  </div>
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Terlambat</p>
    <p class="mt-2 text-2xl font-bold text-rose-600">1</p>
  </div>
</div>

<!-- DIPINJAM -->
<div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 p-4 font-semibold">Buku Yang Sedang Dipinjam</div>
  <div class="flex items-center p-4">
    <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="w-24 rounded-lg object-cover shadow-sm">
    
    <div class="ml-4">
      <h3 class="font-semibold text-slate-800">Tentang Kamu</h3>
      <p class="text-sm text-slate-500">Tere Liye</p>
      <p class="text-sm text-slate-500">Pinjam: 23 Maret 2026</p>
      <p class="text-sm text-slate-500">Kembali: 30 Maret 2026</p>

      <span class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
        Sedang Dipinjam
      </span>
    </div>
  </div>
</div>

<!-- REKOMENDASI -->
<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 p-4 font-semibold">Rekomendasi Buku Untuk Anda</div>

  <div class="grid grid-cols-2 gap-3 p-4 md:grid-cols-3 xl:grid-cols-6" id="rekomendasi-container"></div>
</div>
@endsection

@push('scripts')
<script>
const books = [
  {title:"Atomic Habits", img:"https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg", available:true},
  {title:"Rich Dad Poor Dad", img:"https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg", available:true},
  {title:"Filosofi Teras", img:"https://m.media-amazon.com/images/I/81zD9kaVW9L.jpg", available:false},
  {title:"Psikologi Uang", img:"https://m.media-amazon.com/images/I/71g2ednj0JL.jpg", available:true},
  {title:"Laut Bercerita", img:"https://m.media-amazon.com/images/I/81af+MCATTL.jpg", available:true},
  {title:"Bumi", img:"https://m.media-amazon.com/images/I/81l3rZK4lnL.jpg", available:false}
];

// shuffle
function shuffle(array){
  return array.sort(() => 0.5 - Math.random());
}

// prioritas tersedia
let rekomendasi = [
  ...books.filter(b => b.available),
  ...books.filter(b => !b.available)
];

rekomendasi = shuffle(rekomendasi).slice(0,6);

const container = document.getElementById("rekomendasi-container");

rekomendasi.forEach(book => {
  container.innerHTML += `
    <div class="rounded-xl border border-slate-200 bg-white p-2 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
      <img src="${book.img}" class="h-32 w-full rounded-lg object-cover">
      <small class="mt-2 block font-medium text-slate-700">${book.title}</small>
      <button class="mt-2 w-full rounded-lg bg-blue-600 py-1.5 text-xs text-white hover:bg-blue-700 transition">
        Detail
      </button>
    </div>
  `;
});
</script>
@endpush