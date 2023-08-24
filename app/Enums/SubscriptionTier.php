<?php

namespace App\Enums;

enum SubscriptionTier: string
{
    case TIER_1 = 'tier-1';
    case TIER_2 = 'tier-2';
    case TIER_3 = 'tier-3';


    public function getTierPrice(): int
    {
        return match ($this) {
            self::TIER_1 => 5,
            self::TIER_2 => 10,
            self::TIER_3 => 15,
        };
    }
}
