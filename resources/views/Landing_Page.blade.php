<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perpustakaan Digital</title>

<!-- Tailwind + Flowbite -->
<script src="https://cdn.tailwindcss.com"></script>
@include('partials.head-theme')
@include('partials.flowbite-assets')

<style>
  html { scroll-behavior: smooth; }
  section { scroll-margin-top: 80px; }
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(14px) scale(.97); }
    to   { opacity: 1; transform: translateY(0)   scale(1);    }
  }
</style>
</head>

<body class="bg-slate-100 font-sans antialiased">

<!-- NAVBAR -->
<nav id="navbar" class="fixed top-0 left-0 w-full z-50 bg-gradient-to-r from-[#152a52] to-[#1E376E] shadow-lg">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
    <a href="#beranda" class="flex items-center gap-2">
      <img src="{{ asset('images/poltek.png') }}" class="w-10 object-contain" alt="Logo">
      <span class="font-bold text-white hidden sm:inline"><i class="bi bi-book-half text-amber-400"></i> PERRRPUS</span>
    </a>
    <div class="gap-1 text-white hidden md:flex items-center">
      <a href="#beranda" class="flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-white/10 hover:text-amber-300 transition"><i class="bi bi-house"></i> Beranda</a>
      <a href="#tentang" class="flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-white/10 hover:text-amber-300 transition"><i class="bi bi-info-circle"></i> Tentang</a>
      <a href="#buku" class="flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-white/10 hover:text-amber-300 transition"><i class="bi bi-journal-bookmark"></i> Koleksi</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section id="beranda"
  class="pt-24 min-h-screen flex items-center relative overflow-hidden">

  <!-- BACKGROUND IMAGE -->
  <img src="{{ asset('images/background.jpg') }}"
       class="absolute inset-0 w-full h-full object-cover"
       alt="Background">

  <!-- OVERLAY (BIAR WARNA LEBIH BAGUS) -->
  <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 via-blue-800/70 to-blue-700/50"></div>

  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 items-center gap-8 relative z-10">

    <div>
      <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">
        Perpustakaan Digital PERRRPUS
      </h2>

      <p class="text-white leading-relaxed text-base md:text-lg">
        Akses Literasi Tanpa Batas, Hanya dalam Genggamanmu.<br>
        Membawa pengetahuan ke era digital.
      </p>

      <div class="mt-6 flex gap-4">
        <a href="#buku" class="inline-flex items-center gap-2 bg-amber-400 text-[#1E376E] px-6 py-3 rounded-xl font-semibold hover:bg-amber-300 transition shadow-lg">
          <i class="bi bi-search"></i> Jelajahi Buku
        </a>
        <a href="{{ route('login.form') }}" class="inline-flex items-center gap-2 border-2 border-white text-white px-6 py-3 rounded-xl hover:bg-white hover:text-[#1E376E] transition">
          <i class="bi bi-box-arrow-in-right"></i> Masuk
        </a>
      </div>

      <div class="mt-8 flex gap-8 text-white">
        <div>
          <h3 class="text-2xl font-bold">{{ $totalBooks }}+</h3>
          <p class="text-sm">Koleksi Buku</p>
        </div>
        <div>
          <h3 class="text-2xl font-bold">{{ $totalUsers }}+</h3>
          <p class="text-sm">Pengguna</p>
        </div>
        <div>
          <h3 class="text-2xl font-bold">24/7</h3>
          <p class="text-sm">Akses</p>
        </div>
      </div>
    </div>

    <div class="flex justify-center md:justify-end">
      <img src="{{ asset('images/landingpage2.png') }}"
           class="w-full max-w-md md:max-w-lg drop-shadow-xl"
           alt="Hero Image">
    </div>
  </div>

  <!-- DECOR -->
  <div class="absolute bottom-0 left-0 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl"></div>
</section>

