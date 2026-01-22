<x-mail::message>
# Invoice {{ $invoice->invoice_number }}

Dear {{ $invoice->meta['customer_name'] ?? 'Valued Customer' }},

Thank you for your order! Your invoice is ready. Please find the details below:

**Invoice Details:**
- Invoice #: {{ $invoice->invoice_number }}
- Order #: #{{ $order->id }}
- Invoice Date: {{ $invoice->issued_at->format('M d, Y') }}
- Due Date: {{ $invoice->due_at?->format('M d, Y') ?? 'Upon Receipt' }}
- Total Amount: ৳ {{ number_format($invoice->total, 2) }}

**Items:**

| Product | Qty | Price | Amount |
|---------|-----|-------|--------|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ৳ {{ number_format($item->price, 2) }} | ৳ {{ number_format($item->price * $item->quantity, 2) }} |
@endforeach

**Summary:**
- Subtotal: ৳ {{ number_format($invoice->amount, 2) }}
@if($invoice->discount > 0)
- Discount: - ৳ {{ number_format($invoice->discount, 2) }}
@endif
@if($invoice->tax > 0)
- Tax: + ৳ {{ number_format($invoice->tax, 2) }}
@endif
- **Total: ৳ {{ number_format($invoice->total, 2) }}**

<x-mail::button :url="url('/invoices/' . $invoice->id)">
View Invoice Online
</x-mail::button>

Thank you for your business!

Best regards,  
**Rupchorcha Team**
</x-mail::message>
