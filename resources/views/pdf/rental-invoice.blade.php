<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nota Penyewaan - AL Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        .table th {
            background-color: #f5f5f5;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('storage/logo.png') }}" class="logo" alt="AL Management Logo">
        <h1>NOTA PENYEWAAN</h1>
        <h2>AL Management</h2>
    </div>

    <div class="invoice-details">
        <p><strong>Tanggal:</strong> {{ $date }}</p>
        <p><strong>No. Invoice:</strong> INV-{{ $rental->id }}</p>
        <p><strong>Customer:</strong> {{ $rental->user->name }}</p>
        <p><strong>WhatsApp:</strong> {{ $rental->whatsapp_number }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Durasi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $product->title }}</td>
                <td>{{ $rental->quantity }} unit</td>
                <td>{{ $start_date }} - {{ $end_date }}</td>
                <td>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total Pembayaran: Rp {{ number_format($rental->total_price, 0, ',', '.') }}
    </div>

    <div class="footer">
        <p>Terima kasih telah menyewa di AL Management!</p>
        <p>Untuk informasi lebih lanjut, silakan hubungi kami melalui WhatsApp.</p>
    </div>
</body>
</html>