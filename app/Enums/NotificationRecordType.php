<?php

namespace App\Enums;

enum NotificationRecordType: string
{
    case FOLLOWER = 'follower';
    case DONATION = 'donation';
    case SUBSCRIBER = 'subscriber';
    case MERCH_SALE = 'merch-sale';

    public function getRecordClass(): string
    {
        return match ($this) {
            self::FOLLOWER => \App\Models\Follower::class,
            self::DONATION => \App\Models\Donation::class,
            self::SUBSCRIBER => \App\Models\Subscriber::class,
            self::MERCH_SALE => \App\Models\MerchSale::class,
        };
    }
}