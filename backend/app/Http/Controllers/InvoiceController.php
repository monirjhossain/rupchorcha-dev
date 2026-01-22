<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
use PDF;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of invoices.
     */
    public function index()
    {
        $query = Invoice::with('order.user');

        // Search by invoice number
        if ($search = request('search')) {
            $query->where('invoice_number', 'like', "%$search%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('id', $search);
                  });
        }

        // Filter by status
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        // Filter by date range
        if ($date_from = request('date_from')) {
            $query->whereDate('issued_at', '>=', $date_from);
        }
        if ($date_to = request('date_to')) {
            $query->whereDate('issued_at', '<=', $date_to);
        }

        $invoices = $query->latest()->paginate(20)->appends(request()->query());

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the invoice details.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('order.user', 'order.items');
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Download invoice as PDF.
     */
    public function download(Invoice $invoice)
    {
        $invoice->load('order.user', 'order.items');
        
        $pdf = PDF::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Send invoice via email.
     */
    public function send(Invoice $invoice)
    {
        try {
            $invoice->load('order.user');
            
            Mail::to($invoice->order->customer_email)
                ->send(new InvoiceMail($invoice));

            $invoice->markAsSent();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice sent to ' . $invoice->order->customer_email);
        } catch (\Exception $e) {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Failed to send invoice: ' . $e->getMessage());
        }
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->markAsPaid();
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice marked as paid.');
    }

    /**
     * Mark invoice as unpaid.
     */
    public function markAsUnpaid(Invoice $invoice)
    {
        $invoice->markAsUnpaid();
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice marked as unpaid.');
    }

    /**
     * Create invoice from order.
     */
    public function createFromOrder(Order $order)
    {
        try {
            $invoice = $this->invoiceService->generateInvoice($order);
            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Failed to create invoice: ' . $e->getMessage());
        }
    }

    /**
     * Delete invoice.
     */
    public function destroy(Invoice $invoice)
    {
        $order_id = $invoice->order_id;
        $invoice->delete();
        
        return redirect()->route('orders.show', $order_id)
            ->with('success', 'Invoice deleted successfully.');
    }
}
