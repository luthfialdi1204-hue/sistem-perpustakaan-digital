<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perpustakaan Digital</title>

<!-- Tailwind + Flowbite -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />

</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<nav class="fixed w-full z-50 bg-blue-800">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-16">
    <img src="{{ asset('images/Logo Polibatam.png') }}" class="w-12">
    
    <div class="space-x-6 text-white hidden md:flex">
      <a href="#beranda">Beranda</a>
      <a href="#tentang">Tentang</a>
      <a href="#buku">Koleksi Buku</a>
      <a href="Halaman_Masuk">Masuk</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<section id="beranda" class="bg-cyan-200 pt-24 pb-16">
  <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 items-center">
    
    <div>
      <h2 class="text-3xl font-bold text-blue-600 mb-2">
        Perpustakaan Digital PERRRPUS
      </h2>
      <p>Akses literasi tanpa batas.</p>
    </div>

    <div class="text-center">
      <img src="{{ asset('images/landingpage2.png') }}" class="mx-auto w-72">
    </div>

  </div>
</section>

<!-- ABOUT -->
<section id="tentang" class="bg-white py-16 text-center">
  <div class="max-w-6xl mx-auto px-4">
    <h4 class="text-xl font-bold mb-6">Tentang</h4>

    <div class="grid md:grid-cols-2 items-center gap-6">
      
      <div>
        <img src="{{ asset('images/landingpage1.png') }}" class="mx-auto w-64">
      </div>

      <div class="space-y-3 text-left">
        <div class="p-4 bg-gray-100 rounded">
          Platform perpustakaan digital modern.
        </div>
        <div class="p-4 bg-gray-100 rounded">
          Mudah digunakan semua kalangan.
        </div>
      </div>

    </div>
  </div>
</section>

<!-- KOLEKSI -->
<section id="buku" class="bg-cyan-200 py-16 text-center">
  <div class="max-w-7xl mx-auto px-4">
    <h4 class="text-xl font-bold mb-6">Koleksi Buku</h4>

    <div id="book-container" class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <script>
        document.write(`
          ${Array(16).fill(`
            <div class="bg-white rounded-lg shadow p-3">
              <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="w-full h-40 object-cover rounded">
              <h6 class="mt-2 font-semibold">Atomic Habits</h6>
              <button 
                data-modal-target="detailModal"
                data-modal-toggle="detailModal"
                class="mt-2 w-full bg-blue-600 text-white py-1 rounded">
                Detail
              </button>
            </div>
          `).join('')}
        `);
      </script>
    </div>

    <!-- PAGINATION -->
    <div class="flex justify-center mt-6 space-x-2">
      <button onclick="loadPage(1)" id="page-1" class="px-3 py-1 bg-blue-600 text-white rounded">1</button>
      <button onclick="loadPage(2)" id="page-2" class="px-3 py-1 bg-gray-300 rounded">2</button>
    </div>

  </div>
</section>

<!-- MODAL -->
<div id="detailModal" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
  <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-5 relative">
    
    <button data-modal-hide="detailModal" class="absolute top-2 right-2">✖</button>

    <h5 class="text-lg font-bold mb-4">Detail Buku</h5>

    <div class="grid md:grid-cols-2 gap-4">
      <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="w-full rounded">

      <div>
        <h5 class="font-bold">Atomic Habits</h5>
        <p><b>Pengarang:</b> James Clear</p>
        <p><b>Penerbit:</b> Avery</p>
        <p><b>Tahun:</b> 2018</p>
        <p><b>Deskripsi:</b> Buku tentang kebiasaan kecil yang berdampak besar.</p>
      </div>
    </div>

  </div>
</div>

<!-- FOOTER -->
<footer class="bg-blue-800 text-white pt-10 pb-4">
  <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-4 gap-6 text-left">

    <div>
      <h5 class="font-bold">PERRRPUS</h5>
      <p>Platform perpustakaan digital modern</p>
    </div>

    <div>
      <h6 class="font-semibold">Menu</h6>
      <a href="#beranda" class="block">Beranda</a>
      <a href="#tentang" class="block">Tentang</a>
      <a href="#buku" class="block">Buku</a>
    </div>

    <div>
      <h6 class="font-semibold">Kontak</h6>
      <p>Batam, Indonesia</p>
    </div>

    <div>
      <h6 class="font-semibold">Lokasi</h6>
      <iframe src="https://www.google.com/maps?q=Politeknik+Negeri+Batam&output=embed" class="w-full h-32 rounded"></iframe>
    </div>

  </div>

  <div class="text-center mt-6">
    © <span id="year"></span> PERRRPUS
  </div>
</footer>

<script>
document.getElementById("year").textContent = new Date().getFullYear();

function loadPage(page){
  const container = document.getElementById("book-container");

  if(page === 2){
    container.innerHTML = `
      ${Array(16).fill(`
        <div class="bg-white rounded-lg shadow p-3">
          <img src="https://m.media-amazon.com/images/I/71UwSHSZRnS.jpg" class="w-full h-40 object-cover rounded">
          <h6 class="mt-2 font-semibold">Rich Dad Poor Dad</h6>
          <button 
            data-modal-target="detailModal"
            data-modal-toggle="detailModal"
            class="mt-2 w-full bg-blue-600 text-white py-1 rounded">
            Detail
          </button>
        </div>
      `).join('')}
    `;
  } else {
    location.reload();
  }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>

</body>
</html>