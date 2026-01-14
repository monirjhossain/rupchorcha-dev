use Barryvdh\DomPDF\Facade\Pdf;
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhereHas('supplier', function($s) use ($q) {
                        $s->where('name', 'like', "%$q%");
                    });
            });
        }
        $orders = $query->latest()->paginate(20)->appends($request->except('page'));
        $suppliers = Supplier::all();
        return view('admin.purchase_orders.index', compact('orders', 'suppliers'));
    }
    

    public function create()
    {
        $suppliers = Supplier::all();
        $products = \App\Models\Product::all();
        return view('admin.purchase_orders.create', compact('suppliers', 'products'));
    }

        public function receiveGoods(Request $request, $id)
    {
        $order = PurchaseOrder::with('items')->findOrFail($id);
        $receivedQuantities = $request->input('received_quantity', []);
        $receivedDates = $request->input('received_date', []);
        $userId = auth()->id();
        foreach ($order->items as $item) {
            $qty = isset($receivedQuantities[$item->id]) ? (int)$receivedQuantities[$item->id] : $item->received_quantity;
            $date = isset($receivedDates[$item->id]) ? $receivedDates[$item->id] : $item->received_date;
            $item->received_quantity = $qty;
            $item->received_date = $date;
            $item->received_by = $userId;
            $item->save();
        }
        // Optionally update PO status if all items received
        if ($order->items->every(fn($i) => $i->received_quantity >= $i->quantity)) {
            $order->status = 'received';
            $order->save();
        } elseif ($order->items->some(fn($i) => $i->received_quantity > 0)) {
            $order->status = 'partially_received';
            $order->save();
        }
        return redirect()->back()->with('success', 'Goods received info updated!');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
        ]);

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('purchase_orders', 'public');
        } else {
            $data['attachment'] = null;
        }

        $data['total'] = 0;
        $order = PurchaseOrder::create([
            'supplier_id' => $data['supplier_id'],
            'order_date' => $data['order_date'],
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
            'total' => 0,
            // Optionally save attachment path if you add a column
        ]);

        $total = 0;
        foreach ($data['product_id'] as $i => $productId) {
            $qty = $data['quantity'][$i];
            $unitPrice = $data['unit_price'][$i];
            $subtotal = $qty * $unitPrice;
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total' => $subtotal,
            ]);
            $total += $subtotal;
        }
        $order->update(['total' => $total]);

        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order created!');
    }
      public function updateStatus(Request $request, $id)
    {
        $order = PurchaseOrder::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->input('status');
        $order->status = $newStatus;
        $order->save();

        // Log status change in history
        \App\Models\PurchaseOrderStatusHistory::create([
            'purchase_order_id' => $order->id,
            'status' => $newStatus,
            'user_id' => auth()->id(),
            'notes' => 'Status changed from ' . $oldStatus . ' to ' . $newStatus,
        ]);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }
        public function show($id)
    {
        $order = \App\Models\PurchaseOrder::with(['items.product', 'supplier', 'warehouse'])->findOrFail($id);
        return view('admin.purchase_orders.pdf', compact('order'));
    }
    public function showDetails($id)
    {
        $order = \App\Models\PurchaseOrder::with([
            'items.product', 'supplier', 'warehouse',
            'created_by', 'updated_by', 'approved_by', 'received_by', 'cancelled_by',
            'status_history.user'
        ])->findOrFail($id);
        return view('admin.purchase_orders.show', compact('order'));
    }

        public function edit($id)
    {
        $order = PurchaseOrder::with('items')->findOrFail($id);
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchase_orders.edit', compact('order', 'suppliers', 'products'));
    }
     public function pdf($id)
    {
        $order = \App\Models\PurchaseOrder::with(['items.product', 'supplier'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.purchase_orders.pdf', compact('order'));
        $filename = 'PurchaseOrder-'.$order->id.'.pdf';
        return $pdf->download($filename);
    }

    public function update(Request $request, $id)
    {
        $order = \App\Models\PurchaseOrder::with('items')->findOrFail($id);
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('purchase_orders', 'public');
        }
        $order->update([
            'supplier_id' => $data['supplier_id'],
            'order_date' => $data['order_date'],
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
            'attachment' => $data['attachment'] ?? $order->attachment,
        ]);
        // Remove old items
        $order->items()->delete();
        $total = 0;
        foreach ($data['product_id'] as $i => $productId) {
            $qty = $data['quantity'][$i];
            $unitPrice = $data['unit_price'][$i];
            $subtotal = $qty * $unitPrice;
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total' => $subtotal,
            ]);
            $total += $subtotal;
        }
        $order->update(['total' => $total]);
        return redirect()->route('purchase_orders.index')->with('success', 'Purchase order updated!');
    }
}
