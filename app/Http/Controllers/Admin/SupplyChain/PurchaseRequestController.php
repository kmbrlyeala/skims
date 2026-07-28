<?php

namespace App\Http\Controllers\Admin\SupplyChain;

use App\Http\Controllers\Controller;
use App\Actions\PurchaseRequest\ApprovePurchaseRequest;
use App\Models\Product;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseRequestController extends Controller
{
    public function index(Request $request): Response
    {
        $prs = PurchaseRequest::with(['product', 'supplier', 'requester', 'approver', 'purchaseOrder'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $prs->getCollection()->transform(fn ($pr) => $this->formatPr($pr));

        $products  = Product::active()->with('suppliers')->get(['id', 'name', 'sku']);
        $suppliers = Supplier::active()->get(['id', 'name', 'lead_time_days', 'source_platform']);

        $view = $request->user()->hasRole('admin') 
            ? 'Admin/SupplyChain/PurchaseRequests/Index' 
            : 'InventoryManager/PurchaseRequests/Index';

        return Inertia::render($view, [
            'purchaseRequests' => $prs,
            'filters'          => $request->only(['status', 'search']),
            'isManager'        => $request->user()->hasRole('admin'),
            'routePrefix'      => $request->user()->hasRole('admin') ? 'admin' : 'inventory-manager',
            'products'         => $products->map(fn ($p) => [
                'id'   => $p->id,
                'name' => $p->name,
                'sku'  => $p->sku,
                'suppliers' => $p->suppliers->map(fn ($s) => [
                    'id'       => $s->id,
                    'name'     => $s->name,
                    'moq'      => $s->pivot->moq,
                    'unit_cost' => $s->pivot->unit_cost,
                    'lead_time_days' => $s->lead_time_days,
                ]),
            ]),
            'suppliers'        => $suppliers,
            'prefill'          => null, // Optional if we want to support prefilling the modal later
        ]);
    }

    public function create(Request $request): Response
    {
        $products  = Product::active()->with('suppliers')->get(['id', 'name', 'sku']);
        $suppliers = Supplier::active()->get(['id', 'name', 'lead_time_days', 'source_platform']);

        // Pre-fill if triggered from reorder draft
        $prefill = null;
        if ($request->draft_pr_id) {
            $draft = PurchaseRequest::find($request->draft_pr_id);
            if ($draft && $draft->status === 'draft') {
                $prefill = [
                    'product_id'  => $draft->product_id,
                    'supplier_id' => $draft->supplier_id,
                    'quantity'    => $draft->quantity_requested,
                    'unit_cost'   => $draft->unit_cost,
                    'draft_pr_id' => $draft->id,
                ];
            }
        }

        return Inertia::render('Admin/SupplyChain/PurchaseRequests/Create', [
            'products'        => $products->map(fn ($p) => [
                'id'   => $p->id,
                'name' => $p->name,
                'sku'  => $p->sku,
                'suppliers' => $p->suppliers->map(fn ($s) => [
                    'id'       => $s->id,
                    'name'     => $s->name,
                    'moq'      => $s->pivot->moq,
                    'unit_cost' => $s->pivot->unit_cost,
                    'lead_time_days' => $s->lead_time_days,
                ]),
            ]),
            'suppliers'       => $suppliers,
            'prefill'         => $prefill,
            'routePrefix'     => $request->user()->hasRole('admin') ? 'admin' : 'inventory-manager',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'            => 'required|exists:products,id',
            'supplier_id'           => 'required|exists:suppliers,id',
            'quantity_requested'    => 'required|integer|min:1',
            'unit_cost'             => 'required|numeric|min:0',
            'expected_delivery_date' => 'nullable|date|after:today',
            'notes'                 => 'nullable|string|max:2000',
            'draft_pr_id'           => 'nullable|exists:purchase_requests,id',
        ]);

        // Check MOQ for warning (not blocking at creation — just flag it)
        $moqWarning = null;
        $sp = SupplierProduct::where('supplier_id', $validated['supplier_id'])
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($sp && $validated['quantity_requested'] < $sp->moq) {
            $moqWarning = "Below MOQ of {$sp->moq}. Approver will need to override.";
        }

        $pr = PurchaseRequest::create([
            'product_id'            => $validated['product_id'],
            'supplier_id'           => $validated['supplier_id'],
            'quantity_requested'    => $validated['quantity_requested'],
            'unit_cost'             => $validated['unit_cost'],
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
            'notes'                 => $validated['notes'] ?? null,
            'status'                => 'pending_approval',
            'requested_by'          => $request->user()->id,
        ]);

        // Mark the auto-draft as consumed if this came from one
        if (! empty($validated['draft_pr_id'])) {
            PurchaseRequest::where('id', $validated['draft_pr_id'])
                ->where('status', 'draft')
                ->update(['status' => 'pending_approval']);
        }

        $message = $moqWarning
            ? "Purchase Request #{$pr->id} created with MOQ warning: {$moqWarning}"
            : "Purchase Request #{$pr->id} created successfully.";

        $routePrefix = $request->user()->hasRole('admin') ? 'admin' : 'inventory-manager';
        return redirect()->route("{$routePrefix}.purchase-requests.index")->with('success', $message);
    }

    public function approve(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        if (! $request->user()->hasRole('admin')) {
            abort(403, 'Only admins can approve purchase requests.');
        }

        if ($purchaseRequest->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'This PR is not pending approval.');
        }

        $overrideMoq = (bool) $request->input('override_moq', false);

        try {
            $po = app(ApprovePurchaseRequest::class)->handle($purchaseRequest, $request->user(), $overrideMoq);
            $routePrefix = $request->user()->hasRole('admin') ? 'admin' : 'inventory-manager';
            return redirect()->route("{$routePrefix}.purchase-requests.index")
                ->with('success', "PR approved. Purchase Order {$po->po_number} created.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->with('error', 'Approval failed. See errors below.');
        }
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        if (! $request->user()->hasRole('admin')) {
            abort(403, 'Only admins can reject purchase requests.');
        }

        if ($purchaseRequest->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'This PR is not pending approval.');
        }

        $purchaseRequest->update(['status' => 'rejected']);

        $routePrefix = $request->user()->hasRole('admin') ? 'admin' : 'inventory-manager';
        return redirect()->route("{$routePrefix}.purchase-requests.index")->with('success', 'Purchase Request rejected.');
    }

    private function formatPr(PurchaseRequest $pr): array
    {
        return [
            'id'                    => $pr->id,
            'product'               => ['id' => $pr->product->id, 'name' => $pr->product->name, 'sku' => $pr->product->sku],
            'supplier'              => ['id' => $pr->supplier->id, 'name' => $pr->supplier->name],
            'quantity_requested'    => $pr->quantity_requested,
            'unit_cost'             => $pr->unit_cost,
            'total_cost'            => $pr->unit_cost * $pr->quantity_requested,
            'expected_delivery_date' => $pr->expected_delivery_date?->toDateString(),
            'status'                => $pr->status,
            'status_label'          => $pr->status_label,
            'status_color'          => $pr->status_color,
            'is_auto_draft'         => $pr->is_auto_draft,
            'notes'                 => $pr->notes,
            'requester'             => $pr->requester->name,
            'approver'              => $pr->approver?->name,
            'approved_at'           => $pr->approved_at?->toDateString(),
            'po_number'             => $pr->purchaseOrder?->po_number,
            'created_at'            => $pr->created_at->toDateString(),
        ];
    }
}
