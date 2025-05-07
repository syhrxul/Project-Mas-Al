<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan Penyewaan - {{ $month }}</title>
    <style>
        body { 
            font-family: sans-serif; 
            margin: 40px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        .table th { 
            background-color: #f5f5f5; 
        }
        .total { 
            font-weight: bold; 
            margin-top: 20px; 
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('storage/logo.png') }}" class="logo" alt="AL Management Logo">
        <h2>Laporan Bulanan Penyewaan</h2>
        <h3>{{ $month }}</h3>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Disewa</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentals->groupBy('product_id') as $productId => $groupedRentals)
                <tr>
                    <td>{{ $groupedRentals->first()->product->name }}</td>
                    <td>{{ $groupedRentals->sum('quantity') }} unit</td>
                    <td>Rp {{ number_format($groupedRentals->sum('total_price'), 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Pendapatan: Rp {{ number_format($totalIncome, 0, ',', '.') }}
    </div>
</body>
</html>