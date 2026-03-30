<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreProduct extends Model
{
    protected $fillable = ['name', 'description', 'price', 'image', 'stock', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
