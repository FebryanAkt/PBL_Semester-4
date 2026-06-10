<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'category',
        'category_id',
        'location',
        'phone',
        'condition',
        'description',
        'image',
        'images',
        'status',
        'stock',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryRecord()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->categoryRecord?->name
            ?? $this->attributes['category']
            ?? 'Lainnya';
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function purchaseValidationMessage(int $buyerId, int $quantity): ?string
    {
        if ($quantity < 1) {
            return 'Jumlah pembelian minimal 1.';
        }

        if ((int) $this->user_id === $buyerId) {
            return 'Kamu tidak dapat membeli barang milik sendiri.';
        }

        if ($this->status !== 'tersedia') {
            return 'Barang ini sudah tidak tersedia untuk dibeli.';
        }

        if ((int) $this->stock < 1) {
            return 'Stok barang sudah habis.';
        }

        if ($quantity > (int) $this->stock) {
            return 'Jumlah pembelian melebihi stok yang tersedia.';
        }

        return null;
    }
}
