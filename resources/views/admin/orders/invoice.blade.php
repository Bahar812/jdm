<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            font-size: 12px;
            margin: 24px;
        }

        .row {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 4px;
        }

        .muted {
            color: #475569;
            margin: 2px 0;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: #dcfce7;
            color: #166534;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        table.items thead th {
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #475569;
            padding: 8px 6px;
        }

        table.items tbody td {
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 6px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 320px;
            margin-left: auto;
            margin-top: 16px;
            border-collapse: collapse;
        }

        .summary td {
            padding: 6px 8px;
        }

        .summary .total td {
            border-top: 1px solid #cbd5e1;
            font-weight: 700;
            font-size: 14px;
        }

        .footer {
            margin-top: 24px;
            font-size: 11px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <table class="row header-table">
        <tr>
            <td width="62%">
                <p class="title">INVOICE</p>
                <p class="muted"><strong>No Invoice:</strong> {{ $order->order_number }}</p>
                <p class="muted"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <p class="muted"><strong>Status Bayar:</strong> <span class="badge">{{ \App\Models\Order::paymentStatusLabel($order->payment_status) }}</span></p>
            </td>
            <td width="38%">
                <p class="muted"><strong>CV. Juragan Daging Morowali</strong></p>
                <p class="muted">General Supplier Frozen Food</p>
                <p class="muted">Sulawesi Tengah</p>
            </td>
        </tr>
    </table>

    <table class="row">
        <tr>
            <td width="50%">
                <p class="muted"><strong>Ditagihkan Kepada:</strong></p>
                <p class="muted">{{ $order->customer_name }}</p>
                <p class="muted">{{ $order->customer_phone }}</p>
                <p class="muted">{{ $order->customer_email ?: '-' }}</p>
            </td>
            <td width="50%">
                <p class="muted"><strong>Alamat Pengiriman:</strong></p>
                <p class="muted">{{ $order->shipping_address }}</p>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="43%">Produk</th>
                <th width="17%">Harga</th>
                <th width="10%">Qty</th>
                <th width="25%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr class="total">
            <td>Total</td>
            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Metode Bayar</td>
            <td class="text-right">{{ strtoupper($order->payment_method ?? 'MIDTRANS') }}</td>
        </tr>
        <tr>
            <td>Paid At</td>
            <td class="text-right">{{ $order->paid_at?->format('d M Y H:i') ?? '-' }}</td>
        </tr>
    </table>

    <p class="footer">
        Dokumen ini dihasilkan otomatis dari sistem admin. Terima kasih atas kepercayaan Anda.
    </p>
</body>
</html>
