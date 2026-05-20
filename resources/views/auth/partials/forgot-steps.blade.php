@php
  $currentStep = $currentStep ?? 1;
@endphp
<div class="mb-5">
  <div class="flex items-center justify-center gap-2">
    <span class="h-2.5 w-2.5 rounded-full {{ $currentStep > 1 ? 'bg-emerald-400' : ($currentStep === 1 ? 'bg-accent shadow-[0_0_0_3px_rgba(245,197,24,0.3)]' : 'bg-white/30') }}"></span>
    <span class="h-0.5 w-6 {{ $currentStep > 1 ? 'bg-emerald-400/60' : 'bg-white/20' }}"></span>
    <span class="h-2.5 w-2.5 rounded-full {{ $currentStep > 2 ? 'bg-emerald-400' : ($currentStep === 2 ? 'bg-accent shadow-[0_0_0_3px_rgba(245,197,24,0.3)]' : 'bg-white/30') }}"></span>
    <span class="h-0.5 w-6 {{ $currentStep > 2 ? 'bg-emerald-400/60' : 'bg-white/20' }}"></span>
    <span class="h-2.5 w-2.5 rounded-full {{ $currentStep === 3 ? 'bg-accent shadow-[0_0_0_3px_rgba(245,197,24,0.3)]' : ($currentStep > 3 ? 'bg-emerald-400' : 'bg-white/30') }}"></span>
  </div>
  <p class="mt-2 text-center text-xs text-white/60">Langkah {{ $currentStep }} dari 3</p>
</div>
