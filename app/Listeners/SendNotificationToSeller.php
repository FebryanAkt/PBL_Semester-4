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
        $transaction->loadMissing(['transactionItems.item.user', 'item.user']);

        $sellers = $transaction->orderLines()
            ->map(fn ($line) => $line->item?->user)
            ->filter()
            ->unique('id');

        foreach ($sellers as $seller) {
            if ($seller->isSeller()) {
                $seller->notify(new NewOrderNotification($transaction));
            }
        }
    }
}
