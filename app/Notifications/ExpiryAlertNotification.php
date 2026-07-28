<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Product;

class ExpiryAlertNotification extends Notification
{
    use Queueable;

    public $product;
    public $batchNumber;
    public $expirationDate;
    public $daysUntilExpiry;

    public function __construct(Product $product, $batchNumber, $expirationDate, $daysUntilExpiry)
    {
        $this->product = $product;
        $this->batchNumber = $batchNumber;
        $this->expirationDate = $expirationDate;
        $this->daysUntilExpiry = $daysUntilExpiry;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'expiry_alert',
            'product_id' => $this->product->id,
            'message' => "Batch {$this->batchNumber} of {$this->product->name} expires in {$this->daysUntilExpiry} days ({$this->expirationDate}).",
            'url' => route('inventory-manager.products.show', $this->product->id),
        ];
    }
}
