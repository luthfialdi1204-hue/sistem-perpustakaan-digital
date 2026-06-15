@extends('layouts.mahasiswa')

@section('title', 'Riwayat Peminjaman')
@section('active_page', 'riwayat')
@section('page_title', 'Riwayat Peminjaman')
@section('page_subtitle', 'Lihat seluruh aktivitas peminjaman buku Anda.')

@section('content')

<!-- RINGKASAN STATISTIK -->
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
  <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-[#1E376E] ring-4 ring-[#1E376E]/15">
      <i class="bi bi-journal-bookmark text-white text-xl"></i>
    </div>
    <div class="min-w-0">
      <p class="text-sm text-slate-500">Total Peminjaman</p>
      <p class="text-2xl font-bold text-slate-800">{{ (int)($stats['active_total'] ?? 0) }}</p>
      <p class="mt-0.5 text-xs text-emerald-600 flex items-center gap-0.5">
        <i class="bi bi-arrow-up-short text-base"></i> Semua transaksi tercatat
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
        $activeTotal = max(0, (int)($stats['active_total'] ?? 0));
        $selesai = max(0, (int)($stats['selesai'] ?? 0));
        $selesaiPct = $activeTotal ? round(($selesai / $activeTotal) * 100, 1) : 0;
      @endphp
      <p class="mt-0.5 text-xs text-slate-500">{{ rtrim(rtrim((string)$selesaiPct, '0'), '.') }}% dari total aktif</p>
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
        $terlambatPct = $activeTotal ? round(($terlambat / $activeTotal) * 100, 1) : 0;
      @endphp
      <p class="mt-0.5 text-xs text-slate-500">{{ rtrim(rtrim((string)$terlambatPct, '0'), '.') }}% dari total aktif</p>
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

<!-- FILTER -->
<div class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
  <div class="flex items-center gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 to-teal-500/10 px-5 py-3.5">
    <i class="bi bi-funnel text-[#1E376E]"></i>
    <h2 class="font-semibold text-[#1E376E]">Filter Riwayat</h2>
  </div>
  <div class="p-5">
    <form method="GET" action="{{ route('mahasiswa.riwayat') }}" class="grid gap-3 md:grid-cols-4">
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Status</label>
        <select name="status"
          class="w-full rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2.5 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
          @php $st = (string)($filters['status'] ?? 'all'); @endphp
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
      <div class="md:col-span-2">
        <label class="mb-1 block text-xs font-semibold text-slate-600">Pencarian</label>
        <div class="relative">
          <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
          <input name="q" value="{{ $filters['q'] ?? '' }}" type="search" placeholder="Cari judul, pengarang, atau kode buku..."
            class="w-full rounded-xl border border-slate-200 bg-slate-50/80 py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
        </div>
      </div>
      <div class="flex gap-2 md:col-span-4">
        <a href="{{ route('mahasiswa.riwayat') }}"
          class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
          Reset
        </a>
        <button type="submit"
          class="inline-flex items-center gap-1.5 rounded-xl bg-[#1E376E] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#162d5c]">
          <i class="bi bi-funnel-fill text-xs"></i> Tampilkan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- DAFTAR RIWAYAT -->
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
  <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 via-[#1E376E]/5 to-teal-500/10 px-5 py-4">
    <div class="flex items-center gap-2">
      <i class="bi bi-journal-text text-[#1E376E] text-lg"></i>
      <h3 class="font-semibold text-[#1E376E]">Daftar Riwayat Peminjaman</h3>
    </div>
    <span class="text-xs font-medium text-slate-500">{{ (int)($meta['total'] ?? 0) }} transaksi</span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm table-auto">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
          <th class="px-3 py-2 w-[70px]">Buku</th>
          <th class="px-3 py-2">Informasi</th>
          <th class="px-3 py-2 whitespace-nowrap">Tgl Pinjam</th>
          <th class="px-3 py-2 whitespace-nowrap">Tgl Kembali</th>
          <th class="px-3 py-2 text-center whitespace-nowrap">Status</th>
          <th class="px-3 py-2 whitespace-nowrap">Denda</th>
          <th class="px-3 py-2 min-w-[88px] text-center">Aksi</th>
        </tr>
      </thead>
      <tbody id="historyTableBody" class="divide-y divide-slate-100">
        @foreach(($rows ?? []) as $row)
          @php
            $status = (string)($row['status'] ?? '');
            $canCancel = $status === 'Mengajukan';
            $isLate = $status === 'Terlambat';
          @endphp
          <tr class="group hover:bg-slate-50/80 transition-colors">
            <td class="px-3 py-2.5 align-middle">
              <img src="{{ $row['cover'] ?? '' }}" alt="{{ $row['bookTitle'] ?? '' }}"
                class="w-14 h-[72px] object-cover rounded-lg border border-slate-200 shadow-sm">
            </td>
            <td class="px-3 py-2.5 align-middle">
              <p class="font-semibold text-slate-800 leading-tight">{{ $row['bookTitle'] ?? '' }}</p>
              <p class="text-xs text-slate-500">{{ $row['bookAuthor'] ?? '' }} · <span class="text-[#1E376E] font-medium">{{ $row['bookCode'] ?? '' }}</span></p>
              <p class="text-[11px] text-slate-400 mt-0.5"><i class="bi bi-clock"></i> {{ $row['submittedAt'] ?? '' }}</p>
            </td>
            <td class="px-3 py-2.5 align-middle text-slate-700 whitespace-nowrap text-xs">
              {{ $status === 'Mengajukan' ? 'Menunggu' : ($row['borrowAt'] ?? '—') }}
            </td>
            <td class="px-3 py-2.5 align-middle text-xs text-slate-700 whitespace-nowrap">
              {{ $row['dueAt'] ?? '—' }}
              @if($isLate && ($row['telat'] ?? '') !== '')
                <p class="text-[11px] font-medium text-red-500">Telat {{ $row['telat'] }}</p>
              @elseif(($row['dueNote'] ?? '') !== '' && !in_array($status, ['Ditolak','Dibatalkan'], true))
                <p class="text-[11px] font-medium text-emerald-600">{{ $row['dueNote'] }}</p>
              @endif
            </td>
            <td class="px-3 py-2.5 align-middle text-center">
              <span class="inline-flex items-center gap-1 rounded-md bg-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-700 whitespace-nowrap">
                {{ $status ?: '—' }}
              </span>
            </td>
            <td class="px-3 py-2.5 align-middle whitespace-nowrap text-xs font-semibold {{ $isLate ? 'text-red-600' : 'text-emerald-600' }}">
              {{ ($row['denda'] ?? '') === '' ? '—' : $row['denda'] }}
            </td>
            <td class="px-3 py-2.5 align-middle text-center">
              <div class="flex items-center justify-center gap-1.5">
                <button type="button" class="js-history-detail inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#1E376E] to-teal-600 text-white shadow-sm hover:brightness-110 transition"
                  data-row='@json($row)'>
                  <i class="bi bi-eye text-sm"></i>
                </button>
                @if($canCancel)
                  <button type="button" class="history-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-600 shadow-sm transition hover:bg-rose-100"
                    data-action="cancel" data-id="{{ $row['id'] ?? 0 }}" title="Batalkan pengajuan">
                    <i class="bi bi-x-circle text-sm"></i>
                  </button>
                @endif
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <p id="historyEmpty" class="{{ empty($rows) ? '' : 'hidden' }} px-5 py-12 text-center text-sm text-slate-500">
      <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
      Belum ada riwayat peminjaman.
    </p>
  </div>
  <div class="border-t border-slate-100 px-5 py-4">
    @php
      $current = (int)($meta['current_page'] ?? 1);
      $last = (int)($meta['last_page'] ?? 1);
    @endphp
    @if($last > 1)
      <div class="flex items-center justify-center gap-1 text-sm">
        <div class="inline-flex overflow-hidden rounded-lg border border-slate-200">
          @for($p = 1; $p <= $last; $p++)
            <a href="{{ route('mahasiswa.riwayat', array_merge(request()->query(), ['page' => $p])) }}"
              class="border-r border-slate-200 px-4 py-1.5 text-xs {{ $p === $current ? 'bg-[#1E376E] text-white' : 'bg-white text-slate-700 hover:bg-slate-50' }}">
              {{ $p }}
            </a>
          @endfor
        </div>
      </div>
    @endif
  </div>
</div>

<!-- MODAL DETAIL -->
<div id="detailModal" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full h-full p-4" tabindex="-1" aria-hidden="true">
  <div class="relative w-[850px] max-w-[95%] rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
    <h2 class="mb-4 border-b border-slate-200 pb-2 text-lg font-semibold text-[#1E376E]">Detail Riwayat</h2>
    <h3 class="mb-4 text-lg font-bold text-slate-800">Detail Buku</h3>
    <div class="grid grid-cols-1 gap-6 text-sm sm:grid-cols-2">
      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Kode Buku</label>
          <input type="text" id="modalBookCode" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Judul Buku</label>
          <input type="text" id="modalBookTitle" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Penerbit</label>
          <input type="text" id="modalPublisher" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Pengarang</label>
          <input type="text" id="modalAuthor" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Kategori</label>
          <input type="text" id="modalCategory" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <img id="modalCover" src="" alt="" class="mt-3 w-28 rounded-lg border border-slate-200 shadow-sm">
      </div>
      <div class="space-y-3">
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Tahun Terbit</label>
          <input type="text" id="modalYearPublished" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Peminjaman</label>
          <input type="text" id="modalBorrowDate" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Kembali</label>
          <input type="text" id="modalDueDate" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
          <div id="modalStatusBadge" class="mt-1"></div>
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Telat</label>
          <input type="text" id="modalTelat" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
        <div>
          <label class="mb-1 block text-xs font-medium text-slate-500">Denda</label>
          <input type="text" id="modalDenda" readonly class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-800">
        </div>
      </div>
    </div>
    <div class="mt-6 flex flex-wrap justify-end gap-2">
      <button type="button" id="modalCancelBtn" class="hidden rounded-lg border border-rose-200 bg-rose-50 px-5 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
        <i class="bi bi-x-circle me-1"></i> Batalkan Pengajuan
      </button>
      <button type="button" onclick="closeDetailModal()"
        class="rounded-lg bg-slate-500 px-6 py-2 text-white transition hover:bg-slate-600">
        Kembali
      </button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI BATALKAN -->
<div id="cancelConfirmModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
  <!-- Backdrop -->
  <div id="cancelBackdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeCancelModal()"></div>
  <!-- Panel -->
  <div class="relative w-full max-w-sm rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden"
       style="animation: slideUp .2s ease">
    <!-- Top accent -->
    <div class="h-1.5 w-full bg-gradient-to-r from-rose-500 to-rose-400"></div>
    <div class="px-6 pt-6 pb-5">
      <!-- Icon -->
      <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 ring-8 ring-rose-50">
        <i class="bi bi-exclamation-triangle-fill text-3xl text-rose-500"></i>
      </div>
      <!-- Text -->
      <h3 class="text-center text-lg font-bold text-slate-800">Batalkan Pengajuan?</h3>
      <p class="mt-2 text-center text-sm text-slate-500 leading-relaxed">
        Pengajuan peminjaman akan dibatalkan dan tidak dapat dikembalikan ke status semula.
      </p>
      <!-- Buttons -->
      <div class="mt-6 flex gap-3">
        <button type="button" onclick="closeCancelModal()"
          class="flex-1 rounded-xl border border-slate-200 bg-white py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-95">
          Tidak, Kembali
        </button>
        <button type="button" id="cancelConfirmOkBtn"
          class="flex-1 rounded-xl bg-rose-500 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-600 active:scale-95 flex items-center justify-center gap-1.5">
          <i class="bi bi-x-circle"></i> Ya, Batalkan
        </button>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes slideUp {
  from { opacity: 0; transform: translateY(16px) scale(.97); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

@endsection

@push('scripts')
<script>
// Server-rendered page: keep only modal interaction.
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.js-history-detail');
  if (!btn) return;
  const raw = btn.getAttribute('data-row') || 'null';
  const row = JSON.parse(raw);
  if (!row) return;

  document.getElementById('modalBookCode').value = row.bookCode || '—';
  document.getElementById('modalBookTitle').value = row.bookTitle || '—';
  document.getElementById('modalPublisher').value = row.bookPublisher || row.publisher || '—';
  document.getElementById('modalAuthor').value = row.bookAuthor || '—';
  document.getElementById('modalCategory').value = row.bookCategory || row.category || '—';
  document.getElementById('modalYearPublished').value = row.bookYear || row.yearPublished || '—';
  document.getElementById('modalBorrowDate').value = row.borrowAt || '—';
  document.getElementById('modalDueDate').value = row.dueAt || '—';
  document.getElementById('modalTelat').value = row.telat || '—';
  document.getElementById('modalDenda').value = row.denda || '—';
  document.getElementById('modalStatusBadge').textContent = row.status || '—';
  const cover = document.getElementById('modalCover');
  cover.src = row.cover || '';
  cover.alt = row.bookTitle || '';

  fbShow('detailModal');
});
let historyRows = [];
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const historyListUrl = @json(route('mahasiswa.peminjaman.list'));
const historyCancelUrl = (id) => @json(url('/mahasiswa/peminjaman')) + '/' + id + '/batal';
const ROWS_PER_PAGE = 10;
let currentPage = 1;
let lastPage = 1;
let totalRows = 0;
let currentFilters = { q: '', status: 'all' };

async function apiJson(url, options = {}) {
  const res = await fetch(url, {
    headers: {
      Accept: 'application/json',
      'X-CSRF-TOKEN': csrf,
      ...(options.body ? { 'Content-Type': 'application/json' } : {}),
      ...(options.headers || {}),
    },
    credentials: 'same-origin',
    ...options,
  });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.message || 'Permintaan gagal.');
  return data;
}

async function loadHistory() {
  const params = new URLSearchParams();
  if (currentFilters.q) params.set('q', currentFilters.q);
  if (currentFilters.status && currentFilters.status !== 'all') params.set('status', currentFilters.status);
  params.set('page', String(currentPage));
  params.set('per_page', String(ROWS_PER_PAGE));

  const json = await apiJson(`${historyListUrl}?${params.toString()}`);
  historyRows = json.data || [];
  const meta = json.meta || {};
  lastPage = Number(meta.last_page || 1) || 1;
  totalRows = Number(meta.total || 0) || 0;
  updateStatsFromServer(json.stats || {});
  render();
}

let viewingDetailId = null;
let toastTimer = null;

const tbody = document.getElementById('historyTableBody');
const emptyMsg = document.getElementById('historyEmpty');
const detailModal = document.getElementById('detailModal');
const statusFilterEl = document.getElementById('historyStatusFilter');
const searchEl = document.getElementById('historySearch');
const countEl = document.getElementById('historyCount');
const paginationEl = document.getElementById('paginationContainer');

const INACTIVE_STATUSES = ['Ditolak', 'Dibatalkan'];
const NO_DENDA_STATUSES = ['Mengajukan', 'Ditolak', 'Dibatalkan'];

function escHtml(str) {
  return String(str ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function formatShortDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso + 'T00:00:00');
  if (Number.isNaN(d.getTime())) return iso;
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
}

function formatLongDate(iso) {
  if (!iso) return '—';
  const d = new Date(iso + 'T00:00:00');
  if (Number.isNaN(d.getTime())) return iso;
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

function parseDendaAmount(str) {
  if (!str || str === '—') return 0;
  const n = parseInt(String(str).replace(/\D/g, ''), 10);
  return Number.isNaN(n) ? 0 : n;
}

function formatRp(n) {
  return 'Rp' + n.toLocaleString('id-ID');
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
  if (NO_DENDA_STATUSES.includes(row.status)) {
    return '<span class="text-xs text-slate-400">—</span>';
  }
  if (row.status === 'Sudah Lunas') {
    return '<span class="font-semibold text-teal-600 text-xs">Rp0 <span class="text-[11px] font-normal text-slate-400">(lunas)</span></span>';
  }
  const hasFine = row.denda && row.denda !== 'Rp0' && row.denda !== '—';
  const cls = (row.status === 'Terlambat' || (row.status === 'Dikembalikan' && hasFine))
    ? 'font-semibold text-red-600 text-xs'
    : 'font-semibold text-emerald-600 text-xs';
  const extra = row.status === 'Terlambat' && row.telat
    ? `<span class="text-[11px] text-red-500 ml-1">(${escHtml(row.telat)})</span>`
    : '';
  return `<span class="${cls}">${escHtml(row.denda || 'Rp0')}</span>${extra}`;
}

function dueDateCell(row) {
  const due = formatShortDate(row.dueIso);
  let note = '';
  if (row.status === 'Terlambat' && row.telat) {
    note = `<p class="text-[11px] font-medium text-red-500">Telat ${escHtml(row.telat)}</p>`;
  } else if (row.dueNote && !INACTIVE_STATUSES.includes(row.status)) {
    note = `<p class="text-[11px] font-medium text-emerald-600">${escHtml(row.dueNote)}</p>`;
  }
  return `<p class="text-slate-700 text-xs whitespace-nowrap">${escHtml(due)}</p>${note}`;
}

function borrowDateCell(row) {
  if (row.status === 'Mengajukan') {
    return '<span class="text-xs text-violet-600 font-medium">Menunggu</span>';
  }
  if (INACTIVE_STATUSES.includes(row.status)) {
    return '<span class="text-xs text-slate-400">—</span>';
  }
  return escHtml(formatShortDate(row.borrowIso));
}

function telatDisplay(row) {
  if (NO_DENDA_STATUSES.includes(row.status)) return '—';
  if (row.telat) return row.telat;
  return 'Tidak ada';
}

function dendaDisplay(row) {
  if (NO_DENDA_STATUSES.includes(row.status)) return '—';
  if (row.status === 'Sudah Lunas') return 'Rp0 (lunas)';
  return row.denda && row.denda !== '—' ? row.denda : 'Rp0';
}

function rowClass(row) {
  if (row.status === 'Dibatalkan' || row.status === 'Ditolak') {
    return 'group bg-slate-50/60 hover:bg-slate-50 transition-colors';
  }
  return 'group hover:bg-slate-50/80 transition-colors';
}

function updateStatsFromServer(stats) {
  const activeTotal = Number(stats?.active_total || 0) || 0;
  const selesai = Number(stats?.selesai || 0) || 0;
  const terlambat = Number(stats?.terlambat || 0) || 0;
  const totalDenda = Number(stats?.total_denda || 0) || 0;

  const pct = (n) => (activeTotal ? ((n / activeTotal) * 100).toFixed(1).replace('.0', '') : '0');

  document.getElementById('statTotal').textContent = activeTotal;
  document.getElementById('statSelesai').textContent = selesai;
  document.getElementById('statSelesaiPct').textContent = `${pct(selesai)}% dari total aktif`;
  document.getElementById('statTerlambat').textContent = terlambat;
  document.getElementById('statTerlambatPct').textContent = `${pct(terlambat)}% dari total aktif`;
  document.getElementById('statDenda').textContent = formatRp(totalDenda);
  document.getElementById('statDendaNoteText').textContent = terlambat
    ? `${terlambat} pinjaman terlambat`
    : 'Tidak ada denda';
}

function actionCell(row) {
  const detailBtn = `
    <button type="button" title="Lihat detail" data-action="detail" data-id="${row.id}"
      class="history-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#1E376E] to-teal-600 text-white shadow-sm hover:brightness-110 transition">
      <i class="bi bi-eye text-sm"></i>
    </button>`;
  if (row.status === 'Mengajukan') {
    return `
      <div class="flex items-center justify-center gap-1.5">
        ${detailBtn}
        <button type="button" title="Batalkan pengajuan" data-action="cancel" data-id="${row.id}"
          class="history-action-btn inline-flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-600 shadow-sm transition hover:bg-rose-100">
          <i class="bi bi-x-circle text-sm"></i>
        </button>
      </div>`;
  }
  return `<div class="flex justify-center">${detailBtn}</div>`;
}

function rowHtml(row) {
  return `
    <tr class="${rowClass(row)}" data-id="${row.id}">
      <td class="px-3 py-2.5 align-middle">
        <img src="${escHtml(row.cover)}" alt="${escHtml(row.bookTitle)}"
          class="w-14 h-[72px] object-cover rounded-lg border border-slate-200 shadow-sm">
      </td>
      <td class="px-3 py-2.5 align-middle">
        <p class="font-semibold text-slate-800 leading-tight">${escHtml(row.bookTitle)}</p>
        <p class="text-xs text-slate-500">${escHtml(row.bookAuthor)} · <span class="text-[#1E376E] font-medium">${escHtml(row.bookCode)}</span></p>
        <p class="text-[11px] text-slate-400 mt-0.5"><i class="bi bi-clock"></i> ${escHtml(row.submittedAt)}</p>
      </td>
      <td class="px-3 py-2.5 align-middle text-slate-700 whitespace-nowrap text-xs">${borrowDateCell(row)}</td>
      <td class="px-3 py-2.5 align-middle">${dueDateCell(row)}</td>
      <td class="px-3 py-2.5 align-middle text-center">${statusBadgeHtml(row.status)}</td>
      <td class="px-3 py-2.5 align-middle whitespace-nowrap">${dendaCell(row)}</td>
      <td class="px-3 py-2.5 align-middle text-center">${actionCell(row)}</td>
    </tr>`;
}

function renderPagination() {
  if (!paginationEl) return;
  paginationEl.innerHTML = '';
  if (lastPage <= 1) return;

  const addBtn = (label, page, active, disabled) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = label;
    btn.disabled = disabled;
    btn.className = `min-w-[2rem] rounded-lg px-2.5 py-1.5 text-xs font-medium transition ${active ? 'bg-[#1E376E] text-white' : disabled ? 'text-slate-300' : 'text-slate-600 hover:bg-slate-100'}`;
    if (!disabled && page) btn.addEventListener('click', () => { currentPage = page; loadHistory().catch((e) => alert(e.message)); });
    paginationEl.appendChild(btn);
  };

  addBtn('‹', currentPage - 1, false, currentPage <= 1);
  for (let p = 1; p <= lastPage; p++) {
    if (lastPage > 7 && p > 2 && p < lastPage - 1 && Math.abs(p - currentPage) > 1) {
      if (p === 3 || p === lastPage - 2) {
        const span = document.createElement('span');
        span.className = 'px-1 text-slate-400';
        span.textContent = '…';
        paginationEl.appendChild(span);
      }
      continue;
    }
    addBtn(String(p), p, p === currentPage, false);
  }
  addBtn('›', currentPage + 1, false, currentPage >= lastPage);
}

function render() {
  tbody.innerHTML = historyRows.map((r) => rowHtml(r)).join('');
  const hasAny = totalRows > 0;
  const hasVisible = historyRows.length > 0;
  emptyMsg.classList.toggle('hidden', hasVisible);

  if (countEl) {
    countEl.textContent = hasAny
      ? `Menampilkan ${historyRows.length} dari ${totalRows} transaksi`
      : '';
  }

  if (!hasVisible && hasAny) {
    emptyMsg.innerHTML = '<i class="bi bi-funnel mb-2 block text-3xl text-slate-300"></i>Tidak ada data sesuai filter.';
    emptyMsg.classList.remove('hidden');
  } else if (!hasAny) {
    emptyMsg.innerHTML = '<i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>Belum ada riwayat peminjaman.';
  }

  renderPagination();
}

function openDetailModal(id) {
  const row = historyRows.find((r) => r.id === id);
  if (!row) return;
  viewingDetailId = id;
  document.getElementById('modalBookCode').value = row.bookCode;
  document.getElementById('modalBookTitle').value = row.bookTitle;
  document.getElementById('modalPublisher').value = row.publisher;
  document.getElementById('modalAuthor').value = row.bookAuthor;
  document.getElementById('modalCategory').value = row.category;
  document.getElementById('modalYearPublished').value = row.yearPublished;
  document.getElementById('modalBorrowDate').value = formatLongDate(row.borrowIso);
  document.getElementById('modalDueDate').value = formatLongDate(row.dueIso);
  document.getElementById('modalTelat').value = telatDisplay(row);
  document.getElementById('modalDenda').value = dendaDisplay(row);
  document.getElementById('modalStatusBadge').innerHTML = statusBadgeHtml(row.status);
  document.getElementById('modalCover').src = row.cover;
  document.getElementById('modalCover').alt = row.bookTitle;

  const cancelBtn = document.getElementById('modalCancelBtn');
  if (row.status === 'Mengajukan') {
    cancelBtn.classList.remove('hidden');
    cancelBtn.onclick = () => {
      closeDetailModal();
      openCancelConfirm(id);
    };
  } else {
    cancelBtn.classList.add('hidden');
    cancelBtn.onclick = null;
  }

  fbShow('detailModal');
  detailModal.setAttribute('aria-hidden', 'false');
}

function closeDetailModal() {
  viewingDetailId = null;
  fbHide('detailModal');
  detailModal.setAttribute('aria-hidden', 'true');
}

let pendingCancelId = null;

function openCancelConfirm(id) {
  pendingCancelId = id;
  document.getElementById('cancelConfirmModal').classList.remove('hidden');
}

function closeCancelModal() {
  pendingCancelId = null;
  document.getElementById('cancelConfirmModal').classList.add('hidden');
}

document.getElementById('cancelConfirmOkBtn').addEventListener('click', () => {
  if (pendingCancelId !== null) {
    const id = pendingCancelId;
    closeCancelModal();
    cancelApplication(id);
  }
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && !document.getElementById('cancelConfirmModal').classList.contains('hidden')) {
    closeCancelModal();
  }
});

