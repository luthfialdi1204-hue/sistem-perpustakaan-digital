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
        <i class="bi bi-journal-bookmark text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Total Peminjaman</p>
        <p class="text-2xl font-bold text-slate-800">{{ (int)($stats['total'] ?? 0) }}</p>
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
        <p class="text-sm text-slate-500">Selesai</p>
        <p class="text-2xl font-bold text-slate-800">{{ (int)($stats['selesai'] ?? 0) }}</p>
        @php
          $total = max(0, (int)($stats['total'] ?? 0));
          $selesai = max(0, (int)($stats['selesai'] ?? 0));
          $selesaiPct = $total ? round(($selesai / $total) * 100, 1) : 0;
        @endphp
        <p class="mt-0.5 text-xs text-slate-500">{{ rtrim(rtrim((string)$selesaiPct, '0'), '.') }}% dari total</p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
      <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-500 ring-4 ring-amber-500/15">
        <i class="bi bi-clock-history text-white text-xl"></i>
      </div>
      <div class="min-w-0">
        <p class="text-sm text-slate-500">Terlambat</p>
        <p class="text-2xl font-bold text-slate-800">{{ (int)($stats['terlambat'] ?? 0) }}</p>
        @php
          $terlambat = max(0, (int)($stats['terlambat'] ?? 0));
          $terlambatPct = $total ? round(($terlambat / $total) * 100, 1) : 0;
        @endphp
        <p class="mt-0.5 text-xs text-slate-500">{{ rtrim(rtrim((string)$terlambatPct, '0'), '.') }}% dari total</p>
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
                  <td class="px-3 py-2.5 text-slate-500 text-xs">{{ (($meta['current_page'] ?? 1) - 1) * ($meta['per_page'] ?? 5) + $i + 1 }}</td>
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
            <div class="flex flex-wrap items-center justify-center gap-1 text-sm">
              <div class="inline-flex overflow-hidden rounded-lg border border-slate-200">
                @for($p = 1; $p <= $last; $p++)
                  <a href="{{ route('admin.laporan', array_merge(request()->query(), ['page' => $p])) }}"
                    class="border-r border-slate-200 px-4 py-1.5 text-xs {{ $p === $current ? 'bg-[#1E376E] text-white' : 'bg-white text-slate-700 hover:bg-slate-50' }}">
                    {{ $p }}
                  </a>
                @endfor
              </div>
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
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Kode Buku</label>
        <input id="detailBookCode" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku</label>
        <input id="detailBookTitle" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang</label>
        <input id="detailAuthor" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Kategori</label>
        <input id="detailCategory" type="text" readonly class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm"></div>
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
/* server-rendered: disable old AJAX/chart script
const MONTHS_ID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
const ROWS_PER_PAGE = 5;
let currentPage = 1;
let summaryChartInstance = null;
let lastPage = 1;
let totalRows = 0;
let currentFilters = { q: '', status: 'all', start: '', end: '' };

function formatDisplayDate(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-').map(Number);
  if (!y || !m || !d) return '';
  return `${String(d).padStart(2, '0')}/${MONTHS_ID[m - 1]}/${y}`;
}

function parseDenda(val) {
  if (!val || val === '—') return 0;
  return parseInt(String(val).replace(/[^\d]/g, ''), 10) || 0;
}

function formatRp(n) {
  return 'Rp' + n.toLocaleString('id-ID');
}

let reportRows = [];

let currentDetailRow = null;

function escHtml(s) {
  return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}

function statusBadgeHtml(status) {
  const badges = {
    Mengajukan: '<span class="inline-flex items-center gap-1 rounded-md bg-violet-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-send-fill"></i> Mengajukan</span>',
    'Sedang Dipinjam': '<span class="inline-flex items-center gap-1 rounded-md bg-sky-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-book-fill"></i> Sedang Dipinjam</span>',
    Terlambat: '<span class="inline-flex items-center gap-1 rounded-md bg-red-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-exclamation-circle-fill"></i> Terlambat</span>',
    Dikembalikan: '<span class="inline-flex items-center gap-1 rounded-md bg-emerald-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-box-arrow-in-left"></i> Dikembalikan</span>',
    'Sudah Lunas': '<span class="inline-flex items-center gap-1 rounded-md bg-teal-600 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-cash-coin"></i> Sudah Lunas</span>',
    Ditolak: '<span class="inline-flex items-center gap-1 rounded-md bg-rose-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-x-circle-fill"></i> Ditolak</span>',
    Dibatalkan: '<span class="inline-flex items-center gap-1 rounded-md bg-slate-500 px-2.5 py-1 text-[11px] font-bold text-white shadow-sm whitespace-nowrap"><i class="bi bi-slash-circle"></i> Dibatalkan</span>',
  };
  return badges[status] || `<span class="inline-flex rounded-md bg-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-700">${escHtml(status)}</span>`;
}

function dendaCell(row) {
  if (row.status === 'Mengajukan' || row.status === 'Ditolak' || row.status === 'Dibatalkan') {
    return '<span class="text-xs text-slate-400">—</span>';
  }
  if (row.status === 'Sudah Lunas') {
    return '<span class="font-semibold text-teal-600 text-xs">Rp0 <span class="text-[11px] font-normal text-slate-400">(lunas)</span></span>';
  }
  const hasFine = row.denda && row.denda !== 'Rp0' && row.denda !== '—';
  const cls = (row.status === 'Terlambat' || (row.status === 'Dikembalikan' && hasFine))
    ? 'font-semibold text-red-600 text-xs'
    : 'font-semibold text-emerald-600 text-xs';
  const extra = row.status === 'Terlambat' && row.telat ? `<span class="text-[11px] text-red-500 ml-1">(${escHtml(row.telat)})</span>` : '';
  return `<span class="${cls}">${escHtml(row.denda || 'Rp0')}</span>${extra}`;
}

function dueDateCell(row) {
  const du = formatDisplayDate(row.dueIso);
  let note = '';
  if (row.telatNote) note = `<p class="text-[11px] text-red-500">${escHtml(row.telatNote)}</p>`;
  else if (row.dueNote) note = `<p class="text-[11px] text-emerald-600">${escHtml(row.dueNote)}</p>`;
  return `<p class="text-xs text-slate-700 whitespace-nowrap">${escHtml(du)}</p>${note}`;
}

function memberInitials(name) {
  const p = name.trim().split(/\s+/);
  return p.length >= 2 ? (p[0][0] + p[1][0]).toUpperCase() : name.slice(0, 2).toUpperCase();
}

function rowHtml(row, index) {
  const br = formatDisplayDate(row.borrowIso);
  return `
    <tr class="hover:bg-slate-50/80 transition-colors">
      <td class="px-3 py-2.5 text-slate-500 text-xs">${index}</td>
      <td class="px-3 py-2.5">
        <div class="flex items-center gap-2">
          <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#1E376E]/10 text-[10px] font-bold text-[#1E376E]">${memberInitials(row.member)}</div>
          <span class="font-medium text-slate-800 text-xs">${escHtml(row.member)}</span>
        </div>
      </td>
      <td class="px-3 py-2.5">
        <p class="font-medium text-slate-800 text-xs leading-tight">${escHtml(row.bookTitle)}</p>
        <p class="text-[11px] text-slate-500">${escHtml(row.bookAuthor)}</p>
      </td>
      <td class="px-3 py-2.5 text-xs text-slate-700 whitespace-nowrap">${escHtml(br)}</td>
      <td class="px-3 py-2.5">${dueDateCell(row)}</td>
      <td class="px-3 py-2.5 text-center">${statusBadgeHtml(row.status)}</td>
      <td class="px-3 py-2.5">${dendaCell(row)}</td>
      <td class="px-3 py-2.5 text-center">
        <button type="button" data-action="detail" data-report-id="${row.id}" class="report-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-[#1E376E]">
          <i class="bi bi-three-dots-vertical"></i>
        </button>
      </td>
    </tr>`;
}

function updateStats(stats) {
  const total = Number(stats?.total || 0) || 0;
  const selesai = Number(stats?.selesai || 0) || 0;
  const terlambat = Number(stats?.terlambat || 0) || 0;
  const mengajukan = Number(stats?.mengajukan || 0) || 0;
  const sedangDipinjam = Number(stats?.sedang_dipinjam || 0) || 0;
  const ditolak = Number(stats?.ditolak || 0) || 0;
  const dibatalkan = Number(stats?.dibatalkan || 0) || 0;
  const totalDenda = Number(stats?.total_denda || 0) || 0;

  const pct = (n) => (total ? ((n / total) * 100).toFixed(1) : '0');
  const set = (id, v) => { const e = document.getElementById(id); if (e) e.textContent = v; };
  set('statTotal', total);
  set('statTotalTrend', 'Dari database');
  set('statSelesai', selesai);
  set('statSelesaiPct', `${pct(selesai)}% dari total`);
  set('statTerlambat', terlambat);
  set('statTerlambatPct', `${pct(terlambat)}% dari total`);
  set('statDenda', formatRp(totalDenda));
  set('statDendaNote', `${terlambat} pinjaman terlambat`);
  set('chartCenterTotal', total);
  updateChart({ selesai, terlambat, mengajukan, sedangDipinjam, ditolak, dibatalkan, total });
}

function updateChart(counts) {
  const ctx = document.getElementById('summaryChart');
  if (!ctx) return;
  const { selesai, terlambat, mengajukan, sedangDipinjam, ditolak, dibatalkan, total } = counts;
  const data = [selesai, terlambat, sedangDipinjam, mengajukan];
  const labels = ['Selesai', 'Terlambat', 'Sedang Dipinjam', 'Mengajukan'];
  const colors = ['#10b981', '#ef4444', '#0ea5e9', '#8b5cf6'];
  if (ditolak > 0) {
    data.push(ditolak);
    labels.push('Ditolak');
    colors.push('#f43f5e');
  }
  if (dibatalkan > 0) {
    data.push(dibatalkan);
    labels.push('Dibatalkan');
    colors.push('#64748b');
  }
  if (summaryChartInstance) summaryChartInstance.destroy();
  summaryChartInstance = new Chart(ctx, {
    type: 'doughnut',
    data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 0, hoverOffset: 4 }] },
    options: {
      cutout: '68%',
      plugins: { legend: { display: false } },
      maintainAspectRatio: true,
    },
  });
  const legend = document.getElementById('chartLegend');
  if (legend) {
    legend.innerHTML = labels.map((label, i) => {
      const n = data[i];
      const p = total ? ((n / total) * 100).toFixed(1) : 0;
      return `<li class="flex items-center justify-between gap-2">
        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" style="background:${colors[i]}"></span>${label}</span>
        <span class="font-semibold text-slate-700">${n} <span class="text-slate-400 font-normal">(${p}%)</span></span>
      </li>`;
    }).join('');
  }
}

function updateTopFinesFromPageRows() {
  const list = document.getElementById('topFinesList');
  if (!list) return;
  const ranked = [...reportRows]
    .map((r) => ({ member: r.member, amt: parseDenda(r.denda) }))
    .filter((x) => x.amt > 0)
    .sort((a, b) => b.amt - a.amt)
    .slice(0, 3);
  if (!ranked.length) {
    list.innerHTML = '<li class="text-sm text-slate-400">Belum ada denda tercatat</li>';
    return;
  }
  list.innerHTML = ranked.map((item, i) => `
    <li class="flex items-center justify-between gap-2">
      <span class="flex items-center gap-2 text-sm text-slate-700">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[#1E376E]/10 text-xs font-bold text-[#1E376E]">${i + 1}</span>
        ${escHtml(item.member)}
      </span>
      <span class="text-sm font-semibold text-red-600">${formatRp(item.amt)}</span>
    </li>`).join('');
}

function renderPagination() {
  const tbody = document.getElementById('reportTableBody');
  const emptyMsg = document.getElementById('reportEmpty');
  if (tbody) {
    tbody.innerHTML = reportRows.map((r, i) => rowHtml(r, ((currentPage - 1) * ROWS_PER_PAGE) + i + 1)).join('');
  }
  if (emptyMsg) emptyMsg.classList.toggle('hidden', totalRows > 0);
  const info = document.getElementById('paginationInfo');
  if (info) {
    if (!totalRows) info.textContent = 'Menampilkan 0 dari 0 data';
    else {
      const start = (currentPage - 1) * ROWS_PER_PAGE;
      info.textContent = `Menampilkan ${start + 1} – ${Math.min(start + reportRows.length, totalRows)} dari ${totalRows} data`;
    }
  }
  const pag = document.getElementById('paginationContainer');
  if (!pag) return;
  pag.innerHTML = '';
  const addBtn = (label, page, active, disabled) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = label;
    btn.disabled = disabled;
    btn.className = `min-w-[2rem] rounded-lg px-2.5 py-1.5 text-xs font-medium transition ${active ? 'bg-[#1E376E] text-white' : disabled ? 'text-slate-300' : 'text-slate-600 hover:bg-slate-100'}`;
    if (!disabled && page) btn.addEventListener('click', () => { currentPage = page; fetchReportRows(); });
    pag.appendChild(btn);
  };
  addBtn('‹', currentPage - 1, false, currentPage <= 1);
  for (let p = 1; p <= lastPage; p++) {
    if (lastPage > 7 && p > 2 && p < lastPage - 1 && Math.abs(p - currentPage) > 1) {
      if (p === 3 || p === lastPage - 2) {
        const span = document.createElement('span');
        span.className = 'px-1 text-slate-400';
        span.textContent = '…';
        pag.appendChild(span);
      }
      continue;
    }
    addBtn(String(p), p, p === currentPage, false);
  }
  addBtn('›', currentPage + 1, false, currentPage >= lastPage);
}

function resetFilters() {
  document.getElementById('reportSearch').value = '';
  document.getElementById('reportStatus').value = 'all';
  document.getElementById('dateStart').value = '';
  document.getElementById('dateEnd').value = '';
  currentFilters = { q: '', status: 'all', start: '', end: '' };
  currentPage = 1;
  fetchReportRows();
}

function openDetail(id) {
  const row = reportRows.find((r) => r.id === id);
  if (!row) return;
  currentDetailRow = row;
  document.getElementById('detailMember').value = row.member;
  document.getElementById('detailBookCode').value = row.bookCode;
  document.getElementById('detailBookTitle').value = row.bookTitle;
  document.getElementById('detailAuthor').value = row.bookAuthor;
  document.getElementById('detailCategory').value = row.category;
  document.getElementById('detailCover').src = row.cover;
  document.getElementById('detailStatus').value = row.status;
  document.getElementById('detailBorrowDisplay').value = formatDisplayDate(row.borrowIso);
  document.getElementById('detailDueDisplay').value = formatDisplayDate(row.dueIso);
  document.getElementById('detailTelat').value = row.telat || '—';
  document.getElementById('detailDenda').value = row.denda || '—';
  fbShow('reportDetailModal');
}

function closeDetail() {
  currentDetailRow = null;
  fbHide('reportDetailModal');
}

function csvEscape(val) {
  const s = String(val ?? '');
  return /[",\n]/.test(s) ? `"${s.replace(/"/g, '""')}"` : s;
}

function exportAllExcel() {
  const headers = ['No', 'Anggota', 'Buku', 'Tgl Pinjam', 'Tgl Kembali', 'Status', 'Denda'];
  const lines = [headers.join(',')];
  reportRows.forEach((row, i) => {
    lines.push([i + 1, row.member, row.bookTitle, formatDisplayDate(row.borrowIso), formatDisplayDate(row.dueIso), row.status, row.denda].map(csvEscape).join(','));
  });
  const blob = new Blob(['\uFEFF' + lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = `laporan-peminjaman-${new Date().toISOString().slice(0, 10)}.csv`;
  a.click();
}

function exportAllPdf() {
  const rowsHtml = reportRows.map((row, i) => `<tr><td>${i+1}</td><td>${escHtml(row.member)}</td><td>${escHtml(row.bookTitle)}</td><td>${escHtml(formatDisplayDate(row.borrowIso))}</td><td>${escHtml(formatDisplayDate(row.dueIso))}</td><td>${escHtml(row.status)}</td><td>${escHtml(row.denda)}</td></tr>`).join('');
  const w = window.open('', '_blank');
  if (w) {
    w.document.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><title>Laporan</title><style>body{font-family:system-ui;padding:24px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #e2e8f0;padding:8px;font-size:12px}th{background:#f1f5f9}</style></head><body><h1>Laporan Peminjaman</h1><table><thead><tr><th>No</th><th>Anggota</th><th>Buku</th><th>Pinjam</th><th>Kembali</th><th>Status</th><th>Denda</th></tr></thead><tbody>${rowsHtml}</tbody></table></body></html>`);
    w.document.close();
    w.focus();
    setTimeout(() => w.print(), 300);
  }
}

document.getElementById('reportTableBody').addEventListener('click', (e) => {
  const btn = e.target.closest('.report-action-btn');
  if (btn?.dataset.reportId) openDetail(btn.dataset.reportId);
});
document.getElementById('reportDetailClose').addEventListener('click', closeDetail);
document.getElementById('detailBtnBack').addEventListener('click', closeDetail);
document.getElementById('btnApplyFilter').addEventListener('click', () => {
  currentFilters = {
    q: (document.getElementById('reportSearch').value || '').trim(),
    status: document.getElementById('reportStatus').value || 'all',
    start: document.getElementById('dateStart').value || '',
    end: document.getElementById('dateEnd').value || '',
  };
  currentPage = 1;
  fetchReportRows();
});
document.getElementById('btnResetFilter').addEventListener('click', resetFilters);
document.getElementById('exportAllPdfBtn').addEventListener('click', exportAllPdf);
document.getElementById('exportAllExcelBtn').addEventListener('click', exportAllExcel);

async function fetchReportRows() {
  const params = new URLSearchParams();
  if (currentFilters.q) params.set('q', currentFilters.q);
  if (currentFilters.status && currentFilters.status !== 'all') params.set('status', currentFilters.status);
  if (currentFilters.start) params.set('start', currentFilters.start);
  if (currentFilters.end) params.set('end', currentFilters.end);
  params.set('page', String(currentPage));
  params.set('per_page', String(ROWS_PER_PAGE));

  const res = await fetch(`{{ route('admin.laporan.list') }}?${params.toString()}`, { headers: { Accept: "application/json" } });
  const json = await res.json().catch(() => ({}));
  if (!res.ok) {
    throw new Error(json?.message || 'Gagal memuat data laporan.');
  }
  reportRows = Array.isArray(json.data) ? json.data : [];
  const meta = json.meta || {};
  lastPage = Number(meta.last_page || 1) || 1;
  totalRows = Number(meta.total || 0) || 0;
  updateStats(json.stats || {});
  updateTopFinesFromPageRows();
  renderPagination();
}

(async function initReport() {
  try {
    await fetchReportRows();
  } catch (e) {
    reportRows = [];
    alert(e?.message || 'Gagal memuat data laporan.');
  }
})();
*/
</script>
@endpush
