<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AL Management</h1>
        </div>

        <div class="content">
            <p>Halo {{ $userName }},</p>
            
            <p>Terima kasih telah menyewa di AL Management. Pesanan Anda telah disetujui.</p>
            
            <p>Kami telah melampirkan nota penyewaan dalam format PDF di email ini.</p>
            
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
        </div>

        <div class="footer">
            <p>Hormat kami,</p>
            <p>Tim AL Management</p>
        </div>
    </div>
</body>
</html>