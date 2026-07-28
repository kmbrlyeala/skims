<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class LowStockNotification extends Notification
{
    use Queueable;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'low_stock',
            'product_id' => $this->product->id,
            'message' => "Low stock alert for {$this->product->name}. Current: {$this->product->inventory->on_hand_qty}, Reorder Point: {$this->product->inventory->reorder_point}",
            'url' => route('inventory-manager.supply-inventory.index', ['search' => $this->product->sku]),
        ];
    }
}
