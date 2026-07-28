<?php

namespace App\Http\Controllers\InventoryManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UiMockupController extends Controller
{
    public function purchaseRequests()
    {
        return Inertia::render('InventoryManager/PurchaseRequests/Index');
    }

    public function purchaseOrders()
    {
        return Inertia::render('InventoryManager/PurchaseOrders/Index');
    }

    public function stockMovement()
    {
        $mockData = [
            'data' => [
                [
                    'id' => 1,
                    'date_time' => 'May 27, 2025 10:30 AM',
                    'product' => [
                        'name' => 'SKIMS Body Lotion',
                        'category' => 'Body Care',
                        'image' => 'https://via.placeholder.com/150',
                    ],
                    'sku' => 'LOTION-001',
                    'type' => 'Stock In',
                    'reference_no' => 'PO-10035',
                    'location' => 'Main Warehouse',
                    'in_qty' => 100,
                    'out_qty' => null,
                    'balance' => 150,
                    'user' => [
                        'name' => 'Jane M.',
                        'avatar' => 'https://ui-avatars.com/api/?name=Jane+M&color=7F9CF5&background=EBF4FF'
                    ]
                ],
                [
                    'id' => 2,
                    'date_time' => 'May 27, 2025 09:15 AM',
                    'product' => [
                        'name' => 'SKIMS Facial Serum',
                        'category' => 'Skin Care',
                        'image' => 'https://via.placeholder.com/150',
                    ],
                    'sku' => 'SERUM-002',
                    'type' => 'Stock Out',
                    'reference_no' => 'SO-20089',
                    'location' => 'Main Warehouse',
                    'in_qty' => null,
                    'out_qty' => 3,
                    'balance' => 5,
                    'user' => [
                        'name' => 'John D.',
                        'avatar' => 'https://ui-avatars.com/api/?name=John+D&color=E53E3E&background=FED7D7'
                    ]
                ],
                [
                    'id' => 3,
                    'date_time' => 'May 26, 2025 11:10 AM',
                    'product' => [
                        'name' => 'SKIMS Vitamin C Serum',
                        'category' => 'Skin Care',
                        'image' => 'https://via.placeholder.com/150',
                    ],
                    'sku' => 'VCS-005',
                    'type' => 'Adjustment',
                    'reference_no' => 'ADJ-00056',
                    'location' => 'Main Warehouse',
                    'in_qty' => null,
                    'out_qty' => 1,
                    'balance' => 12,
                    'user' => [
                        'name' => 'Jane M.',
                        'avatar' => 'https://ui-avatars.com/api/?name=Jane+M&color=7F9CF5&background=EBF4FF'
                    ]
                ],
                [
                    'id' => 4,
                    'date_time' => 'May 25, 2025 02:05 PM',
                    'product' => [
                        'name' => 'SKIMS Body Wash',
                        'category' => 'Body Care',
                        'image' => 'https://via.placeholder.com/150',
                    ],
                    'sku' => 'BW-007',
                    'type' => 'Transfer Out',
                    'reference_no' => 'TRF-30025',
                    'location' => 'Main Warehouse',
                    'in_qty' => null,
                    'out_qty' => 10,
                    'balance' => 3,
                    'user' => [
                        'name' => 'John D.',
                        'avatar' => 'https://ui-avatars.com/api/?name=John+D&color=E53E3E&background=FED7D7'
                    ]
                ],
                [
                    'id' => 5,
                    'date_time' => 'May 25, 2025 02:05 PM',
                    'product' => [
                        'name' => 'SKIMS Body Wash',
                        'category' => 'Body Care',
                        'image' => 'https://via.placeholder.com/150',
                    ],
                    'sku' => 'BW-007',
                    'type' => 'Transfer In',
                    'reference_no' => 'TRF-30025',
                    'location' => 'Branch Warehouse',
                    'in_qty' => 10,
                    'out_qty' => null,
                    'balance' => 10,
                    'user' => [
                        'name' => 'John D.',
                        'avatar' => 'https://ui-avatars.com/api/?name=John+D&color=E53E3E&background=FED7D7'
                    ]
                ]
            ],
            'from' => 1,
            'to' => 5,
            'total' => 256,
            'links' => [
                ['url' => null, 'label' => '&laquo; Previous', 'active' => false],
                ['url' => '#', 'label' => '1', 'active' => true],
                ['url' => '#', 'label' => '2', 'active' => false],
                ['url' => null, 'label' => 'Next &raquo;', 'active' => false],
            ]
        ];

        return Inertia::render('InventoryManager/StockMovement/Index', [
            'movements' => $mockData
        ]);
    }

    public function lowStock()
    {
        return Inertia::render('InventoryManager/Inventory/LowStock');
    }

    public function reports()
    {
        return Inertia::render('InventoryManager/Reports/Index');
    }
}
