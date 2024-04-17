<?php

namespace App\Models\Market;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $casts = ['image' => 'array'];

    protected $fillable = [
        'name',
        'introduction',
        'slug',
        'image',
        'status',
        'tags',
        'weight',
        'length',
        'width',
        'height',
        'price',
        'marketable',
        'sold_number',
        'frozen_number',
        'marketable_number',
        'brand_id',
        'category_id',
        'published_at'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function metas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductMeta::class);
    }

    public function colors(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductColor::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function values(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CategoryValue::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany('App\Models\Content\Comment', 'commentable');
    }
}
