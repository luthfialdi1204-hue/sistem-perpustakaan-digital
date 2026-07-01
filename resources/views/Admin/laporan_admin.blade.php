@extends('layouts.admin')

@section('title', 'Laporan Peminjaman')
@section('active_page', 'laporan')
@section('page_title', 'Laporan Peminjaman')
@section('page_subtitle', 'Kelola dan analisis data peminjaman buku secara mudah dan interaktif.')

@section('content')
<div class="space-y-6">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
    <div class="relative w-full sm:w-auto">
      <button type="button" id="exportAllDataBtn" data-dropdown-toggle="exportAllDropdown" data-dropdown-placement="bottom-end"
        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#1E376E] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162d5c] sm:w-auto">
        <i class="bi bi-download"></i> Unduh Laporan
        <i class="bi bi-chevron-down text-xs opacity-80"></i>
      </button>
      <div id="exportAllDropdown"
        class="z-20 hidden w-52 divide-y divide-gray-100 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
        <button type="button" id="exportAllPdfBtn"
          class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50">
          <i class="bi bi-file-pdf text-rose-500"></i> Export PDF
        </button>
        <button type="button" id="exportAllExcelBtn"
          class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-slate-700 hover:bg-slate-50">
          <i class="bi bi-file-earmark-spreadsheet text-emerald-600"></i> Export Excel (CSV)
        </button>
      </div>
    </div>
  </div>

  {{-- Kartu statistik --}}
  <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#1E376E] ring-4 ring-[#1E376E]/15">
        <i class="bi bi-journal-arrow-up text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Sedang Dipinjam</p>
        <p class="text-2xl font-bold text-slate-800">{{ (int)($stats['sedang_dipinjam'] ?? 0) }}</p>
        <p class="mt-0.5 text-xs text-emerald-600 flex items-center gap-0.5">
          <i class="bi bi-arrow-up-short text-base"></i> <span id="statTotalTrend">—</span>
        </p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-teal-600 ring-4 ring-teal-600/15">
        <i class="bi bi-check2-square text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Dikembalikan</p>
        <p class="text-2xl font-bold text-slate-800">{{ (int)($stats['dikembalikan'] ?? 0) }}</p>
        @php
          $total = max(0, (int)($stats['total'] ?? 0));
          $dikembalikan = max(0, (int)($stats['dikembalikan'] ?? 0));
          $dikembalikanPct = $total ? round(($dikembalikan / $total) * 100, 1) : 0;
        @endphp
        <p class="mt-0.5 text-xs text-slate-500">{{ rtrim(rtrim((string)$dikembalikanPct, '0'), '.') }}% dari total</p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-violet-500 ring-4 ring-violet-500/15">
        <i class="bi bi-send text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Mengajukan</p>
        <p class="text-2xl font-bold text-slate-800">{{ (int)($stats['mengajukan'] ?? 0) }}</p>
        @php
          $mengajukan = max(0, (int)($stats['mengajukan'] ?? 0));
          $mengajukanPct = $total ? round(($mengajukan / $total) * 100, 1) : 0;
        @endphp
        <p class="mt-0.5 text-xs text-slate-500">{{ rtrim(rtrim((string)$mengajukanPct, '0'), '.') }}% dari total</p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-rose-500 ring-4 ring-rose-500/15">
        <i class="bi bi-cash-coin text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Total Denda</p>
        <p class="text-2xl font-bold text-slate-800">Rp{{ number_format((int)($stats['total_denda'] ?? 0), 0, ',', '.') }}</p>
        <p class="mt-0.5 text-xs text-rose-500 flex items-center gap-0.5">
          <i class="bi bi-exclamation-circle"></i> <span>{{ ((int)($stats['terlambat'] ?? 0)) ? ((int)($stats['terlambat'] ?? 0)).' pinjaman terlambat' : 'Tidak ada denda' }}</span>
        </p>
      </div>
    </div>
  </div>

  {{-- Filter --}}
  <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center gap-2">
      <i class="bi bi-funnel text-[#1E376E]"></i>
      <h3 class="font-semibold text-[#1E376E]">Filter Laporan</h3>
    </div>
    <form method="GET" action="{{ route('admin.laporan') }}" class="grid grid-cols-1 gap-4 md:grid-cols-12 md:items-end">
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Mulai</label>
        <div class="relative">
          <i class="bi bi-calendar3 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input name="start" value="{{ $filters['start'] ?? '' }}" type="date"
            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Selesai</label>
        <div class="relative">
          <i class="bi bi-calendar3 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
          <input name="end" value="{{ $filters['end'] ?? '' }}" type="date"
            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-3 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
        @php $st = (string)($filters['status'] ?? 'all'); @endphp
        <select name="status"
          class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
          <option value="all" @selected($st==='all')>Semua Status</option>
          <option value="Mengajukan" @selected($st==='Mengajukan')>Mengajukan</option>
          <option value="Sedang Dipinjam" @selected($st==='Sedang Dipinjam')>Sedang Dipinjam</option>
          <option value="Terlambat" @selected($st==='Terlambat')>Terlambat</option>
          <option value="Dikembalikan" @selected($st==='Dikembalikan')>Dikembalikan</option>
          <option value="Sudah Lunas" @selected($st==='Sudah Lunas')>Sudah Lunas</option>
          <option value="Ditolak" @selected($st==='Ditolak')>Ditolak</option>
          <option value="Dibatalkan" @selected($st==='Dibatalkan')>Dibatalkan</option>
        </select>
      </div>
      <div class="md:col-span-4">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pencarian</label>
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
          <input name="q" value="{{ $filters['q'] ?? '' }}" type="search" placeholder="Cari buku atau nama anggota..."
            class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="flex gap-2 md:col-span-2">
        <a href="{{ route('admin.laporan') }}"
          class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
          Reset
        </a>
        <button type="submit"
          class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#1E376E] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#162d5c] transition">
          <i class="bi bi-funnel-fill text-xs"></i> Tampilkan
        </button>
      </div>
    </form>
  </div>

  {{-- Konten utama: tabel + sidebar --}}
  <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    <div class="xl:col-span-2">
      <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
        <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 via-[#1E376E]/5 to-teal-500/10 px-5 py-4">
          <i class="bi bi-journal-text text-[#1E376E] text-lg"></i>
          <h3 class="font-semibold text-[#1E376E]">Riwayat Peminjaman</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full min-w-[720px] text-sm">
            <thead>
              <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-3 py-3 w-10">No</th>
                <th class="px-3 py-3">Anggota</th>
                <th class="px-3 py-3">Buku</th>
                <th class="px-3 py-3 whitespace-nowrap">Tgl Pinjam</th>
                <th class="px-3 py-3 whitespace-nowrap">Tgl Kembali</th>
                <th class="px-3 py-3 text-center">Status</th>
                <th class="px-3 py-3">Denda</th>
                <th class="px-3 py-3 text-center w-12">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach(($rows ?? []) as $i => $row)
                <tr class="hover:bg-slate-50/80 transition-colors">
                  <td class="px-3 py-2.5 text-slate-500 text-xs">{{ (($meta['current_page'] ?? 1) - 1) * ($meta['per_page'] ?? 10) + $i + 1 }}</td>
                  <td class="px-3 py-2.5">
                    <span class="font-medium text-slate-800 text-xs">{{ $row['member'] ?? '' }}</span>
                  </td>
                  <td class="px-3 py-2.5">
                    <p class="font-medium text-slate-800 text-xs leading-tight">{{ $row['bookTitle'] ?? '' }}</p>
                    <p class="text-[11px] text-slate-500">{{ $row['bookAuthor'] ?? '' }}</p>
                  </td>
                  <td class="px-3 py-2.5 text-xs text-slate-700 whitespace-nowrap">{{ $row['borrowAt'] ?? '—' }}</td>
                  <td class="px-3 py-2.5 text-xs text-slate-700 whitespace-nowrap">{{ $row['dueAt'] ?? '—' }}</td>
                  <td class="px-3 py-2.5 text-center text-xs">{{ $row['status'] ?? '—' }}</td>
                  <td class="px-3 py-2.5 text-xs">{{ $row['denda'] ?? '—' }}</td>
                  <td class="px-3 py-2.5 text-center text-xs">
                    <button type="button" class="js-report-detail inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#1E376E] to-teal-600 text-white shadow-sm hover:brightness-110 transition"
                      data-row='@json($row)'>
                      <i class="bi bi-eye text-sm"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <p id="reportEmpty" class="{{ empty($rows) ? '' : 'hidden' }} px-5 py-10 text-center text-sm text-slate-500">
          <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
          Tidak ada data yang cocok dengan filter.
        </p>
        <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
          <p class="text-xs text-slate-500">Menampilkan {{ count($rows ?? []) }} dari {{ (int)($meta['total'] ?? 0) }} data</p>
          @php
            $current = (int)($meta['current_page'] ?? 1);
            $last = (int)($meta['last_page'] ?? 1);
          @endphp
          @if($last > 1)
            <div class="flex flex-wrap items-center justify-center gap-2 text-sm">
              @if($current > 1)
                <a href="{{ route('admin.laporan', array_merge(request()->query(), ['page' => $current - 1])) }}"
                  class="flex items-center justify-center h-10 px-4 rounded-xl border border-slate-200 bg-white text-[#1E376E] font-semibold transition hover:bg-slate-50 shadow-sm text-xs gap-1">
                  ← Prev
                </a>
              @endif

              @php
                $start = $current === 1 ? 1 : max(1, min($current - 1, $last - 1));
                $end = $current === 1 ? min(2, $last) : min($current, $last);
              @endphp
              @for($p = $start; $p <= $end; $p++)
                @if($p === $current)
                  <span class="flex items-center justify-center h-10 w-10 rounded-xl bg-[#1E376E] text-white font-semibold shadow-sm text-xs">
                    {{ $p }}
                  </span>
                @else
                  <a href="{{ route('admin.laporan', array_merge(request()->query(), ['page' => $p])) }}"
                    class="flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200 bg-white text-[#1E376E] font-semibold transition hover:bg-slate-50 shadow-sm text-xs">
                    {{ $p }}
                  </a>
                @endif
              @endfor

              @if($current < $last)
                <a href="{{ route('admin.laporan', array_merge(request()->query(), ['page' => $current + 1])) }}"
                  class="flex items-center justify-center h-10 px-4 rounded-xl bg-[#1E376E] text-white font-semibold shadow-sm transition hover:bg-[#162d5c] text-xs gap-1">
                  Next →
                </a>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <h3 class="mb-4 font-semibold text-[#1E376E]">Ringkasan Peminjaman</h3>
        <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
          <div class="relative h-40 w-40 shrink-0">
            <canvas id="summaryChart"></canvas>
            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
              <span id="chartCenterTotal" class="text-2xl font-bold text-slate-800">0</span>
              <span class="text-xs text-slate-500">Total</span>
            </div>
          </div>
          <ul id="chartLegend" class="flex-1 space-y-2 text-sm"></ul>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <h3 class="mb-4 flex items-center gap-2 font-semibold text-[#1E376E]">
          <i class="bi bi-cash-stack"></i> Denda Tertinggi
        </h3>
        <ul id="topFinesList" class="space-y-3"></ul>
      </div>
    </div>
  </div>
</div>

<div id="reportDetailModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="max-h-[95vh] w-full max-w-4xl overflow-y-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <div class="mb-6 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-[#1E376E]">Detail Peminjaman</h3>
      <button type="button" id="reportDetailClose" data-modal-hide="reportDetailModal" class="text-2xl leading-none text-slate-500 hover:text-slate-700" aria-label="Tutup">&times;</button>
    </div>
    <div class="grid gap-6 lg:grid-cols-2">
      <div class="space-y-3">
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Nama Peminjam</label>
        <input id="detailMember" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Nomor Panggil</label>
        <input id="detailBookCode" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku</label>
        <input id="detailBookTitle" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang</label>
        <input id="detailAuthor" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
        <input id="detailCategory" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">ISBN</label>
        <input id="detailIsbn" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Lokasi Rak</label>
        <input id="detailRack" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <img id="detailCover" src="" alt="" class="h-40 w-28 rounded-lg border border-slate-200 object-cover shadow-sm">
      </div>
      <div class="space-y-3">
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
        <input id="detailStatus" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Peminjaman</label>
        <input id="detailBorrowDisplay" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Kembali</label>
        <input id="detailDueDisplay" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Keterlambatan</label>
        <input id="detailTelat" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Denda</label>
        <input id="detailDenda" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
      </div>
    </div>
    <div class="mt-6 flex justify-end gap-2 border-t border-slate-200 pt-4">
      <button type="button" id="detailBtnBack" data-modal-hide="reportDetailModal" class="rounded-lg border border-slate-200 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Kembali</button>
      <div class="relative">
        <button type="button" id="exportDataBtn" data-dropdown-toggle="exportDropdown" data-dropdown-placement="bottom-end" class="inline-flex items-center gap-2 rounded-lg bg-[#1E376E] px-5 py-2 text-sm font-semibold text-white hover:bg-[#162d5c]">
          Export <i class="bi bi-chevron-down text-xs"></i>
        </button>
        <div id="exportDropdown" class="z-10 hidden w-44 divide-y divide-gray-100 rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
          <button type="button" id="exportPdfBtn" class="block w-full px-4 py-2 text-left text-sm hover:bg-slate-50">PDF</button>
          <button type="button" id="exportExcelBtn" class="block w-full px-4 py-2 text-left text-sm hover:bg-slate-50">Excel</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  /* ── Helpers ── */
  const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

  function formatDisplayDate(iso) {
    if (!iso) return '—';
    const [y, m, d] = iso.split('-').map(Number);
    if (!y || !m || !d) return '—';
    return `${String(d).padStart(2,'0')}/${MONTHS_ID[m-1]}/${y}`;
  }

  function parseDenda(val) {
    if (!val || val === '—') return 0;
    return parseInt(String(val).replace(/[^\d]/g, ''), 10) || 0;
  }

  function formatRp(n) {
    return 'Rp' + n.toLocaleString('id-ID');
  }

  function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;');
  }

  function csvEscape(val) {
    const s = String(val ?? '');
    return /[",\n]/.test(s) ? `"${s.replace(/"/g,'""')}"` : s;
  }

  /* ── Server data ── */
  const serverStats = @json($stats ?? []);
  const serverRows  = @json($rows ?? []);
  const serverTopFines = @json($top_fines ?? []);
  const listApiUrl  = @json(route('admin.laporan.list'));

  /* ── 1. Doughnut Chart ── */
  let summaryChartInstance = null;

  function buildChart(stats) {
    const ctx = document.getElementById('summaryChart');
    if (!ctx) return;

    const total       = Number(stats.total || 0);
    const dikembalikan = Number(stats.dikembalikan || stats.selesai || 0);
    const terlambat   = Number(stats.terlambat || 0);
    const mengajukan  = Number(stats.mengajukan || 0);
    const sedangDipinjam = Number(stats.sedang_dipinjam || 0);
    const ditolak     = Number(stats.ditolak || 0);
    const dibatalkan  = Number(stats.dibatalkan || 0);

    const data   = [dikembalikan, terlambat, sedangDipinjam, mengajukan];
    const labels = ['Dikembalikan','Terlambat','Sedang Dipinjam','Mengajukan'];
    const colors = ['#10b981','#ef4444','#0ea5e9','#8b5cf6'];

    if (ditolak > 0) { data.push(ditolak); labels.push('Ditolak'); colors.push('#f43f5e'); }
    if (dibatalkan > 0) { data.push(dibatalkan); labels.push('Dibatalkan'); colors.push('#64748b'); }

    // Center text
    const centerEl = document.getElementById('chartCenterTotal');
    if (centerEl) centerEl.textContent = total;

    if (summaryChartInstance) summaryChartInstance.destroy();
    summaryChartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{ data, backgroundColor: colors, borderWidth: 0, hoverOffset: 6 }],
      },
      options: {
        cutout: '68%',
        plugins: { legend: { display: false } },
        maintainAspectRatio: true,
      },
    });

    // Legend
    const legend = document.getElementById('chartLegend');
    if (legend) {
      legend.innerHTML = labels.map((label, i) => {
        const n = data[i];
        const p = total ? ((n / total) * 100).toFixed(1) : '0';
        return `<li class="flex items-center justify-between gap-2">
          <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" style="background:${colors[i]}"></span>${label}</span>
          <span class="font-semibold text-slate-700">${n} <span class="text-slate-400 font-normal">(${p}%)</span></span>
        </li>`;
      }).join('');
    }
  }

  buildChart(serverStats);

  /* ── 2. Top fines sidebar ── */
  function buildTopFines(rows) {
    const list = document.getElementById('topFinesList');
    if (!list) return;
    const ranked = rows
      .map(r => ({ member: r.member || '', amt: parseDenda(r.denda) }))
      .filter(x => x.amt > 0)
      .sort((a, b) => b.amt - a.amt)
      .slice(0, 5);
    if (!ranked.length) {
      list.innerHTML = '<li class="text-sm text-slate-400">Belum ada denda tercatat</li>';
      return;
    }
    list.innerHTML = ranked.map((item, i) => `
      <li class="flex items-center justify-between gap-2">
        <span class="flex items-center gap-2 text-sm text-slate-700">
          <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#1E376E]/10 text-xs font-bold text-[#1E376E]">${i+1}</span>
          ${escHtml(item.member)}
        </span>
        <span class="text-sm font-semibold text-red-600">${formatRp(item.amt)}</span>
      </li>`).join('');
  }

  buildTopFines(serverTopFines);

  /* ── 3. Status badges in table ── */
  const BADGES = {
    Mengajukan: '<span class="inline-flex items-center gap-1 rounded-md bg-violet-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-send-fill"></i> Mengajukan</span>',
    'Sedang Dipinjam': '<span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-book-fill"></i> Sedang Dipinjam</span>',
    Terlambat: '<span class="inline-flex items-center gap-1 rounded-md bg-red-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-exclamation-circle-fill"></i> Terlambat</span>',
    Dikembalikan: '<span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-box-arrow-in-left"></i> Dikembalikan</span>',
    'Sudah Lunas': '<span class="inline-flex items-center gap-1 rounded-md bg-teal-600 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-cash-coin"></i> Sudah Lunas</span>',
    Ditolak: '<span class="inline-flex items-center gap-1 rounded-md bg-rose-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-x-circle-fill"></i> Ditolak</span>',
    Dibatalkan: '<span class="inline-flex items-center gap-1 rounded-md bg-slate-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-slash-circle"></i> Dibatalkan</span>',
  };

  // Replace plain-text status cells with badges
  document.querySelectorAll('tbody tr').forEach(tr => {
    const statusTd = tr.querySelector('td:nth-child(6)');
    if (!statusTd) return;
    const txt = statusTd.textContent.trim();
    if (BADGES[txt]) statusTd.innerHTML = BADGES[txt];
  });

  /* ── 4. Detail modal ── */
  function openDetail(rowData) {
    const row = (typeof rowData === 'string') ? JSON.parse(rowData) : rowData;
    document.getElementById('detailMember').value     = row.member || '';
    document.getElementById('detailBookCode').value   = row.bookCode || '';
    document.getElementById('detailBookTitle').value   = row.bookTitle || '';
    document.getElementById('detailAuthor').value      = row.bookAuthor || '';
    document.getElementById('detailCategory').value    = row.category || '';
    document.getElementById('detailIsbn').value        = row.isbn || '—';
    document.getElementById('detailRack').value        = row.rack || '—';
    const coverEl = document.getElementById('detailCover');
    if (coverEl) { coverEl.src = row.cover || ''; coverEl.alt = row.bookTitle || ''; }
    document.getElementById('detailStatus').value      = row.status || '';
    document.getElementById('detailBorrowDisplay').value = row.borrowAt || formatDisplayDate(row.borrowIso);
    document.getElementById('detailDueDisplay').value    = row.dueAt || formatDisplayDate(row.dueIso);
    document.getElementById('detailTelat').value        = row.telat || '—';
    document.getElementById('detailDenda').value        = row.denda || '—';
    if (typeof fbShow === 'function') fbShow('reportDetailModal');
    else document.getElementById('reportDetailModal')?.classList.remove('hidden');
  }

  function closeDetail() {
    if (typeof fbHide === 'function') fbHide('reportDetailModal');
    else document.getElementById('reportDetailModal')?.classList.add('hidden');
  }

  // Wire detail buttons
  document.querySelectorAll('.js-report-detail').forEach(btn => {
    btn.addEventListener('click', () => {
      const raw = btn.getAttribute('data-row');
      if (raw) openDetail(raw);
    });
  });

  document.getElementById('reportDetailClose')?.addEventListener('click', closeDetail);
  document.getElementById('detailBtnBack')?.addEventListener('click', closeDetail);

  // Close modal when clicking backdrop
  const modalEl = document.getElementById('reportDetailModal');
  if (modalEl) {
    modalEl.addEventListener('click', (e) => { if (e.target === modalEl) closeDetail(); });
  }

  // Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDetail();
  });

  /* ── 5. Export (fetch ALL rows from API, then export) ── */
  let allRowsCache = null;

  async function fetchAllRows() {
    if (allRowsCache) return allRowsCache;
    try {
      const params = new URLSearchParams(window.location.search);
      params.set('per_page', '10000');
      params.set('page', '1');
      const res = await fetch(`${listApiUrl}?${params.toString()}`, { headers: { Accept: 'application/json' } });
      const json = await res.json();
      allRowsCache = Array.isArray(json.data) ? json.data : serverRows;
    } catch {
      allRowsCache = serverRows;
    }
    return allRowsCache;
  }

  // Export single detail as PDF
  document.getElementById('exportPdfBtn')?.addEventListener('click', () => {
    const fields = [
      ['Nama Peminjam', document.getElementById('detailMember')?.value],
      ['Nomor Panggil', document.getElementById('detailBookCode')?.value],
      ['Judul Buku', document.getElementById('detailBookTitle')?.value],
      ['Pengarang', document.getElementById('detailAuthor')?.value],
      ['Kategori', document.getElementById('detailCategory')?.value],
      ['ISBN', document.getElementById('detailIsbn')?.value],
      ['Lokasi Rak', document.getElementById('detailRack')?.value],
      ['Status', document.getElementById('detailStatus')?.value],
      ['Tgl Peminjaman', document.getElementById('detailBorrowDisplay')?.value],
      ['Tgl Kembali', document.getElementById('detailDueDisplay')?.value],
      ['Keterlambatan', document.getElementById('detailTelat')?.value],
      ['Denda', document.getElementById('detailDenda')?.value],
    ];
    const trHtml = fields.map(([l,v]) => `<tr><th style="text-align:left;padding:6px 10px;background:#f8fafc">${escHtml(l)}</th><td style="padding:6px 10px">${escHtml(v||'—')}</td></tr>`).join('');
    const w = window.open('','_blank');
    if (w) {
      w.document.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><title>Detail Peminjaman</title><style>body{font-family:system-ui;padding:24px}table{border-collapse:collapse;width:100%;max-width:500px}th,td{border:1px solid #e2e8f0;font-size:13px}</style></head><body><h2>Detail Peminjaman</h2><table>${trHtml}</table></body></html>`);
      w.document.close();
      w.focus();
      setTimeout(() => w.print(), 300);
    }
  });

  // Export single detail as Excel/CSV
  document.getElementById('exportExcelBtn')?.addEventListener('click', () => {
    const fields = [
      ['Nama Peminjam', document.getElementById('detailMember')?.value],
      ['Nomor Panggil', document.getElementById('detailBookCode')?.value],
      ['Judul Buku', document.getElementById('detailBookTitle')?.value],
      ['Pengarang', document.getElementById('detailAuthor')?.value],
      ['Kategori', document.getElementById('detailCategory')?.value],
      ['ISBN', document.getElementById('detailIsbn')?.value],
      ['Lokasi Rak', document.getElementById('detailRack')?.value],
      ['Status', document.getElementById('detailStatus')?.value],
      ['Tgl Peminjaman', document.getElementById('detailBorrowDisplay')?.value],
      ['Tgl Kembali', document.getElementById('detailDueDisplay')?.value],
      ['Keterlambatan', document.getElementById('detailTelat')?.value],
      ['Denda', document.getElementById('detailDenda')?.value],
    ];
    const lines = fields.map(([l,v]) => [csvEscape(l), csvEscape(v||'—')].join(','));
    const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = `detail-peminjaman-${new Date().toISOString().slice(0,10)}.csv`;
    a.click();
  });

  // Export ALL as PDF
  document.getElementById('exportAllPdfBtn')?.addEventListener('click', async () => {
    const rows = await fetchAllRows();
    const trHtml = rows.map((row, i) => `<tr><td>${i+1}</td><td>${escHtml(row.member)}</td><td>${escHtml(row.bookTitle)}</td><td>${escHtml(row.borrowAt || formatDisplayDate(row.borrowIso))}</td><td>${escHtml(row.dueAt || formatDisplayDate(row.dueIso))}</td><td>${escHtml(row.status)}</td><td>${escHtml(row.denda)}</td></tr>`).join('');
    const w = window.open('','_blank');
    if (w) {
      w.document.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><title>Laporan Peminjaman</title><style>body{font-family:system-ui;padding:24px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #e2e8f0;padding:8px;font-size:12px}th{background:#f1f5f9}</style></head><body><h1>Laporan Peminjaman</h1><p style="color:#64748b;font-size:13px">Dicetak pada: ${new Date().toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'})} — Total: ${rows.length} data</p><table><thead><tr><th>No</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th></tr></thead><tbody>${trHtml}</tbody></table></body></html>`);
      w.document.close();
      w.focus();
      setTimeout(() => w.print(), 300);
    }
  });

  // Export ALL as Excel/CSV
  document.getElementById('exportAllExcelBtn')?.addEventListener('click', async () => {
    const rows = await fetchAllRows();
    const headers = ['No','Anggota','Buku','Tgl Pinjam','Tgl Kembali','Status','Denda'];
    const lines = [headers.join(',')];
    rows.forEach((row, i) => {
      lines.push([i+1, row.member, row.bookTitle, row.borrowAt || formatDisplayDate(row.borrowIso), row.dueAt || formatDisplayDate(row.dueIso), row.status, row.denda].map(csvEscape).join(','));
    });
    const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = `laporan-peminjaman-${new Date().toISOString().slice(0,10)}.csv`;
    a.click();
  });

  /* ── 6. Dropdown toggles ── */
  function setupDropdown(btnId, menuId) {
    const btn = document.getElementById(btnId);
    const menu = document.getElementById(menuId);
    if (!btn || !menu) return;
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      menu.classList.toggle('hidden');
    });
    document.addEventListener('click', () => menu.classList.add('hidden'));
    menu.addEventListener('click', () => menu.classList.add('hidden'));
  }
  setupDropdown('exportAllDataBtn', 'exportAllDropdown');
  setupDropdown('exportDataBtn', 'exportDropdown');

  /* ── 7. Stat trend label ── */
  const trendEl = document.getElementById('statTotalTrend');
  if (trendEl) trendEl.textContent = 'Aktif saat ini';
})();
</script>
@endpush
