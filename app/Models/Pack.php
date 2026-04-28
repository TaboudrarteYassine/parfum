<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pack extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($pack) {
            if (empty($pack->slug)) {
                $pack->slug = Str::slug($pack->name);
            }
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'pack_produit')->withPivot('quantity');
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }

    public function isAvailable(): bool
    {
        foreach ($this->products as $product) {
            if ($product->stock < $product->pivot->quantity) {
                return false;
            }
        }
        return true;
    }

    public function decreaseStock(): void
    {
        foreach ($this->products as $product) {
            $product->decreaseStock($product->pivot->quantity);
        }
    }
}