<!-- ABOUT -->
<section id="tentang" class="bg-gradient-to-b from-white to-blue-50 py-20 text-center relative overflow-hidden">

  <div class="absolute top-0 left-0 w-72 h-72 bg-blue-300/20 rounded-full blur-3xl"></div>
  <div class="absolute bottom-0 right-0 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl"></div>

  <div class="max-w-6xl mx-auto px-6 relative z-10">

    <h2 class="text-2xl md:text-3xl font-bold text-[#1E376E]">
      Apa itu Perpustakaan Digital Platform PERRRPUS?
    </h2>

    <p class="text-[#1E376E] text-sm md:text-base mt-2 mb-12 max-w-2xl mx-auto">
      Tak kenal maka tak sayang, kenalan dulu yuk platform kami
    </p>

    <div class="grid md:grid-cols-2 items-center gap-10">

      <div class="flex justify-center">
        <img src="{{ asset('images/landingpage1.png') }}" 
             class="w-full max-w-md md:max-w-lg drop-shadow-2xl hover:scale-105 transition duration-500" 
             alt="About Image">
      </div>

      <div class="space-y-6 text-left">

        <div class="p-6 bg-white/70 backdrop-blur-md rounded-xl shadow-lg hover:shadow-xl transition">
          PERRRPUS adalah platform perpustakaan digital yang menjadi pusat berbagai sumber pengetahuan dalam satu tempat. 
          Platform ini menghadirkan pengalaman literasi modern yang praktis dan mudah diakses.
        </div>

        <div class="p-6 bg-white/70 backdrop-blur-md rounded-xl shadow-lg hover:shadow-xl transition">
          Dengan koleksi yang beragam, mulai dari e-book, jurnal ilmiah, hingga berbagai referensi lainnya, 
          PERRRPUS memudahkan pengguna untuk belajar dan mencari informasi kapan saja dan di mana saja.
        </div>

        <div class="p-6 bg-white/70 backdrop-blur-md rounded-xl shadow-lg hover:shadow-xl transition">
          Platform ini memberikan kenyamanan tanpa batasan geografis tanpa perlu instal aplikasi tambahan.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- KOLEKSI -->
<section id="buku" class="relative overflow-hidden bg-gradient-to-b from-cyan-200 to-blue-100 py-16 text-center">

  <!-- BACKGROUND ANIMATION -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute w-72 h-72 bg-blue-400/20 rounded-full blur-3xl animate-float1"></div>
    <div class="absolute w-72 h-72 bg-cyan-400/20 rounded-full blur-3xl animate-float2"></div>
    <div class="absolute w-72 h-72 bg-indigo-400/20 rounded-full blur-3xl animate-float3"></div>
  </div>

  <div class="max-w-7xl mx-auto px-6 relative z-10">

    <h2 class="text-2xl md:text-3xl font-bold text-[#1E376E] mb-2">
      Koleksi Buku
    </h2>
    <p class="text-[#1E376E] mb-10">
      Temukan buku favoritmu di sini
    </p>

    <div id="book-container" class="grid grid-cols-2 md:grid-cols-4 gap-6 gap-y-8"></div>

    <!-- PAGINATION -->
    <div id="pagination-wrapper" class="flex justify-center mt-10 gap-2 items-center"></div>
  </div>
</section>
 
<!-- MODAL -->
<div id="detailModal" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDetailModal()"></div>
  <!-- Panel -->
  <div class="relative w-full max-w-2xl rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden" style="animation: slideUp .22s ease">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
      <h3 class="text-lg font-bold text-slate-800">Detail Buku</h3>
      <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-700 text-xl leading-none">&times;</button>
    </div>

    <!-- Content -->
    <div class="px-6 py-5">
      <div class="grid md:grid-cols-12 gap-6 items-start">
        <!-- Image Cover -->
        <div class="md:col-span-5 flex justify-center">
          <img id="modal-img" class="w-full max-w-[200px] md:max-w-none rounded-xl border border-slate-200 object-cover shadow-md">
        </div>

        <!-- Details -->
        <div class="md:col-span-7 space-y-2.5 text-sm text-slate-700">
          <h4 id="modal-title" class="text-lg font-bold text-slate-800 leading-tight mb-2"></h4>
          <p><span class="font-semibold text-slate-500">Nomor Panggil:</span> <span id="modal-code" class="text-slate-800"></span></p>
          <p><span class="font-semibold text-slate-500">Pengarang:</span> <span id="modal-author" class="text-slate-800"></span></p>
          <p><span class="font-semibold text-slate-500">Penerbit:</span> <span id="modal-publisher" class="text-slate-800"></span></p>
          <p><span class="font-semibold text-slate-500">Tahun Terbit:</span> <span id="modal-year" class="text-slate-800"></span></p>
          <p class="flex items-center gap-1.5">
            <span class="font-semibold text-slate-500">Kategori:</span>
            <span id="modal-category" class="inline-block rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700"></span>
          </p>
          <p class="flex items-center gap-1.5">
            <span class="font-semibold text-slate-500">Ketersediaan:</span>
            <span id="modal-stock" class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold text-white"></span>
          </p>
          <p><span class="font-semibold text-slate-500">Lokasi Rak:</span> <span id="modal-rack" class="text-slate-800"></span></p>
          <p><span class="font-semibold text-slate-500">ISBN:</span> <span id="modal-isbn" class="text-slate-800"></span></p>
          <div class="pt-1">
            <p class="font-semibold text-slate-800 mb-1">Deskripsi</p>
            <p id="modal-desc" class="text-xs text-slate-500 leading-relaxed"></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-slate-100 flex justify-end">
      <button onclick="closeDetailModal()" class="px-6 py-2 rounded-lg bg-[#1E376E] text-white hover:bg-[#162d5c] text-sm font-semibold transition shadow-sm active:scale-95">
        Kembali
      </button>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="bg-gradient-to-b from-blue-900 to-blue-800 text-white pt-12 pb-6">

  <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-10">
