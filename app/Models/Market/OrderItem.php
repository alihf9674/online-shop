<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function singleProduct(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function amazingSale(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AmazingSale::class);
    }

    public function color(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductColor::class);
    }

    public function guarantee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Guarantee::class);
    }

    public function orderItemAttributes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItemSelectedAttribute::class);
    }
}
