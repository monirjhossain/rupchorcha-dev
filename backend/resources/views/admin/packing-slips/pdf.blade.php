<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Packing Slip {{ $packingSlip->slip_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #1a1a1a;
            background: #fff;
            line-height: 1.5;
        }

        .slip {
            max-width: 960px;
            margin: 0 auto;
            padding: 35px;
            background: #fff;
        }

        /* Top Banner */
        .top-banner {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
            padding: 25px 30px;
            border-radius: 8px 8px 0 0;
            margin: -35px -35px 0 -35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .banner-left h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .banner-left p {
            font-size: 13px;
            opacity: 0.9;
        }

        .banner-right {
            text-align: right;
            font-size: 12px;
        }

        .banner-right .slip-number {
            font-size: 20px;
            font-weight: 700;
            color: #4db8ff;
            margin-bottom: 8px;
        }

        .banner-right p {
            margin-bottom: 4px;
            opacity: 0.9;
        }

        /* Main Content */
        .content {
            padding: 30px;
            background: #f9fafb;
        }

        /* Two Column Layout */
        .layout-two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        /* Shipping Address Card */
        .address-card {
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            border-left: 4px solid #2a5298;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .address-card h3 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #2a5298;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }

        .address-card .address {
            font-size: 13px;
            line-height: 1.8;
            color: #1a1a1a;
        }

        .address-line {
            margin-bottom: 6px;
        }

        .address-line strong {
            color: #2a5298;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .info-item {
            font-size: 12px;
            margin-top: 16px;
        }

        .info-item label {
            color: #2a5298;
            font-weight: 700;
            text-transform: uppercase;
            display: block;
            margin-bottom: 4px;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        .info-item p {
            color: #333;
            font-size: 13px;
        }

        /* Order Stats Card */
        .stats-card {
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            border-top: 3px solid #2a5298;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .stats-card h3 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #2a5298;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 12px;
            background: #f0f4f8;
            border-radius: 4px;
            border-left: 3px solid #2a5298;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #2a5298;
            margin-bottom: 6px;
            letter-spacing: 0.4px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        /* Items Table */
        .items-section {
            margin-top: 30px;
        }

        .items-section h2 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1a1a1a;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .items-table thead {
            background: #2a5298;
            color: #fff;
        }

        .items-table th {
            padding: 14px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .items-table th:nth-child(2),
        .items-table th:nth-child(3) {
            text-align: center;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tbody tr:last-child {
            border-bottom: none;
        }

        .items-table tbody tr:nth-child(odd) {
            background: #f9fafb;
        }

        .items-table td {
            padding: 14px 16px;
            font-size: 13px;
            color: #333;
        }

        .items-table td:nth-child(2),
        .items-table td:nth-child(3) {
            text-align: center;
            font-weight: 600;
        }

        .checkbox-col {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid #2a5298;
            border-radius: 3px;
            display: inline-block;
        }

        /* Notes Section */
        .notes-section {
            margin-top: 25px;
            background: #fff3cd;
            border-left: 4px solid #ff9800;
            padding: 16px 20px;
            border-radius: 4px;
        }

        .notes-section h4 {
            font-size: 12px;
            font-weight: 700;
            color: #ff6b35;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.4px;
        }

        .notes-section p {
            font-size: 12px;
            color: #654321;
            line-height: 1.6;
            margin: 0;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #666;
        }

        .footer-left p {
            margin-bottom: 4px;
        }

        .footer-barcode {
            text-align: right;
        }

        .barcode-number {
            font-size: 16px;
            font-weight: 700;
            color: #2a5298;
            margin-top: 8px;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }
            .slip {
                margin: 0;
                padding: 20px;
                box-shadow: none;
            }
            .top-banner {
                margin: -20px -20px 0 -20px;
                border-radius: 0;
            }
            .checkbox {
                cursor: pointer;
            }
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="slip">
        <!-- Top Banner -->
        <div class="top-banner">
            <div class="banner-left">
                <h1>RUPCHORCHA</h1>
                <p>Professional Packing Slip</p>
            </div>
            <div class="banner-right">
                <div class="slip-number">{{ $packingSlip->slip_number }}</div>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p>{{ $packingSlip->generated_at->format('d M, Y') }}</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Two Column: Address & Stats -->
            <div class="layout-two-col">
                <!-- Shipping Address -->
                <div class="address-card">
                    <h3>🚚 Ship To</h3>
                    <div class="address">
                        <div class="address-line">
                            <strong>{{ $packingSlip->meta['customer_name'] ?? $order->customer_name ?? 'N/A' }}</strong>
                        </div>
                        <div class="address-line">
                            📞 {{ $packingSlip->meta['customer_phone'] ?? $order->customer_phone ?? 'N/A' }}
                        </div>
                        <div class="address-line">
                            📧 {{ $packingSlip->meta['customer_email'] ?? $order->customer_email ?? 'N/A' }}
                        </div>
                        <div class="address-line" style="margin-top: 10px; color: #555;">
                            {{ $packingSlip->meta['shipping_address'] ?? $order->shipping_address ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Courier Service</label>
                            <p>{{ $order->courier?->name ?? 'Not Assigned' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Tracking Number</label>
                            <p>{{ $order->tracking_number ?? 'Pending' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Stats -->
                <div class="stats-card">
                    <h3>📊 Order Summary</h3>
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-label">Order Date</div>
                            <div class="stat-value">{{ $order->created_at->format('d M') }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Total Items</div>
                            <div class="stat-value">{{ $packingSlip->meta['item_count'] ?? 0 }}</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Total Qty</div>
                            <div class="stat-value">{{ $packingSlip->meta['total_quantity'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items to Pack -->
            <div class="items-section">
                <h2>✓ Items to Pack</h2>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Check</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="checkbox-col">
                                <div class="checkbox"></div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #999;">No items found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Special Notes -->
            @if($packingSlip->meta['notes'] ?? false)
            <div class="notes-section">
                <h4>⚠️ Special Instructions</h4>
                <p>{{ $packingSlip->meta['notes'] }}</p>
            </div>
            @endif

            <!-- Footer -->
            <div class="footer">
                <div class="footer-left">
                    <p>✓ Verify all items before shipment</p>
                    <p>✓ Contact warehouse manager if items are damaged</p>
                </div>
                <div class="footer-barcode">
                    <p style="color: #999;">Slip Reference</p>
                    <div class="barcode-number">{{ $packingSlip->slip_number }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
