<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333; 
            background: white;
            line-height: 1.6;
        }
        
        .invoice { 
            max-width: 900px; 
            margin: 0 auto; 
            padding: 40px; 
            background: white;
        }
        
        /* Header Section */
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start;
            margin-bottom: 40px; 
            padding-bottom: 30px; 
            border-bottom: 3px solid #007bff;
        }
        
        .company-info h1 { 
            color: #007bff; 
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .company-info p { 
            font-size: 12px; 
            margin-bottom: 5px; 
            color: #666;
            line-height: 1.8;
        }
        
        .company-info .logo-area {
            margin-bottom: 15px;
        }
        
        .invoice-header { 
            text-align: right;
        }
        
        .invoice-header h2 { 
            color: #007bff; 
            font-size: 28px;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .invoice-header p { 
            font-size: 12px; 
            margin-bottom: 5px; 
            color: #666;
        }
        
        .invoice-header .invoice-number {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin: 10px 0;
        }
        
        .status-badge { 
            display: inline-block; 
            padding: 6px 12px; 
            border-radius: 4px; 
            font-weight: 600; 
            font-size: 11px; 
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-draft { background: #e2e3e5; color: #383d41; }
        .status-unpaid { background: #f8d7da; color: #721c24; }
        .status-sent { background: #d1ecf1; color: #0c5460; }
        .status-paid { background: #d4edda; color: #155724; }
        
        /* Customer Section */
        .customer-section { 
            display: flex; 
            gap: 60px; 
            margin-bottom: 40px;
            padding: 20px 0;
        }
        
        .customer-section div { 
            flex: 1;
        }
        
        .customer-section h4 { 
            color: #007bff; 
            font-size: 12px; 
            margin-bottom: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .customer-section p { 
            font-size: 13px; 
            margin-bottom: 6px; 
            color: #333;
            line-height: 1.8;
        }
        
        .customer-section .label {
            font-weight: 600;
            color: #555;
        }
        
        /* Table Styles */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }
        
        table thead { 
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }
        
        table th { 
            padding: 15px; 
            text-align: left; 
            font-weight: 600; 
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #0056b3;
        }
        
        table td { 
            padding: 12px 15px; 
            border-bottom: 1px solid #dee2e6; 
            font-size: 13px;
        }
        
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        table tbody tr:hover {
            background: #f0f0f0;
        }
        
        .text-right { 
            text-align: right; 
        }
        
        .text-center { 
            text-align: center; 
        }
        
        .item-name {
            font-weight: 500;
            color: #333;
        }
        
        /* Totals Section */
        .totals-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .totals-summary {
            flex: 1;
        }
        
        .totals-box {
            width: 350px;
            margin-left: auto;
        }
        
        .totals-box table {
            margin-bottom: 0;
            border: none;
        }
        
        .totals-box table th {
            background: transparent;
            color: #333;
            padding: 10px 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
            text-transform: none;
            letter-spacing: normal;
        }
        
        .totals-box table td { 
            padding: 10px 15px; 
            border-bottom: 1px solid #dee2e6;
            font-size: 13px;
        }
        
        .totals-row td {
            color: #666;
        }
        
        .totals-row.subtotal td {
            border-bottom: 1px solid #dee2e6;
        }
        
        .totals-row.total {
            background: #007bff;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }
        
        .totals-row.total td {
            padding: 12px 15px;
            border: none;
        }
        
        /* Notes Section */
        .notes-section { 
            background: #f8f9fa; 
            padding: 20px; 
            border-left: 4px solid #007bff; 
            margin-bottom: 30px;
            border-radius: 4px;
        }
        
        .notes-section h5 {
            color: #007bff;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .notes-section p {
            font-size: 12px;
            color: #555;
            line-height: 1.8;
            margin: 0;
        }
        
        /* Footer */
        .footer { 
            text-align: center; 
            font-size: 11px; 
            color: #999; 
            border-top: 1px solid #dee2e6; 
            padding-top: 20px; 
            margin-top: 30px;
            padding-bottom: 20px;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        .divider {
            border: none;
            border-top: 1px solid #dee2e6;
            margin: 30px 0;
        }
        
        .thank-you {
            font-size: 14px;
            color: #007bff;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
        }
        
        @media print {
            body { margin: 0; padding: 0; }
            .invoice { margin: 0; padding: 20px; }
            table { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="logo-area">
                    <h1>🛍️ Rupchorcha</h1>
                </div>
                <p><strong>Rupchorcha Limited</strong></p>
                <p>📍 Your Company Address</p>
                <p>📞 +880 1XXX XXXXXX</p>
                <p>📧 support@rupchorcha.com</p>
                <p>🌐 www.rupchorcha.com</p>
            </div>
            <div class="invoice-header">
                <h2>INVOICE</h2>
                <p class="invoice-number"><strong>{{ $invoice->invoice_number }}</strong></p>
                <p><strong>Order #</strong> {{ $invoice->order->id }}</p>
                <p><strong>Issued:</strong> {{ $invoice->issued_at->format('d M, Y') }}</p>
                <p><strong>Due:</strong> {{ $invoice->due_at?->format('d M, Y') ?? 'Upon Receipt' }}</p>
                <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
            </div>
        </div>

        <!-- Customer Section -->
        <div class="customer-section">
            <div>
                <h4>BILL TO:</h4>
                <p><span class="label">{{ $invoice->meta['customer_name'] ?? 'N/A' }}</span></p>
                <p>{{ $invoice->meta['customer_email'] ?? 'N/A' }}</p>
                <p>📞 {{ $invoice->meta['customer_phone'] ?? 'N/A' }}</p>
                <p style="margin-top: 10px; font-size: 12px; color: #666;">{{ $invoice->meta['shipping_address'] ?? 'N/A' }}</p>
            </div>
            <div>
                <h4>PAYMENT METHOD:</h4>
                <p><span class="label">{{ $invoice->meta['payment_method'] ?? 'Not Specified' }}</span></p>
                <h4 style="margin-top: 20px;">SHIPPING:</h4>
                @if($invoice->order->courier)
                <p><span class="label">{{ $invoice->order->courier->name }}</span></p>
                @if($invoice->order->tracking_number)
                <p>🔗 Tracking: {{ $invoice->order->tracking_number }}</p>
                @endif
                @else
                <p>Not assigned yet</p>
                @endif
            </div>
        </div>

        <hr class="divider">

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">ITEM DESCRIPTION</th>
                    <th style="width: 12%; text-align: center;">QTY</th>
                    <th style="width: 18%; text-align: right;">UNIT PRICE</th>
                    <th style="width: 20%; text-align: right;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->order->items as $item)
                <tr>
                    <td><span class="item-name">{{ $item->product_name }}</span></td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td class="text-right">৳ {{ number_format($item->price, 2) }}</td>
                    <td class="text-right"><strong>৳ {{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-container">
            <div class="totals-summary"></div>
            <div class="totals-box">
                <table>
                    <tr class="totals-row subtotal">
                        <td><strong>Subtotal:</strong></td>
                        <td class="text-right">৳ {{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                    @if($invoice->discount > 0)
                    <tr class="totals-row">
                        <td><strong>Discount:</strong></td>
                        <td class="text-right">- ৳ {{ number_format($invoice->discount, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->order->shipping_cost > 0)
                    <tr class="totals-row">
                        <td><strong>Shipping:</strong></td>
                        <td class="text-right">+ ৳ {{ number_format($invoice->order->shipping_cost, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="totals-row total">
                        <td><strong>TOTAL AMOUNT DUE:</strong></td>
                        <td class="text-right">৳ {{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Thank You -->
        <div class="thank-you">
            🙏 Thank you for your business!
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="notes-section">
            <h5>Special Notes:</h5>
            <p>{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Payment Terms:</strong> Please make payment within 30 days of invoice date.</p>
            <p style="margin-top: 10px;">If you have any questions, please contact us at support@rupchorcha.com or +880 1XXX XXXXXX</p>
            <p style="margin-top: 15px; color: #ccc;">—— This is a computer-generated invoice. No signature is required. ——</p>
        </div>
    </div>
</body>
</html>
