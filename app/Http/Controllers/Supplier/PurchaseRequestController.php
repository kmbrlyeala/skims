<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Notifications\PurchaseRequestStatusUpdated;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        $supplierId = $request->user()->supplier_id;

        $query = PurchaseRequest::with(['product', 'requester'])
            ->where('supplier_id', $supplierId);

        // Filtering
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        
        $allowedSorts = ['id', 'created_at', 'expected_delivery_date', 'status'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $requests = $query->paginate(20)->withQueryString();

        $requests->getCollection()->transform(function ($pr) {
            return [
                'id' => $pr->id,
                'request_no' => 'PR-' . date('Y', strtotime($pr->created_at)) . '-' . str_pad($pr->id, 4, '0', STR_PAD_LEFT),
                'date' => $pr->created_at->format('M d, Y'),
                'product' => $pr->product->name,
                'sku' => $pr->product->sku,
                'qty' => $pr->quantity_requested,
                'value' => number_format($pr->unit_cost * $pr->quantity_requested, 2),
                'need_by_date' => $pr->expected_delivery_date ? $pr->expected_delivery_date->format('M d, Y') : null,
                'status' => $pr->status, // pending_approval, approved, rejected
                'status_label' => $pr->status_label,
                'status_color' => $pr->status_color,
                'requested_by' => $pr->requester->name,
                'reject_reason' => $pr->reject_reason,
            ];
        });

        return Inertia::render('Supplier/PurchaseRequests/Index', [
            'requests' => $requests,
            'filters' => $request->only(['search', 'status', 'start_date', 'end_date', 'sort_by', 'sort_dir']),
        ]);
    }

    public function approve(Request $request, PurchaseRequest $purchaseRequest)
    {
        // Ensure it belongs to supplier
        if ($purchaseRequest->supplier_id !== $request->user()->supplier_id) {
            abort(403);
        }

        if (!in_array($purchaseRequest->status, ['pending_approval', 'pending_factory_approval'])) {
            return redirect()->back()->with('error', 'Request is not pending.');
        }

        $purchaseRequest->status = 'approved';
        $purchaseRequest->approved_by = $request->user()->id; 
        $purchaseRequest->approved_at = now();
        $purchaseRequest->save();

        if ($purchaseRequest->requester) {
            $purchaseRequest->requester->notify(new PurchaseRequestStatusUpdated($purchaseRequest, 'approved'));
        }

        return redirect()->back()->with('success', 'Purchase Request approved.');
    }

    public function reject(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->supplier_id !== $request->user()->supplier_id) {
            abort(403);
        }

        if (!in_array($purchaseRequest->status, ['pending_approval', 'pending_factory_approval'])) {
            return redirect()->back()->with('error', 'Request is not pending.');
        }

        $request->validate([
            'reject_reason' => 'required|string|max:1000'
        ]);

        $purchaseRequest->status = 'rejected';
        $purchaseRequest->reject_reason = $request->reject_reason;
        $purchaseRequest->save();

        if ($purchaseRequest->requester) {
            $purchaseRequest->requester->notify(new PurchaseRequestStatusUpdated($purchaseRequest, 'rejected', $request->reject_reason));
        }

        return redirect()->back()->with('success', 'Purchase Request rejected.');
    }
}