function cancelApplication(id) {
  const cancelUrl = "{{ url('/mahasiswa/peminjaman') }}";
  fetch(`${cancelUrl}/${id}/batal`, {
    method: 'PATCH',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'Accept': 'application/json',
    },
    credentials: 'same-origin'
  }).then(async response => {
    const data = await response.json();
    if (!response.ok) {
      alert(data.message || 'Gagal membatalkan peminjaman.');
      return;
    }
    // Refresh halaman agar data server-rendered ikut terupdate
    window.location.reload();
  }).catch(error => {
    alert('Terjadi kesalahan: ' + error.message);
  });
}

function showToast(message) {
  const toast = document.getElementById('historyToast');
  document.getElementById('historyToastText').textContent = message;
  toast.classList.remove('translate-y-4', 'opacity-0');
  toast.classList.add('translate-y-0', 'opacity-100');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => {
    toast.classList.add('translate-y-4', 'opacity-0');
    toast.classList.remove('translate-y-0', 'opacity-100');
  }, 3200);
}

tbody.addEventListener('click', (e) => {
  const btn = e.target.closest('.history-action-btn');
  if (!btn) return;
  const id = Number(btn.dataset.id);
  const action = btn.dataset.action;
  if (action === 'detail') openDetailModal(id);
  if (action === 'cancel') openCancelConfirm(id);
});



