<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #1D1616;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #E9E9E9;
        }
        .container {
            background-color: #E9E9E9;
            padding: 30px;
            border-radius: 10px;
        }
        .content {
            background-color: #FFFFFF;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.16);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            color: #0156BF;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        h2 {
            color: #0156BF;
            margin-bottom: 20px;
        }
        p {
            color: #1D1616;
            margin-bottom: 15px;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background-color: #0156BF;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .button:hover {
            background-color: #014a9d;
        }
        .link-container {
            background-color: #EDEDED;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            word-break: break-all;
        }
        .link-text {
            color: #0156BF;
            font-size: 14px;
        }
        .warning-box {
            background-color: #FFF9E6;
            border-left: 4px solid #FFDE00;
            padding: 15px;
            margin: 20px 0;
            border-radius: 3px;
        }
        .warning-box p {
            margin: 0;
            color: #1D1616;
        }
        .info-box {
            background-color: #E8F4F8;
            border-left: 4px solid #3674B5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 3px;
        }
        .info-box p {
            margin: 0;
            color: #1D1616;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E9E9E9;
            font-size: 12px;
            color: #616161;
            text-align: center;
        }
        .divider {
            height: 1px;
            background-color: #E9E9E9;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="header">
                <div class="logo">Bursa Mobil Solo</div>
            </div>
            
            <h2>Reset Password Anda</h2>
            
            <p>Halo,</p>
            
            <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>BMS</strong>.</p>
            
            <p>Klik tombol di bawah ini untuk melanjutkan proses reset password:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password Sekarang</a>
            </div>
            
            <div class="divider"></div>
            
            <p style="font-size: 14px; color: #616161;">Atau salin dan tempel link berikut ke browser Anda:</p>
            
            <div class="link-container">
                <p class="link-text">{{ $resetUrl }}</p>
            </div>
            
            <div class="warning-box">
                <p><strong>⚠️ Perhatian:</strong> Link ini hanya berlaku selama <strong>1 jam</strong>. Setelah itu, Anda perlu melakukan permintaan reset password yang baru.</p>
            </div>
            
            <div class="info-box">
                <p><strong>ℹ️ Info Keamanan:</strong> Jika Anda tidak melakukan permintaan reset password, abaikan email ini. Akun Anda tetap aman.</p>
            </div>
            
            <div class="divider"></div>
            
            <div class="footer">
                <p>Email ini dikirim ke: <strong>{{ $email }}</strong></p>
                <p style="margin-top: 10px;">© {{ date('Y') }} BMS. All rights reserved.</p>
                <p style="margin-top: 5px; font-size: 11px;">Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            </div>
        </div>
    </div>
</body>
</html>