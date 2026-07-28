<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $supplierId = $request->user()->supplier_id;

        $invoices = PurchaseOrder::whereHas('purchaseRequest', function ($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId);
            })
            ->whereNotNull('invoice_number')
            ->latest('updated_at')
            ->get()
            ->map(function ($po) {
                return [
                    'id' => $po->invoice_number,
                    'order_id' => $po->id,
                    'po' => $po->po_number,
                    'amount' => '$' . number_format($po->invoice_amount, 2),
                    'date' => $po->updated_at->format('Y-m-d'),
                    'due' => $po->invoice_due_date ? $po->invoice_due_date->format('Y-m-d') : null,
                    'status' => ucfirst($po->payment_status),
                ];
            });

        return Inertia::render('Supplier/Invoices/Index', [
            'invoices' => $invoices
        ]);
    }

    public function markPaid(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Add basic authorization logic check here if needed
        $purchaseOrder->payment_status = 'paid';
        $purchaseOrder->save();

        return redirect()->back()->with('success', 'Invoice marked as paid.');
    }

    public function downloadPdf(Request $request, PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['purchaseRequest.product', 'purchaseRequest.supplier']);
        
        $data = [
            'invoice_number' => $purchaseOrder->invoice_number,
            'po_number' => $purchaseOrder->po_number,
            'amount' => $purchaseOrder->invoice_amount,
            'due_date' => $purchaseOrder->invoice_due_date ? $purchaseOrder->invoice_due_date->format('Y-m-d') : 'N/A',
            'product' => $purchaseOrder->purchaseRequest->product->name,
            'qty' => $purchaseOrder->quantity_ordered,
            'supplier' => $purchaseOrder->purchaseRequest->supplier->name ?? 'Supplier',
            'date' => $purchaseOrder->updated_at->format('Y-m-d')
        ];

        // Ensure we load the view or generate HTML manually if view doesn't exist
        $html = "
            <h1>Invoice: {$data['invoice_number']}</h1>
            <p><strong>PO Number:</strong> {$data['po_number']}</p>
            <p><strong>Supplier:</strong> {$data['supplier']}</p>
            <p><strong>Date:</strong> {$data['date']}</p>
            <p><strong>Due Date:</strong> {$data['due_date']}</p>
            <hr/>
            <table border='1' cellpadding='10' cellspacing='0' width='100%'>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>{$data['product']}</td>
                    <td>{$data['qty']}</td>
                    <td>\${$data['amount']}</td>
                </tr>
            </table>
        ";

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('invoice_'.$data['invoice_number'].'.pdf');
    }
}
