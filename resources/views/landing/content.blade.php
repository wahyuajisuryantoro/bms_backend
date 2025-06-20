@extends('landing.index')
@section('content')
     <!-- hero section start  -->
    <section class="hero-part bg-img bg-home" id="home">
        <div class="container">
            <!-- row start  -->
            <div class="row position-relative align-items-center justify-content-center">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="headline mb-4">
                        <a href="#" class="bg-light">
                            <span class="badge text-bg-primary">Terbaru</span>
                            Aplikasi Bursa Mobil Solo Sudah Live!
                            <i class="ri-arrow-right-line"></i>
                            <span class="ti-angle-right"></span>
                        </a>
                    </div>
                    <div class="hero-text">
                        <h1 class="display-6 lh-55 text-dark">
                            Jual Beli Mobil Bekas di
                            <span class="fw-semibold text-primary">
                                Solo</span>
                            Jadi Lebih Mudah
                        </h1>
                        <p class="fw-medium mt-3 lh-base">
                            Platform terpercaya untuk transaksi mobil bekas di Solo dan sekitarnya.
                            <br />
                            Temukan mobil impian atau jual mobil Anda dengan aman dan cepat.
                        </p>
                    </div>

                    <div class="main-btn mt-4">
                        <a href="#" class="btn btn-primary fs-6 mb-3 me-3" id="playstore-download">
                            <img src="{{ asset('landing/images/playstore.png') }}" alt="Download di Google Play Store"
                                class="border-end pe-2" />
                            <span class="ps-2">Google Play</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="img-phone text-center mb-3">
                        <img src="{{ asset('landing/images/hero/hero.png') }}" alt class="img-fluid w-100" />
                    </div>
                </div>
            </div>
            <!-- row end  -->
        </div>
    </section>
    <!-- hero section end  -->
    <!-- brand section start  -->
    {{-- <section class="section brand-part">
        <div class="container">
            <!-- row start  -->
            <div class="row justify-content-center text-center g-4">
                <div class="col-lg-3 col-5">
                    <a href="#"><img src="{{asset('landing/images/logo/1.png')}}" alt class="img-fluid" /></a>
                </div>
                <div class="col-lg-3 col-5">
                    <a href="#"><img src="images/logo/2.png" alt class="img-fluid" /></a>
                </div>
                <div class="col-lg-3 col-5">
                    <a href="#"><img src="images/logo/3.png" alt class="img-fluid" /></a>
                </div>
                <div class="col-lg-3 col-5">
                    <a href="#"><img src="images/logo/4.png" alt class="img-fluid" /></a>
                </div>
            </div>
            <!-- row end  -->
        </div>
    </section> --}}
    <!-- brand section end  -->
    <!-- about section start  -->
    <section class="section about-part-1 bg-light" id="about">
        <div class="container">
            <!-- row start  -->
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5">
                    <div class="bg-white p-5 shadow-sm rounded-3 mt-4 position-relative overflow-hidden">
                        <div class="about-header">
                            <div class="title-sm">
                                <h6 class="text-primary">Kenapa Pilih Kami?</h6>
                            </div>
                            <div class="main-title mt-4">
                                <h2 class="display-6 lh-55 text-dark">
                                    Platform terbaik untuk<br />
                                    <span class="fw-semibold text-primary">
                                        transaksi mobil </span>
                                    di Solo
                                </h2>
                            </div>
                        </div>
                        <p class="mt-3 lh-base">
                            Aplikasi yang mudah digunakan dengan fitur lengkap
                            <br />
                            untuk jual beli mobil bekas secara aman dan<br />
                            terpercaya di Solo dan sekitarnya.
                        </p>
                        <div class="btn-part mt-4">
                            <a href="#" class="btn btn-primary fs-6">Pelajari
                                Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row mt-2 g-3">
                        <div class="col-lg-6">
                            <div class="icon-text d-flex bg-white p-3 rounded-3">
                                <div class="icon-container">
                                    <i class="ri-hand-coin-line text-primary" style="font-size: 48px;"></i>
                                </div>
                                <div class="ms-3 mt-3">
                                    <h3>Cash & Kredit</h3>
                                    <p class="text-dark fs-6 mb-2">Pembayaran Fleksibel</p>
                                    <p>Beli mobil dengan cash atau kredit sesuai kemampuan Anda</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="icon-text d-flex bg-white p-3 rounded-3">
                                <div class="icon-container">
                                    <i class="ri-smartphone-line text-primary" style="font-size: 48px;"></i>
                                </div>
                                <div class="ms-3 mt-3">
                                    <h3>Mudah Digunakan</h3>
                                    <p class="text-dark fs-6 mb-2">Interface Simple</p>
                                    <p>Aplikasi dengan tampilan yang user-friendly dan mudah dipahami</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 g-3">
                        <div class="col-lg-6">
                            <div class="icon-text d-flex bg-white p-3 rounded-3">
                                <div class="icon-container">
                                    <i class="ri-shield-check-line text-primary" style="font-size: 48px;"></i>
                                </div>
                                <div class="ms-3 mt-3">
                                    <h3>Aman & Terpercaya</h3>
                                    <p class="text-dark fs-6 mb-2">Transaksi Secure</p>
                                    <p>Setiap transaksi dijamin aman dengan verifikasi berlapis</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="icon-text d-flex bg-white p-3 rounded-3">
                                <div class="icon-container">
                                    <i class="ri-map-pin-line text-primary" style="font-size: 48px;"></i>
                                </div>
                                <div class="ms-3 mt-3">
                                    <h3>Khusus Solo</h3>
                                    <p class="text-dark fs-6 mb-2">Fokus Lokal</p>
                                    <p>Platform khusus untuk wilayah Solo dan area sekitarnya</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end  -->
        </div>
    </section>
    <!-- about section end  -->
    <!-- feature section start  -->
    <section class="section feature-part pb-0" id="features">
        <div class="container">
            <!-- row start -->
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-6">
                    <div class="feature-img">
                        <div class="img-phone">
                            <img src="{{ asset('landing/images/services/fitur.png') }}"
                                alt="Fitur Aplikasi Bursa Mobil Solo" class="img-fluid" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-header">
                        <div class="title-sm">
                            <h6 class="text-primary">Fitur Unggulan</h6>
                        </div>
                        <div class="main-title mt-4">
                            <h2 class="display-6 lh-55 text-dark">
                                Solusi lengkap untuk <br />
                                <span class="fw-semibold text-primary">jual beli
                                    mobil</span> di Solo
                            </h2>
                        </div>
                    </div>
                    <p class="fw-medium mt-3">
                        Platform yang dirancang khusus untuk memudahkan transaksi
                        mobil bekas di Solo dengan fitur-fitur canggih dan
                        pengalaman pengguna yang optimal.
                    </p>
                    <div class="d-flex mt-4">
                        <div class="number">
                            <i class="ri-search-line fs-5 p-2 text-dark bg-primary-subtle rounded-circle"></i>
                        </div>
                        <div class="text ms-3">
                            <h6 class="text-dark">Pencarian Cerdas</h6>
                            <p>
                                Filter mobil berdasarkan merk, harga, tahun, dan lokasi
                                dengan sistem pencarian yang akurat dan mudah digunakan.
                            </p>
                        </div>
                    </div>
                    <div class="d-flex pb-2 mt-4">
                        <div class="number">
                            <i class="ri-whatsapp-line fs-5 p-2 text-dark bg-primary-subtle rounded-circle"></i>
                        </div>
                        <div class="text ms-3">
                            <h6 class="text-dark">Konsultasi WhatsApp</h6>
                            <p>
                                Terhubung langsung ke WhatsApp penjual dengan informasi
                                mobil yang sudah otomatis terisi untuk konsultasi
                                dan negosiasi yang lebih mudah.
                            </p>
                        </div>
                    </div>
                    <div class="d-flex pb-2 mt-4">
                        <div class="number">
                            <i class="ri-bank-card-line fs-5 p-2 text-dark bg-primary-subtle rounded-circle"></i>
                        </div>
                        <div class="text ms-3">
                            <h6 class="text-dark">Simulasi Kredit</h6>
                            <p>
                                Hitung cicilan mobil dengan berbagai skema kredit
                                dan dapatkan rekomendasi leasing terpercaya di Solo.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row end  -->
        </div>
    </section>
    <!-- feature section end  -->

    <section class="section guide-app" id="download">
        <div class="container">
            <div class="guide-header text-center">
                <div class="title-sm">
                    <h6 class="text-primary">Panduan Aplikasi</h6>
                </div>
                <div class="main-title mt-4">
                    <h2 class="display-6 lh-55 text-dark">
                        Cara menggunakan Aplikasi Bursa Mobil Solo
                    </h2>
                    <p>
                        Ikuti langkah mudah untuk mulai jual beli mobil di Solo
                    </p>
                </div>
            </div>
            <div class="guide-content text-start mt-5">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-3">
                        <h4 class="text-primary mt-4"><strong>1.</strong> Download Aplikasi</h4>
                        <p class="my-3">Install aplikasi Bursa Mobil Solo di perangkat Android Anda</p>
                        <div class="main-btn mt-4 d-flex">
                            <a href="#" class="btn p-0 m-0"><img
                                    src="{{ asset('landing/images/playstore.png') }}"
                                    alt="Download di App Store"><span class="ps-2">Google Play</span></a>


                        </div>
                    </div>
                    <div class="col-lg-1 d-none d-lg-block">
                        <i class="ri-arrow-right-circle-fill ms-lg-5 fs-2 text-primary"></i>
                    </div>
                    <div class="col-lg-3 ms-lg-5 my-4">
                        <h4 class="text-primary"><strong>2.</strong> Cari Mobil Impian</h4>
                        <p class="my-3">Gunakan filter pencarian untuk menemukan mobil sesuai budget dan kebutuhan di
                            Solo</p>

                    </div>
                    <div class="col-lg-1 d-none d-lg-block">
                        <i class="ri-arrow-right-circle-fill ms-lg-5 fs-2 text-primary"></i>
                    </div>
                    <div class="col-lg-3 ms-lg-5">
                        <h4 class="text-primary"><strong>3.</strong> Konsultasi & Beli</h4>
                        <p class="my-3">Hubungi penjual via WhatsApp untuk negosiasi harga</p>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section app-screenshort position-relative overflow-hidden">
        <div class="container">
            <div class="ss-header text-center mb-5 pb-5">
                <div class="title-sm">
                    <h6 class="text-primary">Aplikasi Kami</h6>
                </div>
                <div class="main-title mt-4">
                    <h2 class="text-dark display-6 lh-55">
                        Screenshot Aplikasi Bursa Mobil Solo
                    </h2>
                    <p class="mt-3 lh-base">

                    </p>
                </div>
            </div>
            <div class="screenshort mt-5">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-12">
                        <div class="swiper mySwiper overflow-hidden">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide"><img
                                        src="{{ asset('landing/images/swiper/onboarding.jpg') }}" alt=""
                                        class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/login.jpg') }}"
                                        alt="" class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img
                                        src="{{ asset('landing/images/swiper/register.jpg') }}" alt=""
                                        class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/home.jpg') }}"
                                        alt="" class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/produk.jpg') }}"
                                        alt="" class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/detail.jpg') }}"
                                        alt="" class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/filter.jpg') }}"
                                        alt="" class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img
                                        src="{{ asset('landing/images/swiper/simulasi.jpg') }}" alt=""
                                        class="img-fluid w-75"></div>
                                <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/favorit.jpg') }}"
                                        alt="" class="img-fluid w-75"></div>
                                {{-- <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/akun.jpg') }}"
                                        alt="" class="img-fluid w-75"></div> --}}
                                <div class="swiper-slide"><img src="{{ asset('landing/images/swiper/tentang.jpg') }}"
                                        alt="" class="img-fluid w-75"></div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

     <!-- price section end  -->
    <section class="section faq">
        <div class="container">
            <div class="contact-header text-center mb-5">
                <div class="title-sm">
                    <h6 class="text-primary">Butuh Bantuan?</h6>
                </div>
                <div class="main-title my-4">
                    <h2 class="display-6 text-dark">
                        Pertanyaan yang Sering Diajukan
                    </h2>
                    <p class="mt-3 lh-base">
                        Temukan jawaban dari pertanyaan umum seputar aplikasi Bursa Mobil Solo
                    </p>
                </div>
            </div>
            <div class="row align-items-center justify-content-between mt-5">
                <div class="col-lg-5">
                    <h5 class="lh-base">
                        <i class="ri-number-1 me-3 p-2 bg-primary text-light rounded-circle align-middle"></i>
                        Bagaimana cara pembayaran di Bursa Mobil Solo?
                    </h5>
                    <p class="ms-5">Pembayaran dilakukan secara langsung di lokasi antara pembeli dan penjual.
                        Aplikasi kami hanya memfasilitasi pencarian dan komunikasi, bukan untuk transaksi pembayaran
                        online.</p>

                    <h5 class="mt-5">
                        <i class="ri-number-2 me-3 p-2 bg-primary text-light rounded-circle align-middle"></i>
                        Data apa saja yang dikumpulkan saat registrasi?
                    </h5>
                    <p class="ms-5">Kami mengumpulkan email, password untuk login, foto profil, identitas diri (nama
                        lengkap, lokasi kota/kecamatan/desa), dan alamat lengkap untuk verifikasi akun dan keamanan
                        transaksi.</p>

                    <h5 class="mt-5">
                        <i class="ri-number-3 me-3 p-2 bg-primary text-light rounded-circle align-middle"></i>
                        Bagaimana jika aplikasi mengalami crash atau kendala teknis?
                    </h5>
                    <p class="ms-5">Jika terjadi masalah teknis, Anda dapat menghubungi:
                        <br><strong>Admin:</strong> <a href="tel:+6282325411811" class="text-primary">+62
                            823-2541-1811</a>
                        <br><strong>Developer:</strong> <a href="tel:081229985420"
                            class="text-primary">081229985420</a>
                    </p>
                </div>
                <div class="col-lg-5">
                    <h5 class="lh-base">
                        <i class="ri-number-4 me-3 p-2 bg-primary text-light rounded-circle align-middle"></i>
                        Apakah bisa menghapus akun secara permanen?
                    </h5>
                    <p class="ms-5">Ya, tersedia fitur hapus akun di pengaturan aplikasi. Semua data Anda akan
                        dihapus permanen dari database sistem dan tidak dapat dipulihkan kembali.</p>

                    <h5 class="mt-5">
                        <i class="ri-number-5 me-3 p-2 bg-primary text-light rounded-circle align-middle"></i>
                        Apakah aplikasi ini gratis untuk digunakan?
                    </h5>
                    <p class="ms-5">Ya, aplikasi Bursa Mobil Solo sepenuhnya gratis untuk download dan penggunaan.
                        Tidak ada biaya berlangganan atau biaya tersembunyi untuk mencari dan menghubungi penjual.</p>

                    <h5 class="mt-5 lh-base">
                        <i class="ri-number-6 me-3 p-2 bg-primary text-light rounded-circle align-middle"></i>
                        Bagaimana cara memverifikasi identitas penjual/pembeli?
                    </h5>
                    <p class="ms-5">Setiap pengguna wajib melengkapi data identitas dan alamat saat registrasi. Kami
                        sarankan untuk selalu bertemu di tempat aman dan membawa dokumen identitas saat transaksi
                        offline.</p>
                </div>
            </div>


        </div>
    </section>
    <!-- video section start  -->
    {{-- <section class="app-video hero-part section position-relative">
        <div class="bg-overlay"></div>
        <div class="container">
            <!-- row start  -->
            <div class="row position-relative align-items-center text-center justify-content-center">
                <div class="col-lg-6">
                    <h2 class="text-light display-6 lh-55">Video Presentation
                    </h2>
                    <a href="#" class="btn text-primary" data-bs-target="#exampleModal"
                        data-bs-toggle="modal"><i
                            class="ri-live-line fs-3 p-3 rounded-circle bg-light fw-normal"></i></a>
                </div>
            </div>
        </div>
        <!-- row start  -->
        </div>
    </section> --}}
    <!-- video section end  -->
    <!-- contact-part start  -->
    <section class="section contact-part bg-light" id="contacts">
        <div class="container">
            <div class="contact-header text-center mb-5">
                <div class="title-sm">
                    <h6 class="text-primary">Kontak</h6>
                </div>
                <div class="main-title my-4">
                    <h2 class="display-6 text-dark">
                        Hubungi <span class="text-primary fw-semibold">Tim
                            Kami</span>
                    </h2>
                    <p class="mt-3 lh-base">
                        Ada pertanyaan atau butuh bantuan? Silakan isi form kontak
                        atau hubungi langsung tim support Bursa Mobil Solo
                    </p>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="contact-box px-5 rounded-2">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-lg-6">
                                <div class="custom-form p-3">
                                    <h5 class="mb-4">Butuh Bantuan :</h5>
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show mb-4"
                                            role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show mb-4"
                                            role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('landing.kontak') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <input name="name" id="name" type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        placeholder="Nama" value="{{ old('name') }}" required />
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col end -->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <input name="email" id="email" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        placeholder="Email" value="{{ old('email') }}" required />
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col end -->
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <input name="subject" type="text"
                                                        class="form-control @error('subject') is-invalid @enderror"
                                                        id="subject" placeholder="Subject"
                                                        value="{{ old('subject') }}" required />
                                                    @error('subject')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col end -->
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                                                        required placeholder="Pesan">{{ old('message') }}</textarea>
                                                    @error('message')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col end -->
                                        </div>
                                        <!-- row end -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary fs-6">
                                                    Kirim Pesan
                                                </button>
                                            </div>
                                            <!-- col end -->
                                        </div>
                                        <!-- row end -->
                                    </form>
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-lg-5">
                                <img src="{{ asset('landing/images/Contact-us.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                        <!-- row end -->
                    </div>
                </div>
            </div>
            <!-- row end -->
        </div>
    </section>

@endsection