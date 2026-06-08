<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'order_id' => $this->transaction->order_id,
            'item_name' => $this->transaction->item->name ?? 'Barang',
            'buyer_name' => $this->transaction->user->name ?? 'Pembeli',
            'total_price' => $this->transaction->price,
            'message' => 'Ada pesanan baru untuk barang "' . ($this->transaction->item->name ?? 'unknown') . '" dari ' . ($this->transaction->user->name ?? 'pembeli'),
            'url' => route('seller.orders.show', $this->transaction->id),
        ];
    }
}