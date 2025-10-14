<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Gagal - BMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #FF0000 0%, #cc0000 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            background-color: #FFFFFF;
            padding: 50px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(29, 22, 22, 0.16);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .icon-wrapper {
            width: 100px;
            height: 100px;
            background-color: #FFE6E6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }
        
        .icon {
            font-size: 50px;
            color: #FF0000;
        }
        
        h2 {
            color: #FF0000;
            margin-bottom: 15px;
            font-size: 28px;
        }
        
        .message {
            color: #1D1616;
            line-height: 1.7;
            margin-bottom: 15px;
            font-size: 15px;
        }
        
        .info-box {
            background-color: #E8F4F8;
            border-left: 4px solid #3674B5;
            padding: 16px;
            margin: 25px 0;
            border-radius: 6px;
            text-align: left;
        }
        
        .info-box p {
            color: #1D1616;
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .info-box strong {
            color: #3674B5;
        }
        
        .reasons {
            background-color: #EDEDED;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: left;
        }
        
        .reasons-title {
            color: #1D1616;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .reason-item {
            color: #616161;
            margin: 8px 0;
            padding-left: 22px;
            position: relative;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .reason-item:before {
            content: "✕";
            position: absolute;
            left: 0;
            color: #FF0000;
            font-weight: bold;
        }
        
        .button {
            display: inline-block;
            padding: 14px 30px;
            background-color: #0156BF;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        .button:hover {
            background-color: #014a9d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(1, 86, 191, 0.3);
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E9E9E9;
            color: #616161;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrapper">
            <div class="icon">✕</div>
        </div>
        
        <h2>Reset Password Gagal</h2>
        
        <p class="message">{{ $message }}</p>
        
        <div class="reasons">
            <div class="reasons-title">Kemungkinan Penyebab:</div>
            <div class="reason-item">Link sudah kadaluarsa (lebih dari 1 jam)</div>
            <div class="reason-item">Link sudah pernah digunakan</div>
            <div class="reason-item">Link tidak valid atau rusak</div>
        </div>
        
        <div class="info-box">
            <p><strong>ℹ️ Solusi:</strong> Silakan lakukan permintaan reset password yang baru melalui aplikasi BMS. Link baru akan dikirim ke email Anda dan berlaku selama 1 jam.</p>
        </div>
        
        <a href="/" class="button">Kembali ke Halaman Utama</a>
        
        <div class="footer">
            <p>© {{ date('Y') }} BMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>