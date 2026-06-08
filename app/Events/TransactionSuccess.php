<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionSuccess
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Transaksi yang sukses.
     *
     * @var \App\Models\Transaction
     */
    public $transaction;

    /**
     * Buat instance event baru.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
}