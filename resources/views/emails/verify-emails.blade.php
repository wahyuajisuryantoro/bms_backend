<!-- resources/views/emails/verify.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #1E3A8A;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Verifikasi Email Anda</h2>
        </div>
        
        <p>Halo {{ isset($userData['name']) ? $userData['name'] : 'Calon Pengguna' }},</p>
        
        <p>Terima kasih telah mendaftar. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:</p>
        
        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="button">Verifikasi Email</a>
        </div>
        
        <p>Jika Anda tidak merasa mendaftar, silakan abaikan email ini.</p>
        
        <p>Link verifikasi ini akan kedaluwarsa dalam 24 jam.</p>
        
        <p>Jika Anda mengalami masalah dengan tombol di atas, salin dan tempel URL berikut ke browser Anda:</p>
        <p style="word-break: break-all;">{{ $verificationUrl }}</p>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>