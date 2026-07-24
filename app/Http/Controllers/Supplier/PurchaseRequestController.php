<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the PRs for the logged-in supplier.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();
        
        // Auto-link to an active factory if not linked yet
        if (!$user->supplier_id) {
            $supplier = Supplier::first();
            if (!$supplier) {
                $supplier = Supplier::create([
                    'name'            => 'SKIMS Beauty Factory',
                    'contact_name'    => $user->name,
                    'contact_email'   => $user->email,
                    'source_platform' => 'local_factory',
                    'lead_time_days'  => 14,
                    'is_active'       => true,
                ]);
            }
            $user->supplier_id = $supplier->id;
            $user->save();
        }

        $prs = PurchaseRequest::with(['product', 'requester', 'approver', 'purchaseOrder'])
            ->where('supplier_id', $user->supplier_id)
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $prs->getCollection()->transform(fn ($pr) => [
            'id'                    => $pr->id,
            'product'               => ['id' => $pr->product->id, 'name' => $pr->product->name, 'sku' => $pr->product->sku],
            'quantity_requested'    => $pr->quantity_requested,
            'unit_cost'             => $pr->unit_cost,
            'total_cost'            => $pr->unit_cost * $pr->quantity_requested,
            'expected_delivery_date' => $pr->expected_delivery_date?->toDateString(),
            'status'                => $pr->status,
            'status_label'          => $pr->status_label,
            'status_color'          => $pr->status_color,
            'notes'                 => $pr->notes,
            'requester'             => $pr->requester->name,
            'po_number'             => $pr->purchaseOrder?->po_number,
            'created_at'            => $pr->created_at->toDateString(),
        ]);

        return Inertia::render('Supplier/PurchaseRequests/Index', [
            'purchaseRequests' => $prs,
            'filters'          => $request->only(['status', 'search']),
        ]);
    }
}
