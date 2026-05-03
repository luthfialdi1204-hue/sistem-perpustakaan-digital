@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('active_page', 'dashboard')
@section('page_title', 'Beranda')

@section('content')

<!-- WELCOME -->
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-700 to-cyan-600 p-6 text-white shadow-lg">
  <h6 class="mb-1 font-semibold text-xl">Selamat Datang, Admin</h6>
  <p class="text-sm text-blue-100">Senin, 29 Maret 2026</p>
</div>

<!-- INFO -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Total Buku</p>
    <p class="mt-2 text-2xl font-bold text-slate-800">30</p>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Total Anggota</p>
    <p class="mt-2 text-2xl font-bold text-blue-700">2</p>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Buku Dipinjam</p>
    <p class="mt-2 text-2xl font-bold text-emerald-600">1</p>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-slate-500">Transaksi Hari Ini</p>
    <p class="mt-2 text-2xl font-bold text-rose-600">1</p>
  </div>
</div>

<!-- GRAFIK -->
<div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
  <canvas id="adminChart" height="100"></canvas>
</div>

<!-- TABLE -->
<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">

  <div class="border-b border-slate-100 p-4 font-bold">
    Daftar Riwayat Peminjaman
  </div>

  <!-- HEADER -->
  <div class="grid min-w-[900px] grid-cols-6 border-b border-slate-200 bg-slate-50 p-4 text-sm font-bold">
    <div>Anggota</div>
    <div>Buku</div>
    <div>Judul</div>
    <div>Tanggal Pinjam</div>
    <div>Tanggal Kembali</div>
    <div>Status</div>
  </div>

  <!-- ITEM 1 -->
  <div class="grid min-w-[900px] grid-cols-6 gap-4 border-b border-slate-100 p-4 text-sm items-start">
    <div>
      👤<br>
      <strong>Luthfi Dwi Apriyadi</strong>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg"
           class="w-16 rounded-lg shadow-sm">
    </div>

    <div>
      <strong>Tentang Kamu</strong><br>
      <small>Tere Liye</small>
    </div>

    <div>23/Maret/2026</div>

    <div>30/Maret/2026</div>

    <div>
      <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs text-emerald-700">
        Dipinjam
      </span>
    </div>
  </div>

  <!-- ITEM 2 -->
  <div class="grid min-w-[900px] grid-cols-6 gap-4 p-4 text-sm items-start">
    <div>
      👤<br>
      <strong>Muhammad Zaky Sadewa</strong>
    </div>

    <div>
      <img src="https://images-na.ssl-images-amazon.com/images/I/81wgcld4wxL.jpg"
           class="w-16 rounded-lg shadow-sm">
    </div>

    <div>
      <strong>Atomic Habits</strong><br>
      <small>James Clear</small>
    </div>

    <div>21/Maret/2026</div>

    <div>26/Maret/2026</div>

    <div>
      <span class="rounded-full bg-amber-100 px-2 py-1 text-xs text-amber-800">
        Dikembalikan
      </span>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('adminChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['25 Mar', '26 Mar', '27 Mar', '28 Mar', '29 Mar'],
        datasets: [{
            label: 'Peminjaman',
            data: [1, 1, 1, 1, 1],
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(147,197,253,0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush