<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseRequestStatusUpdated extends Notification
{
    use Queueable;

    protected $purchaseRequest;
    protected $status;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($purchaseRequest, $status, $reason = null)
    {
        $this->purchaseRequest = $purchaseRequest;
        $this->status = $status;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = "Your Purchase Request #{$this->purchaseRequest->id} has been {$this->status}.";
        if ($this->reason) {
            $message .= " Reason: {$this->reason}";
        }

        return [
            'type' => 'purchase_request_status',
            'message' => $message,
            'purchase_request_id' => $this->purchaseRequest->id,
            'status' => $this->status,
        ];
    }
}
