<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
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
        // --- Users ---
        $admin = User::factory()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@skimshop.local',
            'password' => 'password123',
            'role'     => 'admin',
        ]);

        $supplier = User::factory()->create([
            'name'     => 'Supplier User',
            'email'    => 'supplier@supplier.local',
            'password' => 'password123',
            'role'     => 'supplier',
        ]);

        $supplier2 = User::factory()->create([
            'name'     => 'Glow Botanics',
            'email'    => 'glow@supplier.local',
            'password' => 'password123',
            'role'     => 'supplier',
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
                'supplier_id' => $supplier->id,
                'name'        => 'Velvet Rose Moisturizer',
                'description' => 'A lightweight, fast-absorbing daily moisturizer infused with rose hip oil and hyaluronic acid. Leaves skin soft, dewy, and hydrated all day.',
                'image_url'   => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=400&h=400&fit=crop',
                'sku'         => 'SKIMS-MOIST-001',
                'stock'       => 50,
                'price'       => 42.00,
                'status'      => 'active',
            ],
            [
                'supplier_id' => $supplier->id,
                'name'        => 'Silk Serum Drops',
                'description' => 'Concentrated vitamin C serum with niacinamide for brighter, more even-toned skin. Silky texture absorbs instantly.',
                'image_url'   => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=400&fit=crop',
                'sku'         => 'SKIMS-SERUM-001',
                'stock'       => 35,
                'price'       => 58.00,
                'status'      => 'active',
            ],
            [
                'supplier_id' => $supplier->id,
                'name'        => 'Cloud Cleanser Foam',
                'description' => 'Gentle foaming cleanser with chamomile extract. Removes makeup and impurities without stripping natural oils.',
                'image_url'   => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&h=400&fit=crop',
                'sku'         => 'SKIMS-CLEAN-001',
                'stock'       => 80,
                'price'       => 28.00,
                'status'      => 'active',
            ],
            [
                'supplier_id' => $supplier2->id,
                'name'        => 'Petal Lip Balm Duo',
                'description' => 'Two-pack of buttery lip balms in Rose Petal and Honey Nude. Enriched with shea butter and vitamin E.',
                'image_url'   => 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?w=400&h=400&fit=crop',
                'sku'         => 'GLOW-LIP-001',
                'stock'       => 120,
                'price'       => 18.00,
                'status'      => 'active',
            ],
            [
                'supplier_id' => $supplier2->id,
                'name'        => 'Midnight Repair Cream',
                'description' => 'Rich overnight cream with retinol and peptides. Wake up to visibly firmer, smoother skin.',
                'image_url'   => 'https://images.unsplash.com/photo-1570194065650-d99fb4a38691?w=400&h=400&fit=crop',
                'sku'         => 'GLOW-NIGHT-001',
                'stock'       => 25,
                'price'       => 65.00,
                'status'      => 'active',
            ],
            [
                'supplier_id' => $supplier2->id,
                'name'        => 'Sunbeam SPF 50',
                'description' => 'Weightless mineral sunscreen with a natural finish. Protects against UVA/UVB without the white cast.',
                'image_url'   => 'https://images.unsplash.com/photo-1532947974358-a218d68024e1?w=400&h=400&fit=crop',
                'sku'         => 'GLOW-SPF-001',
                'stock'       => 60,
                'price'       => 35.00,
                'status'      => 'active',
            ],
            [
                'supplier_id' => $supplier->id,
                'name'        => 'Aura Body Oil (Draft)',
                'description' => 'Luxurious body oil blend — coming soon.',
                'image_url'   => null,
                'sku'         => 'SKIMS-OIL-001',
                'stock'       => 0,
                'price'       => 48.00,
                'status'      => 'draft',
            ],
        ];

        $createdProducts = [];
        foreach ($products as $product) {
            $createdProducts[] = InventoryItem::create($product);
        }

        // --- Sample Orders ---
        $order1 = Order::create([
            'customer_id' => $customer->id,
            'status'      => 'delivered',
            'total'       => 100.00,
        ]);
        OrderItem::create([
            'order_id'          => $order1->id,
            'inventory_item_id' => $createdProducts[0]->id,
            'supplier_id'       => $supplier->id,
            'quantity'          => 1,
            'price'             => 42.00,
        ]);
        OrderItem::create([
            'order_id'          => $order1->id,
            'inventory_item_id' => $createdProducts[1]->id,
            'supplier_id'       => $supplier->id,
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
            'inventory_item_id' => $createdProducts[3]->id,
            'supplier_id'       => $supplier2->id,
            'quantity'          => 1,
            'price'             => 18.00,
        ]);
        OrderItem::create([
            'order_id'          => $order2->id,
            'inventory_item_id' => $createdProducts[4]->id,
            'supplier_id'       => $supplier2->id,
            'quantity'          => 1,
            'price'             => 65.00,
        ]);

        // --- Cart items for customer ---
        Cart::create([
            'customer_id'       => $customer->id,
            'inventory_item_id' => $createdProducts[2]->id,
            'quantity'          => 2,
        ]);
        Cart::create([
            'customer_id'       => $customer->id,
            'inventory_item_id' => $createdProducts[5]->id,
            'quantity'          => 1,
        ]);
    }
}
