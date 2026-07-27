<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Suppliers (Factories) ---
        $supplierModel1 = Supplier::create([
            'name'            => 'SKIMS Beauty Factory',
            'contact_name'    => 'Sarah Johnson',
            'contact_email'   => 'sarah.j@skims-beauty.example',
            'source_platform' => 'local_factory',
            'lead_time_days'  => 14,
            'notes'           => 'In-house manufacturing for all SKIMS beauty essentials.',
            'is_active'       => true,
        ]);

        $supplierModel2 = Supplier::create([
            'name'            => 'SKIMS Hygiene Labs',
            'contact_name'    => 'Dr. Emma Stone',
            'contact_email'   => 'emma.s@skims-hygiene.example',
            'source_platform' => 'local_factory',
            'lead_time_days'  => 10,
            'notes'           => 'In-house manufacturing for all SKIMS hygiene products.',
            'is_active'       => true,
        ]);

        // --- Users ---
        $admin = User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@skimshop.local',
            'password' => 'password123',
            'role'     => 'admin',
        ]);

        $inventoryManager = User::factory()->create([
            'name'     => 'Inventory Manager',
            'email'    => 'inventory@skimshop.local',
            'password' => 'password123',
            'role'     => 'inventory_manager',
        ]);

        $supplier = User::factory()->create([
            'name'        => 'Supplier User',
            'email'       => 'supplier@supplier.local',
            'password'    => 'password123',
            'role'        => 'supplier',
            'supplier_id' => $supplierModel1->id,
        ]);

        $supplier2 = User::factory()->create([
            'name'        => 'Glow Botanics',
            'email'       => 'glow@supplier.local',
            'password'    => 'password123',
            'role'        => 'supplier',
            'supplier_id' => $supplierModel2->id,
        ]);

        $customer = User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'customer@skimshop.local',
            'password' => 'password123',
            'role'     => 'customer',
        ]);

        // --- Inventory Items (Products) ---
        $products = [
            [
                'supplier_id'       => $supplier->id,
                'supplier_model_id' => $supplierModel1->id,
                'name'              => 'Velvet Rose Moisturizer',
                'description'       => 'A lightweight, fast-absorbing daily moisturizer infused with rose hip oil and hyaluronic acid. Leaves skin soft, dewy, and hydrated all day.',
                'image_url'         => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop',
                'sku'               => 'SKIMS-MOIST-001',
                'stock'             => 50,
                'price'             => 42.00,
                'status'            => 'active',
            ],
            [
                'supplier_id'       => $supplier->id,
                'supplier_model_id' => $supplierModel1->id,
                'name'              => 'Silk Serum Drops',
                'description'       => 'Concentrated vitamin C serum with niacinamide for brighter, more even-toned skin. Silky texture absorbs instantly.',
                'image_url'         => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=400&fit=crop',
                'sku'               => 'SKIMS-SERUM-001',
                'stock'             => 35,
                'price'             => 58.00,
                'status'            => 'active',
            ],
            [
                'supplier_id'       => $supplier->id,
                'supplier_model_id' => $supplierModel1->id,
                'name'              => 'Cloud Cleanser Foam',
                'description'       => 'Gentle foaming cleanser with chamomile extract. Removes makeup and impurities without stripping natural oils.',
                'image_url'         => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=400&fit=crop',
                'sku'               => 'SKIMS-CLEAN-001',
                'stock'             => 80,
                'price'             => 28.00,
                'status'            => 'active',
            ],
            [
                'supplier_id'       => $supplier2->id,
                'supplier_model_id' => $supplierModel2->id,
                'name'              => 'Petal Lip Balm Duo',
                'description'       => 'Two-pack of buttery lip balms in Rose Petal and Honey Nude. Enriched with shea butter and vitamin E.',
                'image_url'         => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop',
                'sku'               => 'GLOW-LIP-001',
                'stock'             => 120,
                'price'             => 18.00,
                'status'            => 'active',
            ],
            [
                'supplier_id'       => $supplier2->id,
                'supplier_model_id' => $supplierModel2->id,
                'name'              => 'Midnight Repair Cream',
                'description'       => 'Rich overnight cream with retinol and peptides. Wake up to visibly firmer, smoother skin.',
                'image_url'         => 'https://images.unsplash.com/photo-1570194065650-d99fb4a38691?w=400&h=400&fit=crop',
                'sku'               => 'GLOW-NIGHT-001',
                'stock'             => 25,
                'price'             => 65.00,
                'status'            => 'active',
            ],
            [
                'supplier_id'       => $supplier2->id,
                'supplier_model_id' => $supplierModel2->id,
                'name'              => 'Sunbeam SPF 50',
                'description'       => 'Weightless mineral sunscreen with a natural finish. Protects against UVA/UVB without the white cast.',
                'image_url'         => 'https://images.unsplash.com/photo-1532947974358-a218d68024e1?w=400&h=400&fit=crop',
                'sku'               => 'GLOW-SPF-001',
                'stock'             => 60,
                'price'             => 35.00,
                'status'            => 'active',
            ],
            [
                'supplier_id'       => $supplier->id,
                'supplier_model_id' => $supplierModel1->id,
                'name'              => 'Aura Body Oil (Draft)',
                'description'       => 'Luxurious body oil blend — coming soon.',
                'image_url'         => null,
                'sku'               => 'SKIMS-OIL-001',
                'stock'             => 0,
                'price'             => 48.00,
                'status'            => 'draft',
            ],
        ];

        $createdProducts = [];
        $createdAdminProducts = [];
        foreach ($products as $productData) {
            // Remove supplier_model_id before creating InventoryItem as it doesn't have that column
            $invItemData = $productData;
            unset($invItemData['supplier_model_id']);
            $createdProducts[] = InventoryItem::create($invItemData);
            
            // Create Admin Supply Chain Data
            $adminProduct = \App\Models\Product::create([
                'sku'         => $productData['sku'],
                'name'        => $productData['name'],
                'description' => $productData['description'],
                'price'       => $productData['price'],
                'is_active'   => $productData['status'] === 'active',
                'photos'      => $productData['image_url'] ? [$productData['image_url']] : null,
            ]);
            
            $inventory = \App\Models\Inventory::create([
                'product_id'    => $adminProduct->id,
                'on_hand_qty'   => $productData['stock'],
                'incoming_qty'  => 0,
                'reorder_point' => 20,
            ]);
            
            $adminProduct->suppliers()->attach($productData['supplier_model_id'], [
                'moq'       => 10,
                'unit_cost' => $productData['price'] * 0.4,
            ]);
            
            $createdAdminProducts[] = $adminProduct;
        }

        // --- Sample Deliveries (Purchase Requests & Orders) ---
        // For the first active product, let's create a pending delivery
        if (count($createdAdminProducts) > 0) {
            $p = $createdAdminProducts[0];
            $supplierId = $p->suppliers->first()->id;
            
            $pr = \App\Models\PurchaseRequest::create([
                'product_id'             => $p->id,
                'supplier_id'            => $supplierId,
                'quantity_requested'     => 100,
                'unit_cost'              => $p->price * 0.4,
                'expected_delivery_date' => now()->addDays(7),
                'status'                 => 'approved',
                'requested_by'           => $inventoryManager->id,
                'approved_by'            => $admin->id,
                'approved_at'            => now(),
            ]);

            $po = \App\Models\PurchaseOrder::create([
                'purchase_request_id'   => $pr->id,
                'po_number'             => 'PO-2026-001',
                'quantity_ordered'      => 100,
                'unit_cost'             => $pr->unit_cost,
                'total_cost'            => 100 * $pr->unit_cost,
                'expected_arrival_date' => $pr->expected_delivery_date,
                'status'                => 'ordered',
            ]);
            
            // Add to incoming inventory
            $p->inventory->addIncoming(100);
            
            // Second product: partially received delivery
            $p2 = $createdAdminProducts[1];
            $supplierId2 = $p2->suppliers->first()->id;
            
            $pr2 = \App\Models\PurchaseRequest::create([
                'product_id'             => $p2->id,
                'supplier_id'            => $supplierId2,
                'quantity_requested'     => 50,
                'unit_cost'              => $p2->price * 0.4,
                'expected_delivery_date' => now()->subDay(),
                'status'                 => 'approved',
                'requested_by'           => $inventoryManager->id,
                'approved_by'            => $admin->id,
                'approved_at'            => now()->subDays(2),
            ]);

            $po2 = \App\Models\PurchaseOrder::create([
                'purchase_request_id'   => $pr2->id,
                'po_number'             => 'PO-2026-002',
                'quantity_ordered'      => 50,
                'unit_cost'             => $pr2->unit_cost,
                'total_cost'            => 50 * $pr2->unit_cost,
                'expected_arrival_date' => $pr2->expected_delivery_date,
                'status'                => 'partially_received',
            ]);
            
            $p2->inventory->addIncoming(50);
            
            \App\Models\GoodsReceipt::create([
                'purchase_order_id' => $po2->id,
                'quantity_received' => 20,
                'quantity_damaged'  => 0,
                'received_by'       => $inventoryManager->id,
                'received_at'       => now(),
                'notes'             => 'Partial shipment arrived',
            ]);
            
            $p2->inventory->incrementStock(20);
        }

        // --- Sample Orders ---
        $order1 = Order::create([
            'customer_id' => $customer->id,
            'status'      => 'delivered',
            'total'       => 100.00,
        ]);
        OrderItem::create([
            'order_id'          => $order1->id,
            'product_id'        => $createdAdminProducts[0]->id,
            'quantity'          => 1,
            'price'             => 42.00,
        ]);
        OrderItem::create([
            'order_id'          => $order1->id,
            'product_id'        => $createdAdminProducts[1]->id,
            'quantity'          => 1,
            'price'             => 58.00,
        ]);

        $order2 = Order::create([
            'customer_id' => $customer->id,
            'status'      => 'pending',
            'total'       => 83.00,
        ]);
        OrderItem::create([
            'order_id'          => $order2->id,
            'product_id'        => $createdAdminProducts[3]->id,
            'quantity'          => 1,
            'price'             => 18.00,
        ]);
        OrderItem::create([
            'order_id'          => $order2->id,
            'product_id'        => $createdAdminProducts[4]->id,
            'quantity'          => 1,
            'price'             => 65.00,
        ]);

        // --- Cart items for customer ---
        Cart::create([
            'customer_id'       => $customer->id,
            'product_id'        => $createdAdminProducts[2]->id,
            'quantity'          => 2,
        ]);
        Cart::create([
            'customer_id'       => $customer->id,
            'product_id'        => $createdAdminProducts[5]->id,
            'quantity'          => 1,
        ]);
    }
}
