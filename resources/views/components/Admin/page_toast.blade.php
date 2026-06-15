<!-- PAGE TOAST (reusable) -->
<div id="pageToast" class="pointer-events-none fixed bottom-6 right-6 z-[70] translate-y-4 opacity-0 transition-all duration-300">
  <div id="pageToastBox" class="flex items-center gap-2 rounded-xl border px-4 py-3 text-sm font-medium shadow-lg">
    <i id="pageToastIcon" class="bi bi-check-circle-fill text-lg"></i>
    <span id="pageToastText">OK</span>
  </div>
</div>

@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      if (window.AdminUi && typeof window.AdminUi.toast === 'function') {
        window.AdminUi.toast("{{ session('success') }}", "success");
      }
    }, 100);
  });
</script>
@endif

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      if (window.AdminUi && typeof window.AdminUi.toast === 'function') {
        window.AdminUi.toast("{{ session('error') }}", "error");
      }
    }, 100);
  });
</script>
@endif

