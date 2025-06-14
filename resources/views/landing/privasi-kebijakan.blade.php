@extends('landing.index')
@section('content')

<!-- hero section start -->
<section class="hero-part bg-img bg-light" style="padding-top: 120px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="hero-text">
                    <h1 class="display-6 lh-55 text-dark">
                        <i class="ri-shield-check-line text-primary me-3"></i>
                        Privasi & Kebijakan
                    </h1>
                    <p class="fw-medium mt-3 lh-base text-muted">
                        Komitmen kami untuk melindungi data dan privasi pengguna Bursa Mobil Solo
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- hero section end -->

<!-- kebijakan privasi section start -->
<section class="section about-part-1 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Informasi Umum -->
                <div class="bg-white p-5 shadow-sm rounded-3 mb-4">
                    <div class="about-header">
                        <div class="title-sm">
                            <h6 class="text-primary">Informasi Umum</h6>
                        </div>
                        <div class="main-title mt-4">
                            <h2 class="display-6 lh-55 text-dark">
                                Kebijakan Privasi <span class="fw-semibold text-primary">Bursa Mobil Solo</span>
                            </h2>
                        </div>
                    </div>
                    <p class="mt-3 lh-base">
                        Kebijakan Privasi ini menjelaskan bagaimana Bursa Mobil Solo ("kami", "kita", atau "aplikasi") 
                        mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami. 
                        Dengan menggunakan aplikasi ini, Anda menyetujui praktik yang dijelaskan dalam kebijakan ini.
                    </p>
                    <div class="alert alert-info mt-4">
                        <i class="ri-information-line me-2"></i>
                        <strong>Terakhir diperbarui:</strong> Juni 2025
                    </div>
                </div>

                <!-- Data yang Dikumpulkan -->
                <div class="bg-white p-5 shadow-sm rounded-3 mb-4">
                    <h4 class="text-primary mb-4">
                        <i class="ri-database-2-line me-2"></i>
                        Data yang Kami Kumpulkan
                    </h4>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-bold">Data Akun:</h6>
                            <ul class="list-unstyled ms-3">
                                <li><i class="ri-check-line text-success me-2"></i>Nama lengkap</li>
                                <li><i class="ri-check-line text-success me-2"></i>Email address</li>
                                <li><i class="ri-check-line text-success me-2"></i>Password (terenkripsi)</li>
                                <li><i class="ri-check-line text-success me-2"></i>Role pengguna (user/admin)</li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-bold">Data Profil:</h6>
                            <ul class="list-unstyled ms-3">
                                <li><i class="ri-check-line text-success me-2"></i>Nomor WhatsApp</li>
                                <li><i class="ri-check-line text-success me-2"></i>Foto profil</li>
                                <li><i class="ri-check-line text-success me-2"></i>Alamat lengkap</li>
                                <li><i class="ri-check-line text-success me-2"></i>Dusun/RT/RW</li>
                                <li><i class="ri-check-line text-success me-2"></i>Kode pos</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-bold">Data Lokasi:</h6>
                            <ul class="list-unstyled ms-3">
                                <li><i class="ri-check-line text-success me-2"></i>Provinsi</li>
                                <li><i class="ri-check-line text-success me-2"></i>Kabupaten/Kota</li>
                                <li><i class="ri-check-line text-success me-2"></i>Kecamatan</li>
                                <li><i class="ri-check-line text-success me-2"></i>Kelurahan/Desa</li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-bold">Data Teknis:</h6>
                            <ul class="list-unstyled ms-3">
                                <li><i class="ri-check-line text-success me-2"></i>Session data</li>
                                <li><i class="ri-check-line text-success me-2"></i>Cache data</li>
                                <li><i class="ri-check-line text-success me-2"></i>Personal access tokens</li>
                                <li><i class="ri-check-line text-success me-2"></i>Password reset tokens</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cara Pengumpulan Data -->
                <div class="bg-white p-5 shadow-sm rounded-3 mb-4">
                    <h4 class="text-primary mb-4">
                        <i class="ri-user-add-line me-2"></i>
                        Cara Kami Mengumpulkan Data
                    </h4>
                    
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="ri-number-1 p-2 bg-primary text-light rounded-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-dark">Registrasi Akun</h6>
                            <p>Saat Anda mendaftar, kami mengumpulkan nama, email, password, dan nomor WhatsApp yang Anda berikan secara sukarela.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="ri-number-2 p-2 bg-primary text-light rounded-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-dark">Verifikasi Email</h6>
                            <p>Kami mengirim email verifikasi ke alamat email yang Anda daftarkan untuk memastikan keabsahan akun.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <i class="ri-number-3 p-2 bg-primary text-light rounded-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-dark">Kelengkapan Profil</h6>
                            <p>Setelah verifikasi, Anda diminta melengkapi profil dengan data lokasi dan informasi kontak untuk keamanan transaksi.</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="me-3">
                            <i class="ri-number-4 p-2 bg-primary text-light rounded-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-dark">Penggunaan Aplikasi</h6>
                            <p>Data session dan cache dikumpulkan secara otomatis untuk meningkatkan performa dan keamanan aplikasi.</p>
                        </div>
                    </div>
                </div>

                <!-- Penggunaan Data -->
                <div class="bg-white p-5 shadow-sm rounded-3 mb-4">
                    <h4 class="text-primary mb-4">
                        <i class="ri-settings-3-line me-2"></i>
                        Penggunaan Data
                    </h4>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="d-flex mb-3">
                                <i class="ri-shield-check-line text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="text-dark">Keamanan Akun</h6>
                                    <p class="small">Verifikasi identitas dan mencegah akun palsu</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="ri-user-line text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="text-dark">Personalisasi</h6>
                                    <p class="small">Menyesuaikan pengalaman pengguna berdasarkan lokasi</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="ri-mail-line text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="text-dark">Komunikasi</h6>
                                    <p class="small">Mengirim notifikasi penting dan update aplikasi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex mb-3">
                                <i class="ri-search-line text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="text-dark">Pencarian Lokal</h6>
                                    <p class="small">Menampilkan mobil di area terdekat pengguna</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="ri-customer-service-line text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="text-dark">Dukungan Pelanggan</h6>
                                    <p class="small">Memberikan bantuan dan menyelesaikan masalah</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="ri-bar-chart-line text-success me-3 mt-1"></i>
                                <div>
                                    <h6 class="text-dark">Analisis</h6>
                                    <p class="small">Meningkatkan kualitas layanan aplikasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keamanan Data -->
                <div class="bg-white p-5 shadow-sm rounded-3 mb-4">
                    <h4 class="text-primary mb-4">
                        <i class="ri-lock-2-line me-2"></i>
                        Keamanan Data
                    </h4>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-bold mb-3">Perlindungan Data:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Password terenkripsi dengan algoritma bcrypt</li>
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Koneksi SSL/TLS untuk semua transmisi data</li>
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Database dengan sistem backup berkala</li>
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Access control dan user authentication</li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-bold mb-3">Kontrol Akses:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Session management dengan timeout otomatis</li>
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Personal access tokens untuk API</li>
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Rate limiting untuk mencegah abuse</li>
                                <li class="mb-2"><i class="ri-check-double-line text-success me-2"></i>Role-based access (admin/user)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Hak Pengguna -->
                <div class="bg-white p-5 shadow-sm rounded-3 mb-4">
                    <h4 class="text-primary mb-4">
                        <i class="ri-user-settings-line me-2"></i>
                        Hak-Hak Anda
                    </h4>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border-0 bg-light p-3 mb-3">
                                <h6 class="text-dark"><i class="ri-eye-line text-primary me-2"></i>Akses Data</h6>
                                <p class="small mb-0">Anda dapat melihat dan mengunduh data pribadi yang kami simpan</p>
                            </div>
                            <div class="card border-0 bg-light p-3 mb-3">
                                <h6 class="text-dark"><i class="ri-edit-line text-primary me-2"></i>Koreksi Data</h6>
                                <p class="small mb-0">Anda dapat memperbarui atau memperbaiki data yang tidak akurat</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border-0 bg-light p-3 mb-3">
                                <h6 class="text-dark"><i class="ri-delete-bin-line text-danger me-2"></i>Hapus Data</h6>
                                <p class="small mb-0">Anda dapat meminta penghapusan akun dan data secara permanen</p>
                            </div>
                            <div class="card border-0 bg-light p-3 mb-3">
                                <h6 class="text-dark"><i class="ri-download-line text-primary me-2"></i>Portabilitas</h6>
                                <p class="small mb-0">Anda dapat meminta ekspor data dalam format yang dapat dibaca</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kebijakan Penggunaan -->
                <div class="bg-white p-5 shadow-sm rounded-3 mb-4">
                    <h4 class="text-primary mb-4">
                        <i class="ri-file-list-3-line me-2"></i>
                        Ketentuan Penggunaan
                    </h4>
                    
                    <div class="alert alert-warning">
                        <h6><i class="ri-error-warning-line me-2"></i>Hal yang Dilarang:</h6>
                        <ul class="mb-0">
                            <li>Membuat akun palsu atau menggunakan identitas orang lain</li>
                            <li>Menyalahgunakan fitur komunikasi untuk spam atau penipuan</li>
                            <li>Mengupload konten yang melanggar hukum atau tidak pantas</li>
                            <li>Mencoba mengakses data pengguna lain tanpa izin</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-success mt-3">
                        <h6><i class="ri-check-double-line me-2"></i>Penggunaan yang Benar:</h6>
                        <ul class="mb-0">
                            <li>Menggunakan data pribadi yang akurat dan valid</li>
                            <li>Menjaga kerahasiaan password dan informasi akun</li>
                            <li>Berinteraksi dengan pengguna lain secara sopan dan profesional</li>
                            <li>Melaporkan aktivitas mencurigakan ke admin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- kebijakan privasi section end -->

@endsection