detailModal.addEventListener('click', (e) => {
  if (e.target === detailModal) closeDetailModal();
});

document.addEventListener('keydown', (e) => {
  if (e.key !== 'Escape') return;
  if (!detailModal.classList.contains('hidden')) closeDetailModal();
});

const applyBtn = document.getElementById('historyFilterApply');
if (applyBtn && statusFilterEl && searchEl) {
  applyBtn.addEventListener('click', () => {
    currentFilters = { q: (searchEl.value || '').trim(), status: statusFilterEl.value || 'all' };
    currentPage = 1;
    loadHistory().catch((e) => alert(e.message));
  });
}

const resetBtn = document.getElementById('historyFilterReset');
if (resetBtn && statusFilterEl && searchEl) {
  resetBtn.addEventListener('click', () => {
    statusFilterEl.value = 'all';
    searchEl.value = '';
    currentFilters = { q: '', status: 'all' };
    currentPage = 1;
    loadHistory().catch((e) => alert(e.message));
  });
}

if (searchEl && statusFilterEl) {
  searchEl.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      currentFilters = { q: (searchEl.value || '').trim(), status: statusFilterEl.value || 'all' };
      currentPage = 1;
      loadHistory().catch((e) => alert(e.message));
    }
  });
}

// Server-rendered mode: no automatic fetch.
</script>
@endpush
