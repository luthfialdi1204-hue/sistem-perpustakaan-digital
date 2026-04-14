<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan Digital</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f5f5;
    }

    .hero {
      background-color: #9CEDFF;
      padding: 60px 0;
    }

    .about {
      background-color: #ffffff;
      padding: 50px 0;
    }

    .books {
      background-color: #9CEDFF ;
      padding: 50px 0;
    }

    .card img {
      height: 200px;
      object-fit: cover;
    }

    footer {
      background: black;
      color: white;
      padding: 15px;
      text-align: center;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#2f4f8f;">
  <div class="container">
    <img src="{{ asset('storage/Logo Polibatam.png') }}" width="50">

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Koleksi Buku</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Masuk</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="container">
    <div class="row align-items-center">
      
      <div class="col-md-6">
        <h2 class="fw-bold text-primary">
          Perpustakaan Digital <br> Platform PERRRPUS
        </h2>
        <p>
          Akses literasi tanpa batas, hanya dalam genggamanmu.<br>
          Membawa pengetahuan ke era digital.
        </p>
      </div>

      <div class="col-md-6 text-center">
        <img src="{{ asset('storage/img1.png') }}" width="300">

      </div>

    </div>
  </div>
</section>

<!-- ABOUT -->
<section class="about text-center">
  <div class="container">
    <h4 class="fw-bold">Apa Itu Perpustakaan Digital Platform PERRRPUS?</h4>
    <p class="mb-4">Tak kenal maka tak sayang, kenalan dulu yuk platform kami!</p>

    <div class="row justify-content-center align-items-center">
      
      <div class="col-md-4 text-center">
        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" width="150">
      </div>

      <div class="col-md-6">
        <div class="p-3 rounded" style="background:#d9edf3;">
          <p>
            Platform ini merupakan sistem digital yang menyediakan berbagai layanan literasi seperti e-book,
            jurnal, dan sumber pengetahuan lainnya.
          </p>
          <p>
            Dengan platform ini, pengguna dapat membaca dan mengakses informasi kapan saja dan di mana saja
            dengan mudah.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- KOLEKSI BUKU -->
<section class="books text-center">
  <div class="container">
    <h4 class="fw-bold mb-4">Koleksi Buku Digital</h4>

    <div class="row g-4">

      <!-- CARD -->
      <div class="col-6 col-md-3">
        <div class="card shadow-sm">
          <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="card-img-top">
          <div class="card-body">
            <h6>Atomic Habits</h6>
            <button class="btn btn-primary btn-sm w-100">Detail</button>
          </div>
        </div>
      </div>

      <!-- DUPLIKASI CARD -->
      <div class="col-6 col-md-3">
        <div class="card shadow-sm">
          <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="card-img-top">
          <div class="card-body">
            <h6>Atomic Habits</h6>
            <button class="btn btn-primary btn-sm w-100">Detail</button>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm">
          <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="card-img-top">
          <div class="card-body">
            <h6>Atomic Habits</h6>
            <button class="btn btn-primary btn-sm w-100">Detail</button>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm">
          <img src="https://images-na.ssl-images-amazon.com/images/I/91bYsX41DVL.jpg" class="card-img-top">
          <div class="card-body">
            <h6>Atomic Habits</h6>
            <button class="btn btn-primary btn-sm w-100">Detail</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <p>Copyright © 2025 Outrent. All Rights Reserved</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>