<!DOCTYPE html>
<html>
<head>
    <title>Invoice INV-{{ $transaction->created_at->format('Ymd') }}-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 10px; text-align: left; }
        .table th { background-color: #f4f4f4; font-weight: bold; }
        .table tfoot td { font-weight: bold; }
        .logo { max-height: 60px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="WarungKu Logo" class="logo" onerror="this.style.display='none'">
        <h1>Invoice INV-{{ $transaction->created_at->format('Ymd') }}-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</h1>
        <p>Tanggal: {{ $transaction->created_at->format('d/m/Y') }}</p>
        <p>Status: {{ match ($transaction->status ?? 'unknown') {
            'draft' => 'Draft',
            'checked_out' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        } }}</p>
    </div>

    @php
        $totalHarga = 0;
    @endphp

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->transactionItems as $item)
                @php
                    $subtotal = ($item->price ?? 0) * ($item->quantity ?? 0);
                    $totalHarga += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item->product ? $item->product->name : 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $item->quantity ?? 0 }}</td>
                    <td>Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
