<div>
    <div class="p-4 bg-white rounded-lg shadow">
        <h2 class="text-lg font-semibold">Riwayat Transaksi</h2>
        @if ($transactions->isEmpty())
            <p class="mt-2 text-gray-500"></p>
        @else
            <table class="w-full mt-4 border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left border">ID</th>
                        <th class="p-2 text-left border">Status</th>
                        <th class="p-2 text-left border">Total Harga</th>
                        <th class="p-2 text-left border">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="p-2 border">{{ $transaction->id }}</td>
                            <td class="p-2 border">Selesai</td>
                            <td class="p-2 border">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td class="p-2 border">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
