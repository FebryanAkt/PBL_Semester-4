<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionItem;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'price',
        'status',
        'order_id',
        'snap_token',
        'delivery_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // transaksi lama (beli langsung)
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // transaksi banyak barang (keranjang)
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', 'success');
    }

    public function scopeForSeller(Builder $query, int $sellerId): Builder
    {
        return $query->where(function (Builder $query) use ($sellerId) {
            $query
                ->whereHas('item', fn (Builder $itemQuery) => $itemQuery->where('user_id', $sellerId))
                ->orWhereHas(
                    'transactionItems.item',
                    fn (Builder $itemQuery) => $itemQuery->where('user_id', $sellerId)
                );
        });
    }

    public function orderLines(): Collection
    {
        $this->loadMissing(['transactionItems.item', 'item']);

        if ($this->transactionItems->isNotEmpty()) {
            return $this->transactionItems;
        }

        if (!$this->item) {
            return collect();
        }

        $legacyLine = new TransactionItem([
            'item_id' => $this->item_id,
            'quantity' => $this->quantity,
            'price' => $this->item->price,
            'delivery_status' => $this->delivery_status ?? 'belum_dikirim',
            'shipping_code' => $this->shipping_code,
        ]);
        $legacyLine->setRelation('item', $this->item);

        return collect([$legacyLine]);
    }

    public function orderLinesForSeller(int $sellerId): Collection
    {
        return $this->orderLines()
            ->filter(fn (TransactionItem $line) => (int) $line->item?->user_id === $sellerId)
            ->values();
    }

    public function deliveryStatusSummary(?int $sellerId = null): string
    {
        $lines = $sellerId
            ? $this->orderLinesForSeller($sellerId)
            : $this->orderLines();

        if ($lines->isEmpty()) {
            return $this->delivery_status ?? 'belum_dikirim';
        }

        $statuses = $lines
            ->pluck('delivery_status')
            ->filter()
            ->unique();

        if ($statuses->count() === 1) {
            return $statuses->first();
        }

        if ($statuses->contains('belum_dikirim')) {
            return 'belum_dikirim';
        }

        if ($statuses->contains('dikirim')) {
            return 'dikirim';
        }

        return 'diterima';
    }
}
