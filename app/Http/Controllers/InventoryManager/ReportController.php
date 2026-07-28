<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('type', 'inventory');

        $data = [];

        if ($reportType === 'inventory') {
            $products = Product::with(['category', 'inventory'])->get();
            
            $totalProducts = $products->count();
            $totalStock = 0;
            $totalValue = 0;

            $statusBreakdown = [
                'In Stock' => 0,
                'Low Stock' => 0,
                'Out of Stock' => 0,
                'Discontinued' => 0,
            ];

            $categories = [];
            $productsFormatted = [];

            foreach ($products as $p) {
                $onHand = $p->inventory ? $p->inventory->on_hand_qty : 0;
                $val = $onHand * ($p->price ?? 0);
                
                $totalStock += $onHand;
                $totalValue += $val;

                // Status logic
                if (!$p->is_active) {
                    $statusBreakdown['Discontinued']++;
                    $statusStr = 'Discontinued';
                } elseif ($onHand == 0) {
                    $statusBreakdown['Out of Stock']++;
                    $statusStr = 'Out of Stock';
                } elseif ($p->inventory && $onHand <= $p->inventory->reorder_point) {
                    $statusBreakdown['Low Stock']++;
                    $statusStr = 'Low Stock';
                } else {
                    $statusBreakdown['In Stock']++;
                    $statusStr = 'In Stock';
                }

                // Category logic
                $catName = $p->category ? $p->category->name : 'Uncategorized';
                if (!isset($categories[$catName])) {
                    $categories[$catName] = 0;
                }
                $categories[$catName] += $val;

                $productsFormatted[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'category' => $catName,
                    'current_stock' => $onHand,
                    'unit_cost' => $p->price ?? 0,
                    'total_value' => $val,
                    'status' => $statusStr,
                ];
            }

            // Sort products by value descending
            usort($productsFormatted, function($a, $b) {
                return $b['total_value'] <=> $a['total_value'];
            });

            // Sort categories by value descending
            arsort($categories);
            
            $topCategories = [];
            foreach (array_slice($categories, 0, 5) as $name => $val) {
                $topCategories[] = [
                    'name' => $name,
                    'value' => $val,
                    'percentage' => $totalValue > 0 ? round(($val / $totalValue) * 100, 1) : 0
                ];
            }

            // Formatting status breakdown
            $statusOverview = [];
            foreach ($statusBreakdown as $name => $count) {
                $statusOverview[] = [
                    'name' => $name,
                    'count' => $count,
                    'percentage' => $totalProducts > 0 ? round(($count / $totalProducts) * 100, 1) : 0
                ];
            }

            $data = [
                'summary' => [
                    'total_products' => $totalProducts,
                    'total_stock' => $totalStock,
                    'total_value' => $totalValue,
                    'average_value' => $totalProducts > 0 ? $totalValue / $totalProducts : 0,
                ],
                'categories' => $topCategories,
                'statuses' => $statusOverview,
                'top_products' => array_slice($productsFormatted, 0, 10),
            ];

        } elseif ($reportType === 'low_stock') {
            $data = Product::active()->lowStock()->with('category', 'inventory')->get();
        } elseif ($reportType === 'out_of_stock') {
            $data = Product::active()->whereHas('inventory', function ($q) {
                $q->where('on_hand_qty', 0);
            })->with('category')->get();
        } elseif ($reportType === 'movement') {
            $data = InventoryTransaction::with(['product.category', 'user', 'batch'])
                ->orderBy('created_at', 'desc')
                ->paginate(50);
        }

        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv($reportType, $data);
        }

        return Inertia::render('InventoryManager/Reports/Index', [
            'reportType' => $reportType,
            'data' => $data,
        ]);
    }

    private function exportCsv($reportType, $data)
    {
        $csv = "";
        if ($reportType === 'inventory') {
            $csv = "Product Name,Category,Current Stock,Unit Cost,Total Value,Status\n";
            foreach ($data['top_products'] ?? [] as $row) {
                $productName = str_replace('"', '""', $row['name']);
                $csv .= "\"{$productName}\",\"{$row['category']}\",{$row['current_stock']},{$row['unit_cost']},{$row['total_value']},\"{$row['status']}\"\n";
            }
        } elseif ($reportType === 'low_stock' || $reportType === 'out_of_stock') {
            $csv = "Product Name,Category,SKU,On Hand Qty,Reorder Point\n";
            foreach ($data as $row) {
                $productName = str_replace('"', '""', $row->name);
                $onHand = $row->inventory?->on_hand_qty ?? 0;
                $reorder = $row->inventory?->reorder_point ?? 0;
                $catName = $row->category?->name ?? 'Uncategorized';
                $csv .= "\"{$productName}\",\"{$catName}\",{$row->sku},{$onHand},{$reorder}\n";
            }
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="report_' . $reportType . '.csv"');
    }
}
