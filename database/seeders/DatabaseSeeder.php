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
            'name'        => 'SKIMS Beauty Factory',
            'email'       => 'beautyfactory@skims.local',
            'password'    => 'password123',
            'role'        => 'supplier',
            'supplier_id' => $supplierModel1->id,
        ]);

        $supplier2 = User::factory()->create([
            'name'        => 'Hygiene Labs',
            'email'       => 'hygienelabs@skims.local',
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

        // --- Products & Unified Inventory ---
        $products = [
            [
                'name'        => 'Velvet Rose Moisturizer',
                'description' => 'A lightweight, fast-absorbing daily moisturizer infused with rose hip oil and hyaluronic acid. Leaves skin soft, dewy, and hydrated all day.',
                'photos'  => ['https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop'],
                'sku'         => 'SKIMS-MOIST-001',
                'price'       => 42.00,
                'is_active'   => true,
                'stock'       => 50,
            ],
            [
                'name'        => 'Silk Serum Drops',
                'description' => 'Concentrated vitamin C serum with niacinamide for brighter, more even-toned skin. Silky texture absorbs instantly.',
                'photos'  => ['https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=400&fit=crop'],
                'sku'         => 'SKIMS-SERUM-001',
                'price'       => 58.00,
                'is_active'   => true,
                'stock'       => 35,
            ],
            [
                'name'        => 'Cloud Cleanser Foam',
                'description' => 'Gentle foaming cleanser with chamomile extract. Removes makeup and impurities without stripping natural oils.',
                'photos'  => ['https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=400&fit=crop'],
                'sku'         => 'SKIMS-CLEAN-001',
                'price'       => 28.00,
                'is_active'   => true,
                'stock'       => 80,
            ],
            [
                'name'        => 'Petal Lip Balm Duo',
                'description' => 'Two-pack of buttery lip balms in Rose Petal and Honey Nude. Enriched with shea butter and vitamin E.',
                'photos'  => ['https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop'],
                'sku'         => 'GLOW-LIP-001',
                'price'       => 18.00,
                'is_active'   => true,
                'stock'       => 120,
            ],
            [
                'name'        => 'Midnight Repair Cream',
                'description' => 'Rich overnight cream with retinol and peptides. Wake up to visibly firmer, smoother skin.',
                'photos'  => ['https://images.unsplash.com/photo-1570194065650-d99fb4a38691?w=400&h=400&fit=crop'],
                'sku'         => 'GLOW-NIGHT-001',
                'price'       => 65.00,
                'is_active'   => true,
                'stock'       => 25,
            ],
            [
                'name'        => 'Sunbeam SPF 50',
                'description' => 'Weightless mineral sunscreen with a natural finish. Protects against UVA/UVB without the white cast.',
                'photos'  => ['https://images.unsplash.com/photo-1532947974358-a218d68024e1?w=400&h=400&fit=crop'],
                'sku'         => 'GLOW-SPF-001',
                'price'       => 35.00,
                'is_active'   => true,
                'stock'       => 60,
            ],
            [
                'name'        => 'Aura Body Oil (Draft)',
                'description' => 'Luxurious body oil blend — coming soon.',
                'photos'  => [],
                'sku'         => 'SKIMS-OIL-001',
                'price'       => 48.00,
                'is_active'   => false,
                'stock'       => 0,
            ],
        ];

        $createdProducts = [];
        foreach ($products as $productData) {
            $stock = $productData['stock'];
            unset($productData['stock']);
            
            $product = \App\Models\Product::create($productData);
            
            \App\Models\Inventory::create([
                'product_id'    => $product->id,
                'on_hand_qty'   => $stock,
                'incoming_qty'  => 0,
                'reorder_point' => 10,
            ]);
            
            $createdProducts[] = $product;
        }

        // --- Link Products to Suppliers (Factories) ---
        // Supplier 1 supplies the first 3 products
        $supplierModel1->products()->attach([
            $createdProducts[0]->id => ['moq' => 100, 'unit_cost' => 15.00],
            $createdProducts[1]->id => ['moq' => 200, 'unit_cost' => 22.00],
            $createdProducts[2]->id => ['moq' => 150, 'unit_cost' => 8.50],
        ]);

        // Supplier 2 supplies the next 4 products
        $supplierModel2->products()->attach([
            $createdProducts[3]->id => ['moq' => 500, 'unit_cost' => 4.00],
            $createdProducts[4]->id => ['moq' => 100, 'unit_cost' => 28.00],
            $createdProducts[5]->id => ['moq' => 300, 'unit_cost' => 12.00],
            $createdProducts[6]->id => ['moq' => 250, 'unit_cost' => 18.00],
        ]);

        // --- Sample Orders ---
        $order1 = Order::create([
            'customer_id' => $customer->id,
            'status'      => 'delivered',
            'total'       => 100.00,
        ]);
        OrderItem::create([
            'order_id'   => $order1->id,
            'product_id' => $createdProducts[0]->id,
            'quantity'   => 1,
            'price'      => 42.00,
        ]);
        OrderItem::create([
            'order_id'   => $order1->id,
            'product_id' => $createdProducts[1]->id,
            'quantity'   => 1,
            'price'      => 58.00,
        ]);

        $order2 = Order::create([
            'customer_id' => $customer->id,
            'status'      => 'pending',
            'total'       => 83.00,
        ]);
        OrderItem::create([
            'order_id'   => $order2->id,
            'product_id' => $createdProducts[3]->id,
            'quantity'   => 1,
            'price'      => 18.00,
        ]);
        OrderItem::create([
            'order_id'   => $order2->id,
            'product_id' => $createdProducts[4]->id,
            'quantity'   => 1,
            'price'      => 65.00,
        ]);

        // --- Cart items for customer ---
        Cart::create([
            'customer_id' => $customer->id,
            'product_id'  => $createdProducts[2]->id,
            'quantity'    => 2,
        ]);
        Cart::create([
            'customer_id' => $customer->id,
            'product_id'  => $createdProducts[5]->id,
            'quantity'    => 1,
        ]);
    }
}