<div>

  <!-- LOGO + TEXT -->
  <div class="flex items-center gap-3 mb-2">
    <img src="{{ asset('images/poltek.png') }}" 
         class="w-10 h-10 object-contain 
         alt="Logo Polibatam">

    <h5 class="font-bold text-lg text-white">
      PERRRPUS
    </h5>
  </div>

       <p class="text-white/90">
        Platform perpustakaan digital modern
      </p>
      </div>

    <!-- MENU -->
    <div>
      <h6 class="font-semibold mb-3 text-white">Menu</h6>
      <div class="space-y-2">
        <a href="#beranda" class="block text-white/90 hover:text-yellow-300 transition">Beranda</a>
        <a href="#tentang" class="block text-white/90 hover:text-yellow-300 transition">Tentang</a>
        <a href="#buku" class="block text-white/90 hover:text-yellow-300 transition">Buku</a>
      </div>
    </div>

    <!-- KONTAK -->
    <div>
      <h6 class="font-semibold mb-3 text-white">Kontak</h6>
      <p class="text-white/90 flex items-center gap-2"><i class="bi bi-geo-alt text-amber-400"></i> Batam, Indonesia</p>
      <p class="text-white/90 mt-2 flex items-center gap-2"><i class="bi bi-envelope text-amber-400"></i> info@perrrpus.com</p>
      <p class="text-white/90 mt-2 flex items-center gap-2"><i class="bi bi-telephone text-amber-400"></i> +62 812-3456-7890</p>
    </div>

    <!-- LOKASI -->
    <div>
      <h6 class="font-semibold mb-3 text-white">Lokasi</h6>
      <iframe 
        src="https://www.google.com/maps?q=Politeknik+Negeri+Batam&output=embed" 
        class="w-full h-32 rounded-lg shadow-md border-0">
      </iframe>
    </div>

  </div>

  <!-- COPYRIGHT -->
  <div class="border-t border-blue-700 mt-10 pt-4 text-center text-sm text-white/80">
    © <span id="year"></span> PERRRPUS
  </div>

</footer>

<!-- SCRIPT -->
<script>
const allBooks = @json($books ?? []);
const perPage = 8;
let currentPage = 1;

function escHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

function createCard(book){
  const img = book.img;
  const title = escHtml(book.title);
  const author = escHtml(book.author);
  const publisher = escHtml(book.publisher);
  const year = escHtml(book.year);
  const desc = escHtml(book.description);
  const code = escHtml(book.code);
  const category = escHtml(book.category);
  const rack = escHtml(book.rack);
  const isbn = escHtml(book.isbn);
  const stock = book.stock;

  return `
    <div class="bg-white rounded-2xl border border-slate-200 p-4 flex flex-col items-center justify-between text-center hover:shadow-xl hover:-translate-y-1 transition duration-300">
      <div class="overflow-hidden rounded-xl w-full">
        <img src="${img}" class="w-full h-44 object-cover hover:scale-105 transition duration-300" alt="${title}">
      </div>
      <div class="p-1 w-full text-center flex flex-col items-center gap-1 mt-2.5">
        <h6 class="font-bold text-[#1E376E] text-sm truncate w-full" title="${title}">${title}</h6>
        <p class="text-xs text-slate-500">${author}</p>
        <p class="text-xs text-slate-500">${category}</p>
        <p class="text-xs text-slate-500">${stock > 0 ? stock + ' tersedia' : 'Dipinjam semua'}</p>
      </div>
      <button 
        onclick="showDetail(this)"
        data-title="${title}"
        data-author="${author}"
        data-publisher="${publisher}"
        data-year="${year}"
        data-desc="${desc}"
        data-img="${img}"
        data-code="${code}"
        data-category="${category}"
        data-rack="${rack}"
        data-isbn="${isbn}"
        data-stock="${stock}"
        class="mt-3.5 w-full rounded-xl bg-[#1E376E] py-2.5 text-xs font-semibold text-white transition hover:bg-[#162d5c] shadow-sm">
        Lihat Detail
      </button>
    </div>
  `;
}

