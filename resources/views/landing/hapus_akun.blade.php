@extends('landing.index')
@section('content')
   <!-- hapus akun section start -->
<section class="section guide-app" id="hapus-akun">
    <div class="container">
        <div class="guide-header text-center">
            <div class="title-sm">
                <h6 class="text-primary">Hapus Akun</h6>
            </div>
            <div class="main-title mt-4">
                <h2 class="display-6 lh-55 text-dark">
                    Cara Menghapus Akun Bursa Mobil Solo
                </h2>
                <p>
                    Ikuti prosedur berikut untuk menghapus akun Anda secara permanen
                </p>
            </div>
        </div>
        
        <!-- Peringatan -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="alert alert-warning text-center">
                    <h6><i class="ri-error-warning-line me-2"></i>Peringatan Penting!</h6>
                    <p class="mb-0">Penghapusan akun bersifat <strong>permanen</strong> dan tidak dapat dibatalkan. Semua data akan dihapus dari sistem.</p>
                </div>
            </div>
        </div>

        <div class="guide-content text-start mt-5">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-3">
                    <h4 class="text-primary mt-4"><strong>1.</strong> Hubungi Admin</h4>
                    <p class="my-3">Kirim pesan WhatsApp ke admin dengan nomor yang tersedia di aplikasi</p>
                </div>
                <div class="col-lg-1 d-none d-lg-block">
                    <i class="ri-arrow-right-circle-fill ms-lg-5 fs-2 text-primary"></i>
                </div>
                <div class="col-lg-3 ms-lg-5 my-4">
                    <h4 class="text-primary"><strong>2.</strong> Kirim Data Diri</h4>
                    <p class="my-3">Sertakan username, nama lengkap, alamat kota/kabupaten, dan bukti transaksi (jika ada)</p>
                </div>
                <div class="col-lg-1 d-none d-lg-block">
                    <i class="ri-arrow-right-circle-fill ms-lg-5 fs-2 text-primary"></i>
                </div>
                <div class="col-lg-3 ms-lg-5">
                    <h4 class="text-primary"><strong>3.</strong> Tunggu Proses</h4>
                    <p class="my-3">Admin akan memproses permintaan Anda paling lama 2 hari kerja</p>
                </div>
            </div>
        </div>

        <!-- Template Pesan -->
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <div class="bg-light p-4 rounded">
                    <h5 class="text-dark mb-3"><i class="ri-message-3-line text-primary me-2"></i>Template Pesan WhatsApp</h5>
                    <div class="bg-white p-3 rounded border">
                        <p class="mb-2"><strong>Contoh pesan:</strong></p>
                        <p class="mb-1">Halo Admin Bursa Mobil Solo,</p>
                        <p class="mb-1">Saya ingin menghapus akun dengan data:</p>
                        <p class="mb-1">• Username: [username_anda]</p>
                        <p class="mb-1">• Nama: [nama_lengkap]</p>
                        <p class="mb-1">• Alamat: [kota/kabupaten]</p>
                        <p class="mb-1">• Bukti Transaksi: [jika ada]</p>
                        <p class="mb-0">Mohon diproses. Terima kasih.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Tambahan -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-8">
                <div class="alert alert-info">
                    <h6><i class="ri-information-line me-2"></i>Informasi Tambahan:</h6>
                    <ul class="mb-0">
                        <li>Semua data pribadi, riwayat, dan favorit akan dihapus</li>
                        <li>Email yang sama tidak dapat digunakan untuk registrasi ulang</li>
                        <li>Proses tidak dapat dibatalkan setelah dikonfirmasi admin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- hapus akun section end -->
@endsection
