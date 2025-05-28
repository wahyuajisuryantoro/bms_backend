<!-- resources/views/verification/success.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email Berhasil</title>
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
        .success-icon {
            color: #4CAF50;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1>Verifikasi Email Berhasil</h1>
        <p>{{ $message }}</p>
        <p>Sekarang Anda dapat login ke aplikasi menggunakan email dan password yang telah terdaftar.</p>
        <a href="dealermobil://login" class="btn">Buka Aplikasi</a>
    </div>
</body>
</html>