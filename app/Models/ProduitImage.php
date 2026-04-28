<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProduitImage extends Model
{
    protected $table = 'produit_images';

    protected $fillable = ['product_id', 'path', 'is_featured', 'sort_order'];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($image) {
            Storage::disk('public')->delete($image->path);
        });
    }
}