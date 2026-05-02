<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perpustakaan Digital</title>

<!-- Tailwind + Flowbite -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />

<style>
  html { scroll-behavior: smooth; }
  section { scroll-margin-top: 80px; }
</style>
</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<nav id="navbar" class="fixed top-0 left-0 w-full z-50 bg-blue-800 transition-all duration-300">
  <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
    <img src="{{ asset('images/poltek.png') }}" class="w-12 object-contain" alt="Logo">
    
    <div class="space-x-6 text-white hidden md:flex">
      <a href="#beranda" class="hover:text-yellow-300 transition">Beranda</a>
      <a href="#tentang" class="hover:text-yellow-300 transition">Tentang</a>
      <a href="#buku" class="hover:text-yellow-300 transition">Koleksi Buku</a>
      <a href="Halaman_Masuk" class="hover:text-yellow-300 transition">Masuk</a>
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
        <a href="#buku" class="bg-yellow-400 text-[#1E376E] px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition">
          Jelajahi Buku
        </a>

        <a href="Halaman_Masuk" class="border border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-[#1E376E] transition">
          Masuk
        </a>
      </div>

      <div class="mt-8 flex gap-8 text-white">
        <div>
          <h3 class="text-2xl font-bold">20+</h3>
          <p class="text-sm">Koleksi Buku</p>
        </div>
        <div>
          <h3 class="text-2xl font-bold">2+</h3>
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
    <div class="flex justify-center mt-10 gap-2 items-center">
      <button onclick="loadPage(1)" id="page-1" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow">1</button>
      <button onclick="loadPage(2)" id="page-2" class="px-4 py-2 bg-white text-blue-600 rounded-lg shadow">2</button>
      <button onclick="loadPage(3)" id="page-3" class="px-4 py-2 bg-white text-blue-600 rounded-lg shadow">3</button>
      <span class="px-2 text-gray-500">...</span>
      <button onclick="loadPage(currentPage + 1)" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
        Next →
      </button>
    </div>
  </div>
</section>
 
<!-- MODAL -->
<div id="detailModal" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
  <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-5 relative">

    <button data-modal-hide="detailModal" class="absolute top-2 right-2">✖</button>

    <h5 class="text-lg font-bold mb-4">Detail Buku</h5>

    <div class="grid md:grid-cols-2 gap-4">
      <img id="modal-img" class="w-full rounded">

      <div>
        <h5 id="modal-title" class="font-bold"></h5>
        <p><b>Pengarang:</b> <span id="modal-author"></span></p>
        <p><b>Penerbit:</b> <span id="modal-publisher"></span></p>
        <p><b>Tahun:</b> <span id="modal-year"></span></p>
        <p><b>Deskripsi:</b> <span id="modal-desc"></span></p>
      </div>
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
      <p class="text-white/90">📍 Batam, Indonesia</p>
      <p class="text-white/90 mt-2">✉️ info@perrrpus.com</p>
      <p class="text-white/90 mt-2">📞 +62 812-3456-7890</p>
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
let currentPage = 1;

function createCard(img, title){
  return `
    <div class="bg-white rounded-xl shadow-md p-4 flex flex-col hover:shadow-xl hover:-translate-y-1 transition duration-300">
      
      <div class="overflow-hidden rounded-lg">
        <img src="${img}" class="w-full h-56 object-cover hover:scale-105 transition duration-300">
      </div>

      <h6 class="mt-3 font-semibold text-[#1E376E] text-sm">${title}</h6>

      <button 
        onclick="showDetail(this)"
        data-title="${title}"
        data-author="James Clear"
        data-publisher="Avery Publishing"
        data-year="2018"
        data-desc="Buku tentang kebiasaan kecil yang berdampak besar."
        data-img="${img}"
        data-modal-target="detailModal"
        data-modal-toggle="detailModal"
        class="mt-3 w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white py-2 rounded-lg text-sm hover:opacity-90 transition">
        Detail
      </button>

    </div>
  `;
}

function loadPage(page){
  currentPage = page;
  const container = document.getElementById("book-container");

  let books = [];

  if(page === 1){
    books = Array(12).fill({
      img: "{{ asset('images/Cover buku 3.jpg') }}",
      title: "Atomic Habits"
    });
  } else if(page === 2){
    books = Array(12).fill({
      img: "{{ asset('images/Cover buku 2.jpg') }}",
      title: "Rich Dad Poor Dad"
    });
  } else {
    books = Array(12).fill({
      img: "{{ asset('images/Cover buku 1.jpg') }}",
      title: "The Psychology of Money"
    });
  }

  container.innerHTML = books.map(b => createCard(b.img, b.title)).join("");
    if (typeof initFlowbite === "function") {
      initFlowbite();
  }

  [1,2,3].forEach(i => {
    const btn = document.getElementById("page-" + i);
    if(btn){
      btn.classList.remove("bg-blue-600","text-white");
      btn.classList.add("bg-white","text-blue-600");
    }
  });

    const active = document.getElementById("page-" + page);
    if(active){
      active.classList.remove("bg-white","text-blue-600");
    active.classList.add("bg-blue-600","text-white");
  }
}

/* 🔥 INI BAGIAN PENTING (DETAIL MODAL) */
function showDetail(btn){
  document.getElementById("modal-title").innerText = btn.dataset.title;
  document.getElementById("modal-author").innerText = btn.dataset.author;
  document.getElementById("modal-publisher").innerText = btn.dataset.publisher;
  document.getElementById("modal-year").innerText = btn.dataset.year;
  document.getElementById("modal-desc").innerText = btn.dataset.desc;
  document.getElementById("modal-img").src = btn.dataset.img;
}

document.getElementById("year").textContent = new Date().getFullYear();
loadPage(1);
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>