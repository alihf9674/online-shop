<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryAttribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type', 'unit', 'category_id'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function values(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CategoryValue::class);
    }
}
