<?php

namespace App\Models;

use App\Models\Traits\ReadableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SubscriptionTier;
use App\Models\User;

/**
 * @property-read int $id
 * @property string $name
 * @property SubscriptionTier $subscription_tier
 */
class Subscriber extends Model
{
    use HasFactory;
    use ReadableTrait;

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
