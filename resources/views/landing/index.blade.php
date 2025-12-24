<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sistem Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <style>
        body {
            background: linear-gradient(135deg, #cce0ff, #a8b7ff);
            min-height: 100vh;
            padding-top: 40px;
        }

        .header-icon img {
            width: 85px;
        }

        .search-bar input {
            border-radius: 10px;
            padding-left: 20px;
            height: 48px;
        }

        .swiper {
            width: 100%;
            padding: 10px 0 40px;
        }

        .swiper-slide {
            width: 320px !important;   /* biar semua ukuran konsisten */
            display: flex;
            justify-content: center;
        }

        .login-btn {
            margin-top: 25px;
            text-align: center;
        }

        .swiper-button-next, 
        .swiper-button-prev {
            color: #003399;
            font-weight: bold;
        }

        .book-card {
            width: 300px;
            height: 580px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            background: white;
            padding: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: 0.2s;

            display: flex;                /* aktifkan flexbox */
            flex-direction: column;       /* susun vertikal */
            justify-content: space-between; /* tombol selalu di bawah */
        }

        .book-card:hover {
            transform: translateY(-5px);
        }

        /* Kotak Foto */
        .book-cover {
            width: 100%;
            height: 330px;
            background: #f28c28; /* oranye */
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Informasi */
        .book-info {
            margin-top: 10px;
        }

        .book-title {
            font-weight: 700;
            font-size: 18px;
            line-height: 1.3;          /* penting! */
            height: calc(18px * 1.3 * 2);   /* tinggi = 2 baris */
            
            margin-bottom: 5px;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-meta {
            font-size: 16px;
            color: #444;
            line-height: 1.3;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .badge-stock {
            display: inline-block;
            background: #d4fdd4;
            color: #1b8f1b;
            font-weight: 600;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            margin-bottom: 14px;
        }

        /* Tombol */
        .btn-detail {
            width: 100%;
            background: #007bff;
            border: none;
            padding: 8px;
            color: white;
            font-size: 14px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-detail:hover {
            background: #0069d9;
        }

        .cover-wrapper {
            width: 100%;    
            height: 250px;        /* Tinggi cover SAMA semua */
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .cover-wrapper img {
            width: 150px;
            max-height: 100%;
            object-fit: contain;
        }

        .no-cover {
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            line-height: 1.3;
        }

        .meta-row {
            margin-bottom: 1px;
        }

        .swiper-wrapper.center-mode {
            justify-content: center !important;
        }

        .swiper-wrapper.center-multiple {
            justify-content: center !important;
        }

        .book-info-wrapper {
            flex-grow: 1;   /* area atas akan mengisi ruang kosong */
        }
    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="text-center mb-4">
        <div class="header-icon">
            <img src="{{ asset('logo.png') }}">
        </div>
        <h2 class="font-weight-bold mt-2">Sistem Perpustakaan</h2>
        <span>Sistem Perpustakaan Versi 1.0</span>
    </div>
    
    <div class="card" style="border-radius: 15px;">
        <div class="mt-4 ml-3 mr-3">
            <div class="search-title d-flex align-items-center mb-3">
                <h4 class="mb-0 font-weight-bold">
                    <i class="bi bi-search ml-2 mr-2"></i> Cari Buku
                </h4>
            </div>
            <div class="search-bar mb-3">
                <input type="text" id="searchInput" class="form-control search-input" 
                    placeholder="Cari berdasarkan judul, pengarang, atau ISBN...">
            </div>
        </div>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($buku as $b)
                <div class="swiper-slide book-item"
                    data-title="{{ strtolower($b->judul) }}"
                    data-author="{{ strtolower($b->pengarang) }}"
                    data-isbn="{{ strtolower($b->kode_buku) }}">
                    <div class="book-card ml-3 mb-1">
                        <!-- FOTO COVER -->
                        <div class="cover-wrapper">
                            @if($b->cover && file_exists(public_path('/storage/covers/' . $b->cover)))
                                <img src="{{ asset('/storage/covers/' . $b->cover) }}" alt="Cover Buku">
                            @else
                                <div class="no-cover">FOTO<br>BUKU</div>
                            @endif
                        </div>

                        <!-- INFORMASI -->
                        <div class="book-info-wrapper">
                            <div class="book-info">
                                <h5 class="book-title">{{ $b->judul }}</h5>
                                    <p class="book-meta">
                                        <div class="meta-row">
                                            <b>Pengarang:</b> {{ $b->pengarang }}
                                        </div>
                                        <div class="meta-row">
                                            <b>Penerbit:</b> {{ $b->penerbit }} ({{ $b->tahun }})
                                        </div>
                                        <div class="meta-row">
                                            <b>No. ISBN:</b> {{ $b->kode_buku }}
                                        </div>
                                    </p>
                                <span class="badge-stock">✓ Stok: {{ $b->stok_tersedia }}</span>
                            </div>
                        </div>  
                        <form action="{{ route('cetak.info', $b->id_buku) }}" method="GET" target="_blank">
                            <button type="submit" class="btn-detail">
                                <i class="bi bi-printer" style="margin-right: 4px"></i> Cetak Info
                            </button>
                        </form>

                    </div>
                </div>
                @endforeach
            </div>
                <!-- BUTTON NEXT / PREV -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>


    <!-- LOGIN BUTTON -->
    <div class="login-btn">
        <a href="/login" class="btn btn-primary btn-md">
            Login Pustakawan
        </a>
    </div>
    <br>
    <br>
    <br>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 4,
        spaceBetween: 25,
        loop: true,                 // INI WAJIB
        loopAdditionalSlides: 10,   // Biar perulangan mulus tanpa glitch
        speed: 300,                 // Transisi lebih smooth
        autoplay: {
            delay: 1500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            1400: { slidesPerView: 4 },
            1100: { slidesPerView: 3 },
            800: { slidesPerView: 2 },
            0: { slidesPerView: 1 },
        }
    });

    // LIVE SEARCH
document.getElementById("searchInput").addEventListener("keyup", function () {
    let keyword = this.value.toLowerCase();
    let slides = document.querySelectorAll(".swiper-slide");
    let wrapper = document.querySelector(".swiper-wrapper");

    let visibleCount = 0;

    slides.forEach(slide => {
        let title = slide.dataset.title;
        let author = slide.dataset.author;
        let isbn = slide.dataset.isbn;

        if (title.includes(keyword) || author.includes(keyword) || isbn.includes(keyword)) {
            slide.style.display = "flex";
            visibleCount++;
        } else {
            slide.style.display = "none";
        }
    });

    // Reset semua mode
    wrapper.classList.remove("center-mode");
    wrapper.classList.remove("center-multiple");

    if (keyword === "") {
        // Kembali normal
        swiper.update();
        return;
    }

    if (visibleCount === 1) {
        wrapper.classList.add("center-mode");      // 1 hasil → center
    } else if (visibleCount > 1) {
        wrapper.classList.add("center-multiple");  // >1 hasil → center tapi tetap horizontal
    }

    swiper.update();
});
</script>

</body>
</html>
