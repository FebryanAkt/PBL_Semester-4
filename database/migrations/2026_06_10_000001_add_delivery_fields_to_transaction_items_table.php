<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->string('delivery_status')->default('belum_dikirim')->after('price');
            $table->string('shipping_code')->nullable()->after('delivery_status');
        });

        DB::table('transaction_items')
            ->orderBy('id')
            ->chunkById(100, function ($transactionItems) {
                foreach ($transactionItems as $transactionItem) {
                    $transaction = DB::table('transactions')
                        ->select(['delivery_status', 'shipping_code'])
                        ->where('id', $transactionItem->transaction_id)
                        ->first();

                    if ($transaction) {
                        DB::table('transaction_items')
                            ->where('id', $transactionItem->id)
                            ->update([
                                'delivery_status' => $transaction->delivery_status ?? 'belum_dikirim',
                                'shipping_code' => $transaction->shipping_code,
                            ]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropColumn(['delivery_status', 'shipping_code']);
        });
    }
};
