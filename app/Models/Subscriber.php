<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SubscriptionTier;
use App\Models\User;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_tier'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'subscription_tier' => SubscriptionTier::class
    ];
}
