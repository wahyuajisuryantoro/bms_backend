<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Enhanced SEO Meta Tags -->
    <meta name="description"
        content="Bursa Mobil Solo - tempat jual beli mobil bekas terpercaya di Solo dengan pembayaran cash dan kredit. Marketplace mobil Surakarta yang menghubungkan penjual dan pembeli secara aman. Transaksi mudah, harga transparan, pilihan kredit fleksibel untuk semua jenis mobil bekas berkualitas." />

    <meta name="keywords"
        content="bursa mobil solo, jual beli mobil bekas solo, marketplace mobil solo, mobil bekas surakarta, bursa mobil surakarta, jual mobil cash solo, kredit mobil solo, kredit mobil surakarta, mobil bekas cash kredit solo, dealer mobil solo, showroom mobil solo, mobil second solo, mobil murah solo, beli mobil solo, jual mobil solo, pasar mobil solo, bursa otomotif solo, mobil bekas berkualitas solo, transaksi mobil solo, platform mobil solo, aplikasi jual mobil solo, situs mobil bekas solo, toko mobil solo, lelang mobil solo, mobil bekas terpercaya solo" />

    <!-- Long-tail Keywords for Local SEO -->
    <meta name="keywords-long-tail"
        content="tempat jual beli mobil bekas di solo, bursa mobil bekas solo surakarta, kredit mobil bekas tanpa dp solo, mobil bekas murah di solo jawa tengah, showroom mobil bekas terpercaya solo, dealer mobil second solo, pasar mobil bekas solo karanganyar, jual mobil bekas cash kredit solo, beli mobil bekas cicilan solo, platform jual beli mobil online solo" />

    <!-- Geographic and Category Specific -->
    <meta name="geo.keywords"
        content="solo, surakarta, karanganyar, sukoharjo, boyolali, klaten, wonogiri, sragen, jawa tengah, central java" />

    <meta name="category.keywords"
        content="sedan bekas solo, suv bekas solo, hatchback bekas solo, mpv bekas solo, pickup bekas solo, mobil sport bekas solo, mobil keluarga bekas solo, mobil murah solo, mobil mewah bekas solo" />

    <!-- Brand Specific Keywords -->
    <meta name="brand.keywords"
        content="toyota bekas solo, honda bekas solo, suzuki bekas solo, daihatsu bekas solo, mitsubishi bekas solo, nissan bekas solo, mazda bekas solo, hyundai bekas solo, kia bekas solo, ford bekas solo, chevrolet bekas solo, bmw bekas solo, mercedes bekas solo, audi bekas solo" />

    <!-- Transaction Type Keywords -->
    <meta name="transaction.keywords"
        content="cash mobil solo, kredit mobil solo, tukar tambah mobil solo, trade in mobil solo, dp murah mobil solo, cicilan mobil solo, leasing mobil solo, financing mobil solo, kredit tanpa dp solo, kredit bunga rendah solo" />

    <!-- Additional SEO Meta -->
    <meta name="subject" content="Bursa Mobil Bekas Solo - Jual Beli Cash Kredit" />
    <meta name="topic" content="Marketplace Mobil Bekas Solo Surakarta" />
    <meta name="summary"
        content="Platform terpercaya untuk transaksi mobil bekas di Solo dengan pilihan pembayaran cash dan kredit yang fleksibel" />
    <meta name="classification" content="Otomotif, Marketplace, Jual Beli Mobil, E-commerce" />
    <meta name="coverage" content="Solo, Surakarta, Karanganyar, Sukoharjo, Jawa Tengah" />
    <meta name="distribution" content="local" />
    <meta name="target" content="pembeli mobil bekas, penjual mobil bekas, dealer mobil, showroom mobil" />

    <!-- Local Business Schema Keywords -->
    <meta name="business.type" content="Automotive Marketplace, Car Dealership Platform, Vehicle Trading Platform" />
    <meta name="service.area" content="Solo, Surakarta, Karanganyar, Sukoharjo, Boyolali, Klaten, Sragen, Wonogiri" />
    <meta name="price.range" content="10000000-500000000" />
    <meta name="currency" content="IDR" />

    <!-- Search Intent Keywords -->
    <meta name="intent.keywords"
        content="cari mobil bekas solo, beli mobil second solo, jual mobil cepat solo, kredit mobil mudah solo, dp ringan mobil solo, mobil bekas berkualitas solo, showroom terpercaya solo, dealer resmi solo" />

    <!-- Seasonal/Trending Keywords -->
    <meta name="trending.keywords"
        content="mobil bekas 2024 solo, promo kredit mobil solo, diskon mobil bekas solo, bursa mobil akhir tahun solo, mobil bekas terbaru solo, update harga mobil solo" />
    <meta name="author" content="bursamobilsolo.com" />
    <meta name="robots" content="index, follow" />
    <meta name="language" content="id" />
    <meta name="geo.region" content="ID-JI" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="Bursa Mobil Solo" />
    <meta name="application-name" content="Bursa Mobil Solo" />
    <meta name="theme-color" content="#1a73e8" />
    <meta name="msapplication-TileColor" content="#1a73e8" />
    <title>Bursa Mobil Solo - Jual Beli Cash Kredit Terpercaya</title>
    <link rel="icon" type="images/x-icon" href="{{ asset('landing/images/icon.png') }}" />

    <!-- remix icon  -->
    <link rel="stylesheet" href="{{ asset('landing/css/remixicon.css') }}" />
    <!-- Swiper-slider Css -->
    <link rel="stylesheet" href="{{ asset('landing/css/swiper-bundle.min.css') }}" />
    <!-- bootstrap  -->
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/css/style.min.css') }}" />
</head>

<body data-bs-spy="scroll" data-bs-target="#navbarCollapse">
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <video id="VisaChipCardVideo" class="video-box" controls>
                            <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4" />
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('landing.partial_landing.header')
    @yield('content')
    @include('landing.partial_landing.footer')
    <script src="{{ asset('landing/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('landing/js/swiper-bundle.min.js') }}"></script>
    <script src="https://cdn.lordicon.com/lordicon-1.1.0.js"></script>
    <script src="{{ asset('landing/js/app.js') }}"></script>
</body>

</html>
