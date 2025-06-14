<header>
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom sticky sticky-light" id="navbar">
        <div class="container">
            <div class="navbar-brand logo variant-horizontal">
                <a class="navbar-caption fs-4 text-primary fw-bold d-flex align-items-center" href="{{route('landing')}}">
                    <img src="{{ asset('landing/images/logo-app.png') }}" alt="Logo Bursa Mobil Solo" class="logo-img me-2"
                        width="50" height="40">
                    <span class="brand-text">Bursa Mobil Solo</span>
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fw-bold text-dark fs-4"><i class="ri-menu-5-line"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav ms-auto" id="navbar-navlist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#features">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#download">Download</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="bantuanDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-customer-service-2-line me-1"></i>
                            Bantuan
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bantuanDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('privasi-kebijakan') }}">
                                    <i class="ri-shield-check-line me-2 text-primary"></i>
                                    Privasi & Kebijakan
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('hapus-akun') }}">
                                    <i class="ri-delete-bin-line me-2 text-danger"></i>
                                    Pengajuan Hapus Akun
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="nav-btn">
                    <a href="#contacts" class="btn btn-light text-primary rounded-2 border">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </nav>
</header>
