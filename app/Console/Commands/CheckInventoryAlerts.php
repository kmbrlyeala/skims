<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use App\Notifications\ExpiryAlertNotification;
use Carbon\Carbon;

class CheckInventoryAlerts extends Command
{
    protected $signature = 'inventory:check-alerts';
    protected $description = 'Check inventory for low stock and expiring batches, and dispatch notifications.';

    public function handle()
    {
        $inventoryManagers = User::where('role', 'inventory_manager')->get();

        if ($inventoryManagers->isEmpty()) {
            $this->info('No inventory managers found to notify.');
            return;
        }

        $this->info('Checking low stock...');
        $lowStockProducts = Product::active()->lowStock()->with('inventory')->get();
        foreach ($lowStockProducts as $product) {
            // In a real application, you might want to prevent duplicate notifications
            // e.g., by checking if a notification was already sent recently.
            foreach ($inventoryManagers as $manager) {
                $manager->notify(new LowStockNotification($product));
            }
            $this->line("Notified low stock for {$product->name}");
        }

        $this->info('Checking expiring batches (within 30 days)...');
        // Find batches expiring within 30 days
        $threshold = Carbon::now()->addDays(30);
        $expiringProducts = Product::active()->whereHas('inventoryBatches', function ($q) use ($threshold) {
            $q->whereNotNull('expiration_date')
              ->where('expiration_date', '<=', $threshold->toDateString())
              ->where('quantity', '>', 0);
        })->with(['inventoryBatches' => function ($q) use ($threshold) {
            $q->whereNotNull('expiration_date')
              ->where('expiration_date', '<=', $threshold->toDateString())
              ->where('quantity', '>', 0);
        }])->get();

        foreach ($expiringProducts as $product) {
            foreach ($product->inventoryBatches as $batch) {
                $days = Carbon::parse($batch->expiration_date)->diffInDays(Carbon::now());
                foreach ($inventoryManagers as $manager) {
                    $manager->notify(new ExpiryAlertNotification($product, $batch->batch_number, $batch->expiration_date, $days));
                }
                $this->line("Notified expiry for {$product->name} (Batch: {$batch->batch_number})");
            }
        }

        $this->info('Inventory alerts check completed.');
    }
}
