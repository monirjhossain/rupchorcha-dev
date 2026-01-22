<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Generate invoice from order
     */
    public function generateInvoice(Order $order, $force = false)
    {
        // Check if invoice already exists
        if ($order->invoices()->exists() && !$force) {
            return $order->invoices()->latest()->first();
        }

        $invoiceNumber = $this->generateInvoiceNumber();
        
        // Calculate amounts
        $subtotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        $discount = $order->discount_amount ?? 0;
        $tax = $this->calculateTax($subtotal - $discount);
        $total = $subtotal - $discount + $tax + ($order->shipping_cost ?? 0);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'order_id' => $order->id,
            'amount' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'status' => 'draft',
            'issued_at' => now(),
            'due_at' => now()->addDays(30),
            'meta' => [
                'customer_name' => $order->customer_name ?? $order->user?->name,
                'customer_email' => $order->customer_email ?? $order->user?->email,
                'customer_phone' => $order->customer_phone,
                'shipping_address' => $order->shipping_address,
                'payment_method' => $order->payment_method,
                'items_count' => $order->items->count(),
            ]
        ]);

        return $invoice;
    }

    /**
     * Generate unique invoice number
     */
    public function generateInvoiceNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = 'INV-' . $year . $month;

        // Get the last invoice number for this month
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate tax (default 15%)
     */
    public function calculateTax($amount, $rate = 0.15)
    {
        return round($amount * $rate, 2);
    }

    /**
     * Send invoice email
     */
    public function sendInvoice(Invoice $invoice)
    {
        // Email sending handled in controller/mail class
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->markAsPaid();
        $invoice->order->update(['payment_status' => 'paid']);
    }

    /**
     * Get invoice data for PDF
     */
    public function getInvoiceData(Invoice $invoice)
    {
        $invoice->load('order.user', 'order.items', 'order.courier');
        
        return [
            'invoice' => $invoice,
            'order' => $invoice->order,
            'items' => $invoice->order->items,
            'company' => [
                'name' => config('app.name', 'Rupchorcha'),
                'address' => 'Your Company Address',
                'phone' => '+880 1XXX XXXXXX',
                'email' => 'support@rupchorcha.com',
                'website' => 'www.rupchorcha.com',
                'logo' => asset('path/to/logo.png'),
            ]
        ];
    }
}
