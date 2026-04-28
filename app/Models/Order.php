<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'guest_name', 'guest_email', 'guest_phone', 'guest_address',
        'total', 'status', 'tracking_code', 'notes',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->tracking_code)) {
                $order->tracking_code = strtoupper(Str::random(10));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function retour()
    {
        return $this->hasOne(Retour::class, 'commande_id');
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    public function getCustomerEmailAttribute(): string
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function canBeReturned(): bool
    {
        return $this->isDelivered()
            && $this->updated_at->diffInDays(now()) <= 7
            && !$this->retour;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'bg-yellow-900/50 text-yellow-400',
            'confirmed' => 'bg-blue-900/50 text-blue-400',
            'shipped'   => 'bg-purple-900/50 text-purple-400',
            'delivered' => 'bg-green-900/50 text-green-400',
            'cancelled' => 'bg-red-900/50 text-red-400',
            default     => 'bg-gray-900/50 text-gray-400',
        };
    }
}