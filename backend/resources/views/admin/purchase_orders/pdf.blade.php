
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Nunito', Arial, sans-serif;
            background: #f4f6fb;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .po-container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 40px 40px 30px 40px;
            border: 1px solid #e3e6f0;
        }
        .po-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #17a2b8;
            padding-bottom: 18px;
        }
        .po-title {
            font-size: 2.2rem;
            color: #17a2b8;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .po-meta {
            color: #888;
            font-size: 1.1rem;
        }
        .po-logo img {
            height: 60px;
        }
        .po-info {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .po-info-box {
            font-size: 1rem;
            line-height: 1.7;
        }
        .po-info-box strong {
            color: #17a2b8;
        }
        .po-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 1rem;
        }
        .po-table th, .po-table td {
            border: 1px solid #e3e6f0;
            padding: 10px 8px;
        }
        .po-table th {
            background: #eaf6fa;
            color: #17a2b8;
            font-weight: 700;
            text-align: left;
        }
        .po-table td {
            background: #fff;
        }
        .po-table tfoot td {
            font-weight: 700;
            background: #f8f9fa;
        }
        .po-notes {
            margin-top: 30px;
            font-size: 1rem;
            color: #666;
            background: #f8f9fa;
            border-left: 4px solid #17a2b8;
            padding: 12px 18px;
            border-radius: 6px;
        }
        .po-footer {
            margin-top: 40px;
            font-size: 0.95rem;
            color: #aaa;
            text-align: center;
        }
        .po-signature {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .po-signature-box {
            text-align: right;
        }
        .po-signature-line {
            border-top: 1.5px solid #17a2b8;
            width: 180px;
            margin-bottom: 4px;
        }
        .po-signature-label {
            color: #888;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
<div class="po-container">
    <div class="po-header">
        <div>
            <div class="po-title">PURCHASE ORDER</div>
            <div class="po-meta">#{{ $order->id }}</div>
        </div>
        <div class="po-logo">
            <img src="{{ asset('logo.png') }}" alt="Logo">
        </div>
    </div>
    <div class="po-info">
        <div class="po-info-box">
            <strong>Supplier</strong><br>
            {{ $order->supplier->name }}<br>
            {{ $order->supplier->address }}<br>
            {{ $order->supplier->phone }}<br>
            {{ $order->supplier->email }}
        </div>
        <div class="po-info-box" style="text-align:right;">
            <strong>Date:</strong> {{ $order->order_date }}<br>
            <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
            <strong>Warehouse:</strong> {{ $order->warehouse->name ?? '-' }}
        </div>
    </div>
    <table class="po-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->product->sku ?? '-' }}</td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td style="text-align:right;">&#2547;{{ number_format($item->unit_price,2) }}</td>
                <td style="text-align:right;">&#2547;{{ number_format($item->total,2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total</td>
                <td style="text-align:right;">&#2547;{{ number_format($order->total,2) }}</td>
            </tr>
        </tfoot>
    </table>
    @if($order->notes)
    <div class="po-notes">
        <strong>Notes:</strong><br>
        {{ $order->notes }}
    </div>
    @endif
    <div class="po-signature">
        <div class="po-signature-box">
            <div class="po-signature-line"></div>
            <div class="po-signature-label">Authorized Signature</div>
        </div>
    </div>
    <div class="po-footer">
        Generated by {{ config('app.name') }} on {{ now()->format('Y-m-d H:i') }}
    </div>
</div>
</body>
</html>
