<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'stock',
        'category_id', 'brand_id', 'size', 'gender',
        'is_featured', 'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProduitImage::class)->orderBy('sort_order');
    }

    public function featuredImage()
    {
        return $this->hasOne(ProduitImage::class)->where('is_featured', true);
    }

    public function packs()
    {
        return $this->belongsToMany(Pack::class, 'pack_produit')->withPivot('quantity');
    }

    public function getMainImageUrlAttribute(): string
    {
        $featured = $this->images->where('is_featured', true)->first();
        $image = $featured ?? $this->images->first();
        return $image ? asset('storage/' . $image->path) : asset('images/no-image.png');
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function decreaseStock(int $qty): void
    {
        $this->decrement('stock', $qty);
    }

    public function increaseStock(int $qty): void
    {
        $this->increment('stock', $qty);
    }
}