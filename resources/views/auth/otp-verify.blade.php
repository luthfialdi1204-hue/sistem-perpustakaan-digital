@extends('auth.layout')

@section('title', 'Verifikasi OTP — Perpustakaan Digital')
@section('subtitle', 'Verifikasi Kode OTP')

@section('steps')
  @include('auth.partials.forgot-steps', ['currentStep' => 2])
@endsection

@section('content')
@php
  $inputClass = 'w-full rounded-xl border border-white/50 bg-white/90 py-2.5 pl-10 text-slate-800 outline-none transition-all focus:border-accent/60 focus:ring-[3px] focus:ring-accent/15';
@endphp

<div class="text-left">

  {{-- Alert sukses --}}
  @if (session('success'))
    <div class="mb-4 flex items-start gap-2 rounded-xl border border-green-400/30 bg-green-500/20 px-4 py-3 text-sm text-green-200">
      <i class="bi bi-check-circle-fill mt-0.5 shrink-0 text-green-300"></i>
      <div class="flex-1">
        <p>{{ session('success') }}</p>
        @if(session('remaining_seconds'))
          <p class="mt-0.5 text-xs text-green-300">Tunggu <span id="cooldown">{{ session('remaining_seconds') }}</span> detik.</p>
        @endif
      </div>
      <button onclick="this.parentElement.remove()" class="text-green-300 hover:text-white">✕</button>
    </div>
  @endif

  {{-- Alert error --}}
  @if (session('error'))
    <div class="mb-4 flex items-start gap-2 rounded-xl border border-rose-400/30 bg-rose-500/20 px-4 py-3 text-sm text-rose-200">
      <i class="bi bi-exclamation-circle-fill mt-0.5 shrink-0"></i>
      <p class="flex-1">{{ session('error') }}</p>
      <button onclick="this.parentElement.remove()" class="text-rose-300 hover:text-white">✕</button>
    </div>
  @endif

  {{-- Info email & NIM --}}
  <div class="mb-4 space-y-1">
    <p class="flex items-center gap-1.5 text-xs text-white/70">
      <i class="bi bi-envelope shrink-0 text-amber-400"></i>
      Masukkan kode OTP yang dikirim ke
      <strong class="text-white">{{ session('otp_email') }}</strong>
    </p>
    <p class="flex items-center gap-1.5 text-xs text-white/50">
      <i class="bi bi-person-vcard shrink-0 text-amber-300/60"></i>
      NIM: {{ session('otp_identifier') }}
    </p>
  </div>

  {{-- Form OTP --}}
  <form method="POST" action="{{ route('password.otp.verify') }}" id="otpForm" class="space-y-4">
    @csrf

    <div>
      <label class="mb-2 flex items-center gap-1 text-xs font-medium text-white/70">
        <i class="bi bi-123 text-amber-300/80"></i> Kode OTP
      </label>

      {{-- 6 kotak OTP --}}
      <div class="flex gap-2" id="otpBoxes">
        @for ($i = 0; $i < 6; $i++)
          <input
            type="text"
            maxlength="1"
            inputmode="numeric"
            pattern="[0-9]"
            class="otp-input h-11 w-full rounded-xl border border-white/50 bg-white/90
                   text-center text-xl font-bold text-slate-800 outline-none transition-all
                   focus:border-accent/60 focus:ring-[3px] focus:ring-accent/15
                   {{ $errors->has('otp') ? 'border-rose-400' : '' }}"
            autocomplete="off"
          >
        @endfor
      </div>

      <input type="hidden" name="otp" id="hiddenCode">

      @error('code')
        <p class="mt-1.5 flex items-center gap-1 text-xs text-rose-200">
          <i class="bi bi-exclamation-circle"></i>{{ $message }}
        </p>
      @enderror
    </div>

    {{-- Timer --}}
    <p class="flex items-center gap-1.5 text-xs text-white/60" id="timerWrapper">
      <i class="bi bi-clock text-amber-400"></i>
      Kode kedaluwarsa dalam
      <span id="timerDisplay" class="font-semibold text-amber-300">05:00</span>
    </p>
    <p class="hidden flex items-center gap-1.5 text-xs text-rose-300" id="expiredMsg">
      <i class="bi bi-clock-history"></i> Kode OTP sudah kedaluwarsa.
    </p>

    {{-- Tombol --}}
    <div class="flex items-center justify-between gap-2 pt-1">
      <a href="{{ url()->previous() }}"
         class="inline-flex items-center gap-1.5 rounded-full border border-white/25 bg-white/20 px-4 py-2 text-sm text-white transition hover:bg-white/30">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
      <button type="submit" id="submitBtn"
              class="inline-flex items-center gap-2 rounded-full bg-gradient-to-br from-brand via-brand-light to-teal-600 px-5 py-2 text-sm font-medium text-white shadow-lg transition hover:brightness-105 disabled:opacity-50 disabled:cursor-not-allowed">
        <i class="bi bi-check-circle"></i> Verifikasi
      </button>
    </div>
  </form>

  {{-- Kirim ulang --}}
  <div class="mt-4 flex items-center gap-2 text-xs text-white/50">
    <i class="bi bi-arrow-repeat text-amber-400"></i>
    Tidak menerima kode?
    <button id="resendBtn"
            onclick="resendOtp()"
            class="font-medium text-white/80 underline-offset-2 hover:text-white hover:underline disabled:cursor-not-allowed disabled:opacity-40"
            disabled>
      Kirim Ulang OTP
    </button>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const boxes  = document.querySelectorAll('.otp-input');
    const hidden = document.getElementById('hiddenCode');

    boxes.forEach((box, i) => {
        box.addEventListener('input', e => {
            const val = e.target.value.replace(/\D/g, '');
            e.target.value = val;
            if (val && i < boxes.length - 1) boxes[i + 1].focus();
            updateHidden();
        });
        box.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !box.value && i > 0) {
                boxes[i - 1].focus();
                boxes[i - 1].value = '';
                updateHidden();
            }
        });
        box.addEventListener('paste', e => {
            e.preventDefault();
            const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            [...pasted].forEach((ch, idx) => { if (boxes[idx]) boxes[idx].value = ch; });
            updateHidden();
            if (pasted.length === 6) boxes[5].focus();
        });
    });

    function updateHidden() {
        hidden.value = [...boxes].map(b => b.value).join('');
    }

    let timeLeft = 300;
    const timerDisplay = document.getElementById('timerDisplay');
    const timerWrapper = document.getElementById('timerWrapper');
    const expiredMsg   = document.getElementById('expiredMsg');
    const resendBtn    = document.getElementById('resendBtn');
    const submitBtn    = document.getElementById('submitBtn');

    const timer = setInterval(() => {
        timeLeft--;
        const m = String(Math.floor(timeLeft / 60)).padStart(2, '0');
        const s = String(timeLeft % 60).padStart(2, '0');
        timerDisplay.textContent = `${m}:${s}`;
        if (timeLeft <= 0) {
            clearInterval(timer);
            timerWrapper.classList.add('hidden');
            expiredMsg.classList.remove('hidden');
            submitBtn.disabled = true;
            resendBtn.disabled = false;
        }
    }, 1000);

    @if(session('otp_expired'))
        clearInterval(timer);
        timerWrapper.classList.add('hidden');
        expiredMsg.classList.remove('hidden');
        submitBtn.disabled = true;
        resendBtn.disabled = false;
    @endif
});

async function resendOtp() {
    const btn = document.getElementById('resendBtn');
    btn.disabled = true;
    btn.textContent = 'Mengirim...';
    try {
        const res  = await fetch('{{ route("otp.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });
        const data = await res.json();
        alert(data.message);
        if (data.success) location.reload();
        else btn.disabled = false;
    } catch {
        alert('Gagal menghubungi server. Coba lagi.');
        btn.disabled = false;
    } finally {
        btn.textContent = 'Kirim Ulang OTP';
    }
}
</script>
@endsection