<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    protected $fillable = [
        'user_id', 'commande_id', 'reason', 'status', 'proof_image', 'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'commande_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'bg-yellow-900/50 text-yellow-400',
            'accepted' => 'bg-green-900/50 text-green-400',
            'rejected' => 'bg-red-900/50 text-red-400',
            default    => 'bg-gray-900/50 text-gray-400',
        };
    }
}