function renderPagination(totalBooks, perPage, page) {
  const wrapper = document.getElementById("pagination-wrapper");
  if (!wrapper) return;

  const totalPages = Math.ceil(totalBooks / perPage);
  if (totalPages <= 1) {
    wrapper.innerHTML = "";
    return;
  }

  let html = "";
  
  // Previous button (only if not on first page)
  if (page > 1) {
    html += `
      <button onclick="loadPage(${page - 1})"
        class="flex items-center justify-center h-10 px-4 rounded-xl border border-slate-200 bg-white text-[#1E376E] font-semibold transition hover:bg-slate-50 shadow-sm text-xs gap-1">
        ← Prev
      </button>
    `;
  }

  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    if (i === page) {
      html += `<span class="flex items-center justify-center h-10 w-10 rounded-xl bg-[#1E376E] text-white font-semibold shadow-sm text-xs">${i}</span>`;
    } else {
      html += `<button onclick="loadPage(${i})" class="flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200 bg-white text-[#1E376E] font-semibold transition hover:bg-slate-50 shadow-sm text-xs">${i}</button>`;
    }
  }

  // Next button
  if (page < totalPages) {
    html += `
      <button onclick="loadPage(${page + 1})"
        class="flex items-center justify-center h-10 px-4 rounded-xl bg-[#1E376E] text-white font-semibold shadow-sm transition hover:bg-[#162d5c] text-xs gap-1">
        Next →
      </button>
    `;
  }

  wrapper.innerHTML = html;
}

function loadPage(page){
  currentPage = page;
  const container = document.getElementById("book-container");
  if (!container) return;

  if (allBooks.length === 0) {
    container.innerHTML = `
      <div class="col-span-full py-16 text-center text-slate-500">
        <i class="bi bi-journal-x mb-2 block text-4xl text-slate-300"></i>
        Belum ada koleksi buku di perpustakaan.
      </div>
    `;
    renderPagination(0, perPage, 1);
    return;
  }

  const startIndex = (page - 1) * perPage;
  const pageBooks = allBooks.slice(startIndex, startIndex + perPage);

  container.innerHTML = pageBooks.map(b => createCard(b)).join("");
  
  if (typeof initFlowbite === "function") {
    initFlowbite();
  }

  renderPagination(allBooks.length, perPage, page);
}

/* 🔥 INI BAGIAN PENTING (DETAIL MODAL) */
function showDetail(btn){
  document.getElementById("modal-title").innerText = btn.dataset.title;
  document.getElementById("modal-author").innerText = btn.dataset.author;
  document.getElementById("modal-publisher").innerText = btn.dataset.publisher;
  document.getElementById("modal-year").innerText = btn.dataset.year;
  document.getElementById("modal-desc").innerText = btn.dataset.desc;
  document.getElementById("modal-img").src = btn.dataset.img;
  
  document.getElementById("modal-code").innerText = btn.dataset.code || '-';
  document.getElementById("modal-category").innerText = btn.dataset.category || '-';
  document.getElementById("modal-rack").innerText = btn.dataset.rack || '-';
  document.getElementById("modal-isbn").innerText = btn.dataset.isbn || '-';
  
  const stock = Number(btn.dataset.stock) || 0;
  const stockEl = document.getElementById("modal-stock");
  stockEl.innerText = stock > 0 ? `${stock} tersedia` : 'Dipinjam semua';
  stockEl.className = `inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold text-white ` + (stock > 0 ? 'bg-emerald-500' : 'bg-amber-500');

  document.getElementById("detailModal").classList.remove("hidden");
}

function closeDetailModal() {
  document.getElementById("detailModal").classList.add("hidden");
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') closeDetailModal();
});

document.getElementById("year").textContent = new Date().getFullYear();
loadPage(1);
</script>


</body>
</html>