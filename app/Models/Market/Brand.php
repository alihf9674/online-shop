<?php

namespace App\Models\Market;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $casts = ['logo' => 'array'];

    protected $fillable = ['persian_name', 'original_name', 'slug', 'logo', 'status', 'tags'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'original_name'
            ]
        ];
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
