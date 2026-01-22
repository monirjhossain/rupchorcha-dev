<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #e91e63;
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .order-number {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 10px;
        }
        .info-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-label {
            font-weight: 600;
            color: #555;
        }
        .info-value {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f0f0f0;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
            color: #333;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        .quantity {
            text-align: center;
        }
        .price {
            text-align: right;
            font-weight: 600;
        }
        .subtotal-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .subtotal-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .subtotal-row.total {
            font-size: 18px;
            font-weight: 700;
            color: #e91e63;
            border-top: 2px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }
        .shipping-info {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .shipping-info h3 {
            font-size: 14px;
            color: #2e7d32;
            margin-bottom: 10px;
        }
        .address-block {
            margin-bottom: 20px;
        }
        .address-block h3 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .address-block p {
            font-size: 13px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 3px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #999;
        }
        .button {
            display: inline-block;
            background-color: #e91e63;
            color: #fff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 600;
        }
        .button:hover {
            background-color: #c2185b;
        }
        .status-badge {
            display: inline-block;
            background-color: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✓ Order Confirmed!</h1>
            <p class="order-number">Order #{{ $order->id }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Thank You Message -->
            <div class="section">
                <p>Dear <strong>{{ $order->customer_name }}</strong>,</p>
                <p style="margin-top: 10px; font-size: 14px; color: #666;">
                    Thank you for your order! We've received your order and are preparing it for shipment.
                </p>
            </div>

            <!-- Order Details -->
            <div class="section">
                <h2>Order Details</h2>
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Order Number:</span>
                        <span class="info-value">#{{ $order->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Order Date:</span>
                        <span class="info-value">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge">{{ ucfirst($order->status) }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="section">
                <h2>Order Items</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="quantity">Qty</th>
                            <th class="price">Unit Price</th>
                            <th class="price">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name ?? 'Product' }}</strong>
                                    @if($item->product && $item->product->sku)
                                        <br><small style="color: #999;">SKU: {{ $item->product->sku }}</small>
                                    @endif
                                </td>
                                <td class="quantity">{{ $item->quantity }}</td>
                                <td class="price">৳{{ number_format($item->price, 2) }}</td>
                                <td class="price">৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="subtotal-section">
                    <div class="subtotal-row">
                        <span>Subtotal:</span>
                        <span>৳{{ number_format($order->items()->sum(\DB::raw('price * quantity')), 2) }}</span>
                    </div>
                    <div class="subtotal-row">
                        <span>Shipping Cost:</span>
                        <span>৳{{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="subtotal-row" style="color: #27ae60;">
                            <span>Discount:</span>
                            <span>-৳{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="subtotal-row total">
                        <span>Total Amount:</span>
                        <span>৳{{ number_format($order->total ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="section">
                <h2>Shipping Information</h2>
                <div class="shipping-info">
                    <h3>📦 Estimated Delivery</h3>
                    <p>Your order will be delivered within 3-5 business days.</p>
                </div>
                <div class="address-block">
                    <h3>📍 Shipping Address</h3>
                    <p><strong>{{ $order->customer_name }}</strong></p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->area }}, {{ $order->city }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="section">
                <h2>Contact Information</h2>
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $order->customer_email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $order->customer_phone }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
                <div class="section">
                    <h2>Order Notes</h2>
                    <div class="info-box">
                        <p style="font-size: 14px; color: #666;">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ config('app.frontend_url') }}/order-success?orderId={{ $order->id }}" class="button">
                    View Order Status
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="margin-top: 10px;">
                If you have any questions, please contact us at {{ config('mail.from.address') }}
            </p>
        </div>
    </div>
</body>
</html>
