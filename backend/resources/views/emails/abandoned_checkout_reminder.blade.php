<html>
<body>
    <h2>Don't forget your cart at Rupchorcha!</h2>
    <p>Hi{{ $checkout->user ? ' ' . $checkout->user->name : '' }},</p>
    <p>You left the following items in your cart:</p>
    <ul>
        @foreach(($checkout->cart_data['items'] ?? []) as $item)
            <li>{{ $item['product_name'] }} (x{{ $item['quantity'] }})</li>
        @endforeach
    </ul>
    <p><a href="{{ url('/cart') }}">Return to your cart and complete your purchase</a></p>
    <p>If you have any questions, reply to this email or contact our support team.</p>
    <p>Thank you,<br>Rupchorcha Team</p>
</body>
</html>
