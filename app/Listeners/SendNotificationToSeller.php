<?php

namespace App\Listeners;

use App\Events\TransactionSuccess;
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToSeller implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(TransactionSuccess $event): void
    {
        $transaction = $event->transaction;
        $seller = $transaction->item->user; // Penjual

        if ($seller && $seller->isSeller()) {
            $seller->notify(new NewOrderNotification($transaction));
        }
    }
}