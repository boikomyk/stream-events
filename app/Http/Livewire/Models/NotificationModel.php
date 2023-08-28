<?php

namespace App\Http\Livewire\Models;

use App\Enums\NotificationRecordType;
use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use DateTime;

class NotificationModel
{
    public DateTime $created_at;
    public string $message;
    public string $extra_message = '';
    public bool $is_read;
    public NotificationRecordType $record_type;
    public int $record_id;



    public static function createFromFollower(Follower $follower): static
    {
        $notification_model = new static();
        $notification_model->message = "{$follower->name} followed you!";
        $notification_model->created_at = $follower->created_at;
        $notification_model->is_read = $follower->is_read;
        $notification_model->record_id = $follower->id;
        $notification_model->record_type = NotificationRecordType::FOLLOWER;
        return $notification_model;
    }

    public static function createFromSubscriber(Subscriber $subscriber): static
    {
        $notification_model = new static();
        $notification_model->message = "$subscriber->name ({$subscriber->subscription_tier->name}) subscribed to you!";
        $notification_model->created_at = $subscriber->created_at;
        $notification_model->is_read = $subscriber->is_read;
        $notification_model->record_id = $subscriber->id;
        $notification_model->record_type = NotificationRecordType::SUBSCRIBER;
        return $notification_model;
    }

    public static function createFromDonation(Donation $donation): static
    {
        $notification_model = new static();
        $notification_model->message = "{$donation->username} donated {$donation->amount} {$donation->currency} to you!";
        $notification_model->created_at = $donation->created_at;
        $notification_model->extra_message = $donation->message;
        $notification_model->is_read = $donation->is_read;
        $notification_model->record_id = $donation->id;
        $notification_model->record_type = NotificationRecordType::DONATION;
        return $notification_model;
    }

    public static function createFromMerchSale(MerchSale $merch_sale): static
    {
        $notification_model = new static();
        $notification_model->message = "{$merch_sale->username} bought some {$merch_sale->name} from you for {$merch_sale->price} {$merch_sale->currency}!";
        $notification_model->created_at = $merch_sale->created_at;
        $notification_model->is_read = $merch_sale->is_read;
        $notification_model->record_id = $merch_sale->id;
        $notification_model->record_type = NotificationRecordType::MERCH_SALE;
        return $notification_model;
    }

    public static function createFromObject(Follower|Subscriber|Donation|MerchSale $object) {
        $object_class = get_class($object);
        return match($object_class) {
            Follower::class => static::createFromFollower($object),
            Subscriber::class => static::createFromSubscriber($object),
            Donation::class => static::createFromDonation($object),
            MerchSale::class => static::createFromMerchSale($object),
            default => throw new \Exception("Unsupported object class {$object_class}")
        };
    }
}
