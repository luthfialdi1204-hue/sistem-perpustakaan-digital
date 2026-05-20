<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Modal === 'undefined') return;
  window.fbModal = function (id) {
    const el = document.getElementById(id);
    if (!el) return null;
    if (!el._fbModal) {
      el._fbModal = new Modal(el, {
        backdrop: 'dynamic',
        backdropClasses: 'bg-gray-900/50 fixed inset-0 z-40',
        onShow: function () { document.body.classList.add('overflow-hidden'); },
        onHide: function () { document.body.classList.remove('overflow-hidden'); },
      });
    }
    return el._fbModal;
  };
  window.fbShow = function (id) { window.fbModal(id)?.show(); };
  window.fbHide = function (id) { window.fbModal(id)?.hide(); };
});
</script>
