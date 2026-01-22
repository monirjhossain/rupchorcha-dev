<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PackingSlip;
use PDF;

class PackingSlipController extends Controller
{
    public function download($orderId)
    {
        $order = Order::with(['items', 'user', 'courier'])->findOrFail($orderId);
        
        // Get or create packing slip
        $packingSlip = $order->packingSlips()->first() 
            ?? PackingSlip::generateFromOrder($order);
        
        // Mark as printed
        $packingSlip->markAsPrinted();
        
        // Generate PDF
        $pdf = PDF::loadView('admin.packing-slips.pdf', [
            'packingSlip' => $packingSlip,
            'order' => $order,
        ]);
        
        return $pdf->download('packing-slip-' . $packingSlip->slip_number . '.pdf');
    }

    public function generate($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Check if already exists
        if ($order->packingSlips()->exists()) {
            return back()->with('error', 'Packing slip already generated for this order.');
        }
        
        PackingSlip::generateFromOrder($order);
        
        return back()->with('success', 'Packing slip generated successfully!');
    }

    public function show(PackingSlip $packingSlip)
    {
        $packingSlip->load('order.items', 'order.user', 'order.courier');
        
        return view('admin.packing-slips.show', [
            'packingSlip' => $packingSlip,
            'order' => $packingSlip->order,
        ]);
    }

    public function preview($orderId)
    {
        $order = Order::with(['items', 'user', 'courier'])->findOrFail($orderId);
        
        // Get or create packing slip for preview
        $packingSlip = $order->packingSlips()->first() 
            ?? PackingSlip::generateFromOrder($order);
        
        return view('admin.packing-slips.show', [
            'packingSlip' => $packingSlip,
            'order' => $order,
        ]);
    }
}
