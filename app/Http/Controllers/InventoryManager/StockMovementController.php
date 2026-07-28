<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StockMovementController extends Controller
{
    public function index(Request $request): Response
    {
        $transactionsQuery = InventoryTransaction::with(['inventory.product', 'user', 'sourceable'])
            ->when($request->search, function ($q, $s) {
                $q->where('reference_number', 'like', "%{$s}%")
                  ->orWhereHas('inventory.product', fn ($p) => $p->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%"));
            })
            ->when($request->type, fn ($q, $t) => $q->where('transaction_type', $t))
            ->when($request->product_id, fn ($q, $p) => $q->whereHas('inventory', fn ($inv) => $inv->where('product_id', $p)))
            ->when($request->location, fn ($q, $l) => $q->whereHas('inventory', fn ($inv) => $inv->where('location', 'like', "%{$l}%")))
            ->when($request->date_range, function ($q, $range) {
                $dates = explode(' - ', $range);
                if (count($dates) == 2) {
                    $q->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
                }
            })
            ->latest();

        if ($request->has('export') && $request->export === 'csv') {
            $csvData = $transactionsQuery->get();
            $csv = "Date,Product,SKU,Type,Reference No,In Qty,Out Qty,Balance,User\n";
            foreach ($csvData as $tx) {
                $productName = str_replace('"', '""', $tx->inventory?->product?->name ?? 'Unknown');
                $sku = $tx->inventory?->product?->sku ?? '';
                $inQty = in_array($tx->transaction_type, ['add', 'receive', 'transfer_in', 'adjust_add']) ? $tx->quantity : '';
                $outQty = in_array($tx->transaction_type, ['deduct', 'sale', 'transfer_out', 'adjust_deduct']) ? $tx->quantity : '';
                $csv .= "\"{$tx->created_at}\",\"{$productName}\",{$sku},{$tx->transaction_type},{$tx->reference_number},{$inQty},{$outQty},{$tx->balance_after},{$tx->user?->name}\n";
            }
            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="stock_movements.csv"');
        }

        $transactions = $transactionsQuery->paginate(25)->withQueryString();

        $transactions->getCollection()->transform(function ($tx) {
            $inQty = in_array($tx->transaction_type, ['add', 'receive', 'transfer_in', 'adjust_add']) ? abs($tx->quantity) : 0;
            $outQty = in_array($tx->transaction_type, ['deduct', 'sale', 'transfer_out', 'adjust_deduct']) ? abs($tx->quantity) : 0;
            if ($tx->transaction_type === 'adjust' && $tx->quantity > 0) $inQty = $tx->quantity;
            if ($tx->transaction_type === 'adjust' && $tx->quantity < 0) $outQty = abs($tx->quantity);

            return [
                'id' => $tx->id,
                'date' => $tx->created_at->format('Y-m-d H:i'),
                'product_name' => $tx->inventory?->product?->name ?? 'Unknown',
                'product_category' => $tx->inventory?->product?->category?->name ?? 'Uncategorized',
                'sku' => $tx->inventory?->product?->sku ?? '',
                'type' => $tx->transaction_type,
                'reference_no' => $tx->reference_number,
                'location' => $tx->inventory?->location ?? 'Main Warehouse',
                'in_qty' => $inQty > 0 ? $inQty : null,
                'out_qty' => $outQty > 0 ? $outQty : null,
                'balance' => $tx->balance_after,
                'user' => $tx->user?->name ?? 'System',
            ];
        });

        $products = Product::active()->get(['id', 'name', 'sku']);

        return Inertia::render('InventoryManager/StockMovement/Index', [
            'transactions' => $transactions,
            'filters'      => $request->only(['search', 'type', 'product_id', 'location', 'date_range']),
            'products'     => $products,
        ]);
    }
}
