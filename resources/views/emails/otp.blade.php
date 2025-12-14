<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - CineWave</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #E50914;
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content p {
            color: #666666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .otp-box {
            background-color: #f8f8f8;
            border: 2px dashed #E50914;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 48px;
            font-weight: bold;
            color: #E50914;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .otp-label {
            font-size: 14px;
            color: #888888;
            margin-bottom: 10px;
        }
        .footer {
            background-color: #141414;
            color: #888888;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
        .footer a {
            color: #E50914;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CINEWAVE</h1>
        </div>
        
        <div class="content">
            <h2>Halo, {{ $name }}!</h2>
            <p>Terima kasih telah mendaftar di CineWave. Untuk melanjutkan proses verifikasi email Anda, silakan gunakan kode OTP berikut:</p>
            
            <div class="otp-box">
                <div class="otp-label">Kode Verifikasi Anda</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-label">Kode ini akan kedaluwarsa dalam 10 menit</div>
            </div>
            
            <p>Jika Anda tidak merasa mendaftar akun CineWave, abaikan email ini.</p>
            
            <p>Salam,<br>Tim CineWave</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2024 CineWave. Semua hak dilindungi.</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
