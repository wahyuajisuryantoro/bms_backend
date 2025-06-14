 <footer class="footer-part section pb-5" id="contacts">
        <div class="container">
            <div class="footer-info">
                <!-- row start -->
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="logo">
                            <a class="fs-4 text-light fw-bold" href="#"> <img
                                    src="{{ asset('landing/images/logo-app.png') }}" alt="Logo Bursa Mobil Solo"
                                    class="logo-img me-2" width="50" height="40">
                                Bursa Mobil Solo
                            </a>
                        </div>
                        <p class="mt-4 text-white-50">
                            Platform terpercaya untuk jual beli mobil bekas di Solo dan sekitarnya.
                            Hubungkan penjual dan pembeli dengan mudah, aman, dan transparan.
                        </p>
                        <h5 class="text-light">Download App</h5>
                        <div class="main-btn mt-4">
                            <a href="#" class="btn p-0 m-0 ms-2"><img
                                    src="{{ asset('landing/images/play-store.png') }}" alt=""></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-12 pt-2">
                        <h5 class="text-light fs-5">Link Cepat</h5>
                        <ul class="list-unstyled mt-4">
                            <li><a href="#home" class="text-white-50">Beranda</a></li>
                            <li><a href="#about" class="text-white-50">Tentang Kami</a></li>
                            <li><a href="#features" class="text-white-50">Layanan</a>
                            </li>
                            <li><a href="#download" class="text-white-50">Download</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 pt-2">
                        <div class="footer-icon">
                            <h5 class="text-light fs-5 mb-4">Sosial Media</h5>
                            <a href="https://www.instagram.com/trisnomotorsolo"
                                class="icon-link icon-link-hover text-white-50"><i
                                    class="ri-instagram-line mx-2 bi"></i>
                            </a>

                        </div>
                        <div class="mt-4">
                            <h5 class="text-light fs-5 mb-4">Kontak Kami</h5>
                            <div class="d-flex align-items-center">
                                <i class="ri-mail-send-line me-2 text-white-50"></i>
                                <a href="" class="text-white-50">
                                    bursamobilsolo1@gmail.com</a>
                            </div>
                            <form class="form subscribe">
                                <input placeholder="Email" class="form-control w-75" required>
                                <a href="#" class="submit"><i class="ri-send-plane-line"></i></a>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- row end -->
            </div>
        </div>
    </footer>
    <!-- footer-part end -->
    <div class="footer-copyright py-3">
        <div class="copy-info text-center text-light">
            ©
            <script>
                document.write(new Date().getFullYear())
            </script> Bursa Mobil Solo<a href="{{ route('landing') }}"
                class="text-white-50 text-decoration-underline">
            </a>
        </div>
    </div>