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
        
        // Ensure the supplier user is properly linked to a factory
        if (!$user->supplier_id) {
            return Inertia::render('Supplier/PurchaseRequests/Index', [
                'purchaseRequests' => ['data' => [], 'links' => [], 'meta' => []],
                'filters'          => $request->only(['status', 'search']),
                'error'            => 'Your account is not linked to a supplier factory. Please contact an administrator.',
            ]);
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

    /**
     * Factory approves the PR and provides ETA.
     */
    public function approve(Request $request, PurchaseRequest $purchaseRequest, \App\Actions\PurchaseRequest\FactoryApprovePurchaseRequest $action)
    {
        $user = $request->user();

        if ($purchaseRequest->supplier_id !== $user->supplier_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($purchaseRequest->status !== 'pending_factory_approval') {
            return back()->with('error', 'This PR is not pending factory approval.');
        }

        $validated = $request->validate([
            'expected_delivery_date' => 'required|date|after_or_equal:today',
        ]);

        $action->handle($purchaseRequest, $validated['expected_delivery_date']);

        return back()->with('success', 'Purchase Request approved. Purchase Order has been generated.');
    }
}
