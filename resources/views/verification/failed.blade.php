<!-- resources/views/verification/failed.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email Gagal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 500px;
            margin: 0 auto;
        }
        .error-icon {
            color: #F44336;
            font-size: 64px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #1E3A8A;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn-secondary {
            background-color: #757575;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">✕</div>
        <h1>Verifikasi Email Gagal</h1>
        <p>{{ $message }}</p>
        <p>Mohon mencoba kembali atau mendaftar ulang jika masalah berlanjut.</p>
        <div>
            <a href="dealermobil://register" class="btn">Daftar Ulang</a>
            <a href="dealermobil://login" class="btn btn-secondary">Coba Login</a>
        </div>
    </div>
</body>
</html>