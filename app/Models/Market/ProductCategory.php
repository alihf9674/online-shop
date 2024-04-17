<?php

namespace App\Models\Market;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $casts = ['image' => 'array'];

    protected $fillable = ['name', 'description', 'slug', 'image', 'status', 'tags', 'show_in_menu', 'parent_id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo($this, 'parent_id')->with('parent');
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany($this, 'parent_id')->with('children');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function attributes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CategoryAttribute::class);
    }
}
