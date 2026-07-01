@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')
@section('active_page', 'kelola-peminjaman')
@section('page_title', 'Kelola Peminjaman')
@section('page_subtitle', 'Kelola dan pantau seluruh aktivitas peminjaman buku.')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-md">
  <div class="flex flex-col gap-4 border-b border-slate-200 bg-gradient-to-r from-[#1E376E]/8 via-[#1E376E]/5 to-teal-500/10 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex items-center gap-2">
      <i class="bi bi-journal-text text-[#1E376E] text-lg"></i>
      <h2 class="font-semibold text-[#1E376E]">Daftar Peminjaman</h2>
    </div>
    
    <form method="GET" action="{{ route('admin.peminjaman') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
      <div class="relative w-full sm:max-w-xs">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input name="q" value="{{ request('q') }}" type="search" placeholder="Cari anggota, judul, atau pengarang..."
          class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20">
      </div>
      <select name="category" onchange="this.form.submit()"
        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-[#1E376E] focus:outline-none focus:ring-2 focus:ring-[#1E376E]/20 sm:w-44">
        <option value="all">Semua Kategori</option>
        @foreach ($categories ?? [] as $cat)
          <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
      </select>
    </form>
  </div>

  <div class="overflow-x-auto px-5 pb-2">
    <table class="w-full min-w-[960px] text-sm table-auto">
      <thead>
        <tr class="border-b border-slate-200 bg-slate-50/90 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
          <th class="px-3 py-3">Anggota</th>
          <th class="px-3 py-3 w-[70px]">Buku</th>
          <th class="px-3 py-3">Informasi</th>
          <th class="px-3 py-3 whitespace-nowrap">Tgl Pinjam</th>
          <th class="px-3 py-3 whitespace-nowrap">Tgl Kembali</th>
          <th class="px-3 py-3 text-center whitespace-nowrap">Status</th>
          <th class="px-3 py-3 whitespace-nowrap">Denda</th>
          <th class="px-3 py-3 text-center w-[100px]">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @php
          $statusBadges = [
            'Mengajukan' => ['bg' => 'bg-violet-500', 'icon' => 'bi-send-fill', 'label' => 'Mengajukan'],
            'Sedang Dipinjam' => ['bg' => 'bg-sky-500', 'icon' => 'bi-book-fill', 'label' => 'Dipinjam'],
            'Terlambat' => ['bg' => 'bg-red-500', 'icon' => 'bi-exclamation-circle-fill', 'label' => 'Terlambat'],
            'Dikembalikan' => ['bg' => 'bg-emerald-500', 'icon' => 'bi-check-circle-fill', 'label' => 'Kembali'],
            'Sudah Lunas' => ['bg' => 'bg-teal-600', 'icon' => 'bi-cash-coin', 'label' => 'Lunas'],
            'Ditolak' => ['bg' => 'bg-rose-500', 'icon' => 'bi-x-circle-fill', 'label' => 'Ditolak'],
            'Dibatalkan' => ['bg' => 'bg-slate-500', 'icon' => 'bi-slash-circle', 'label' => 'Batal'],
          ];
        @endphp

        @forelse($loans as $loan)
          @php
            $user = $loan->peminjaman ? $loan->peminjaman->user : null;
            $badge = $statusBadges[$loan->status_label] ?? ['bg' => 'bg-slate-500', 'icon' => 'bi-info-circle', 'label' => $loan->status_label];
            $borrowDateStr = $loan->tgl_Peminjaman ? \Carbon\Carbon::parse($loan->tgl_Peminjaman)->locale('id')->translatedFormat('d M Y') : '—';
            $dueDateStr = $loan->tgl_pengembalian ? \Carbon\Carbon::parse($loan->tgl_pengembalian)->locale('id')->translatedFormat('d M Y') : '—';
          @endphp
          <tr class="hover:bg-slate-50/80 transition-colors">
            <td class="px-3 py-2.5 align-middle">
              <div class="flex items-center gap-2 min-w-[140px]">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#1E376E]/10 text-[#1E376E] text-xs font-bold">
                  {{ $user ? $user->initials() : '—' }}
                </div>
                <div>
                  <p class="font-semibold text-slate-800 leading-tight">{{ $user ? $user->nama_pengguna : '—' }}</p>
                  <p class="text-[11px] text-slate-500">{{ $user ? $user->nim : '—' }}</p>
                </div>
              </div>
            </td>
            <td class="px-3 py-2.5 align-middle">
              <img src="{{ $loan->book_cover }}" onerror="this.onerror=null;this.src='{{ asset('images/Cover buku 1.jpg') }}';" class="w-12 h-16 object-cover rounded-lg border border-slate-200" alt="">
            </td>
            <td class="px-3 py-2.5 align-middle">
              <p class="font-semibold text-slate-800 leading-tight">{{ $loan->buku->judul_buku }}</p>
              <p class="text-[11px] text-slate-500">{{ $loan->buku->nomor_panggil }}</p>
            </td>
            <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">{{ $borrowDateStr }}</td>
            <td class="px-3 py-2.5 align-middle text-xs whitespace-nowrap">{{ $dueDateStr }}</td>
            <td class="px-3 py-2.5 align-middle text-center">
              <span class="inline-flex items-center gap-1 rounded-md {{ $badge['bg'] }} px-2 py-0.5 text-[11px] font-bold text-white whitespace-nowrap">
                <i class="bi {{ $badge['icon'] }}"></i> {{ $badge['label'] }}
              </span>
            </td>
            <td class="px-3 py-2.5 align-middle text-xs font-semibold text-slate-700 whitespace-nowrap">
              @if($loan->denda_display != '—' && $loan->status_transaksi == \App\Models\DetailPeminjaman::STATUS_TERLAMBAT)
                <span class="text-rose-600">{{ $loan->denda_display }}</span>
              @else
                <span>{{ $loan->denda_display }}</span>
              @endif
            </td>
            <td class="px-3 py-2.5 align-middle text-center">
              <div class="flex items-center justify-center gap-1">
                @if($loan->status_transaksi == \App\Models\DetailPeminjaman::STATUS_MENGAJUKAN)
                  <button type="button" onclick="document.getElementById('approveConfirmModal-{{ $loan->kode_detail }}').classList.remove('hidden')" class="rounded bg-emerald-500 px-2 py-1 text-xs font-bold text-white hover:bg-emerald-600">
                    Setujui
                  </button>
                  <button type="button" onclick="document.getElementById('rejectConfirmModal-{{ $loan->kode_detail }}').classList.remove('hidden')" class="rounded bg-rose-500 px-2 py-1 text-xs font-bold text-white hover:bg-rose-600">
                    Tolak
                  </button>
                @elseif(in_array($loan->status_transaksi, [\App\Models\DetailPeminjaman::STATUS_DIPINJAM, \App\Models\DetailPeminjaman::STATUS_TERLAMBAT]))
                  <button type="button" onclick="document.getElementById('editLoanModal-{{ $loan->kode_detail }}').classList.remove('hidden')" class="rounded bg-sky-500 px-2.5 py-1 text-xs font-bold text-white hover:bg-sky-600">
                    Kembali
                  </button>
                @else
                  <span class="text-xs text-slate-400 font-semibold">—</span>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 py-12 text-center text-sm text-slate-500">
              <i class="bi bi-journal-x mb-2 block text-3xl text-slate-300"></i>
              Tidak ada data peminjaman.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="flex items-center justify-center border-t border-slate-100 px-5 py-4">
    {{ $loans->links() }}
  </div>
</div>

<!-- ==================== MODALS LOOP ==================== -->
@foreach($loans as $loan)
  @php
    $user = $loan->peminjaman ? $loan->peminjaman->user : null;
  @endphp
  
  @if($loan->status_transaksi == \App\Models\DetailPeminjaman::STATUS_MENGAJUKAN)
    <!-- APPROVE MODAL -->
    <div id="approveConfirmModal-{{ $loan->kode_detail }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
      <form method="POST" action="{{ route('admin.peminjaman.approve', $loan->kode_detail) }}" class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl text-center">
        @csrf
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
          <i class="bi bi-check2-circle text-2xl"></i>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Setujui Peminjaman?</h3>
        <p class="mt-1 text-xs text-slate-500">Pengajuan buku <span class="font-semibold text-slate-700">"{{ $loan->buku->judul_buku }}"</span> oleh <span class="font-semibold text-slate-700">{{ $user ? $user->nama_pengguna : '—' }}</span> akan disetujui.</p>

        <div class="mt-5 flex justify-center gap-2">
          <button type="button" onclick="document.getElementById('approveConfirmModal-{{ $loan->kode_detail }}').classList.add('hidden')"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
            Batal
          </button>
          <button type="submit" class="rounded-lg bg-emerald-500 px-4 py-2 text-xs font-medium text-white hover:bg-emerald-600">
            Setujui
          </button>
        </div>
      </form>
    </div>

    <!-- REJECT MODAL -->
    <div id="rejectConfirmModal-{{ $loan->kode_detail }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
      <form method="POST" action="{{ route('admin.peminjaman.reject', $loan->kode_detail) }}" class="w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl text-center">
        @csrf
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
          <i class="bi bi-exclamation-triangle text-2xl"></i>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Tolak Peminjaman?</h3>
        <p class="mt-1 text-xs text-slate-500">Pengajuan buku <span class="font-semibold text-slate-700">"{{ $loan->buku->judul_buku }}"</span> oleh <span class="font-semibold text-slate-700">{{ $user ? $user->nama_pengguna : '—' }}</span> akan ditolak.</p>

        <div class="mt-5 flex justify-center gap-2">
          <button type="button" onclick="document.getElementById('rejectConfirmModal-{{ $loan->kode_detail }}').classList.add('hidden')"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
            Batal
          </button>
          <button type="submit" class="rounded-lg bg-rose-500 px-4 py-2 text-xs font-medium text-white hover:bg-rose-600">
            Tolak
          </button>
        </div>
      </form>
    </div>
  @elseif(in_array($loan->status_transaksi, [\App\Models\DetailPeminjaman::STATUS_DIPINJAM, \App\Models\DetailPeminjaman::STATUS_TERLAMBAT]))
    <!-- EDIT/RETURN MODAL -->
    <div id="editLoanModal-{{ $loan->kode_detail }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex justify-center items-center p-4">
      <form method="POST" action="{{ route('admin.peminjaman.update', $loan->kode_detail) }}" class="w-full max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
        @csrf
        @method('PATCH')
        <h3 class="mb-1 text-lg font-semibold text-[#1E376E] text-left">Proses Pengembalian Buku</h3>
        <p class="mb-6 text-xs text-slate-500 text-left">Konfirmasi pengembalian buku dan status denda.</p>

        <div class="grid gap-6 md:grid-cols-2 text-left text-sm">
          <div class="space-y-3">
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Nama Peminjam</label>
              <input type="text" readonly value="{{ $user ? $user->nama_pengguna : '—' }}" class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Nomor Panggil</label>
              <input type="text" readonly value="{{ $loan->buku->nomor_panggil }}" class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Judul Buku</label>
              <input type="text" readonly value="{{ $loan->buku->judul_buku }}" class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Pengarang</label>
              <input type="text" readonly value="{{ $loan->buku->pengarang }}" class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
            </div>
          </div>

          <div class="space-y-3">
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Status Akhir Pengembalian</label>
              <select name="status" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-[#1E376E] focus:outline-none">
                <option value="Dikembalikan">Dikembalikan (Buku Kembali & Belum Bayar Denda)</option>
                <option value="Sudah Lunas" selected>Sudah Lunas (Buku Kembali & Denda Lunas)</option>
              </select>
              <p class="mt-1 text-[10px] text-slate-500"><strong>Dikembalikan</strong> = Buku diterima, denda dicatat. <strong>Sudah Lunas</strong> = Buku diterima, denda dilunasi/tidak ada.</p>
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Tanggal Peminjaman</label>
              <input name="borrow_date" type="text" readonly value="{{ $loan->tgl_Peminjaman ? $loan->tgl_Peminjaman->format('Y-m-d') : '' }}" class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Tenggat Pengembalian</label>
              <input name="due_date" type="text" readonly value="{{ $loan->tgl_pengembalian ? $loan->tgl_pengembalian->format('Y-m-d') : '' }}" class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-800">
            </div>
            <div>
              <label class="mb-1 block text-xs font-semibold text-slate-600">Total Denda Terakumulasi</label>
              <input name="denda" type="text" readonly value="{{ $loan->denda_display }}" class="w-full cursor-not-allowed rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-850 font-bold">
            </div>
          </div>
        </div>

        <div class="mt-8 flex justify-end gap-2 border-t border-slate-200 pt-4">
          <button type="button" onclick="document.getElementById('editLoanModal-{{ $loan->kode_detail }}').classList.add('hidden')"
            class="rounded-lg border border-slate-200 px-5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Kembali</button>
          <button type="submit"
            class="rounded-lg bg-[#1E376E] px-5 py-2 text-sm font-semibold text-white hover:bg-[#162d5c]">Simpan Status</button>
        </div>
      </form>
    </div>
  @endif
@endforeach
@endsection
