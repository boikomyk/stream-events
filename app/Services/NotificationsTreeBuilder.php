<?php

namespace App\Services;

use App\Enums\NotificationRecordType;
use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\NotificationMapItem;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;

class NotificationsTreeBuilder
{
    const BATCH_SIZE = 200;

    public function buildTree(): void
    {
        DB::table('notification_map_items')->truncate();

        $this->addFollowersItems();
        $this->addDonationsItems();
        $this->addMerchSalesItems();
        $this->addSubscribersItems();
    }

    private function addFollowersItems(): void
    {
        $followers = Follower::all();

        DB::beginTransaction();
        try {
            $nr = 0;
            foreach ($followers as $follower)  {
                $nr++;

                $notification = new NotificationMapItem();
                $notification->record_id = $follower->id;
                $notification->user_id = $follower->user_id;
                $notification->record_created_at = $follower->created_at;
                $notification->record_type = NotificationRecordType::FOLLOWER;
                $notification->save();

                if ($nr % self::BATCH_SIZE === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    private function addSubscribersItems(): void
    {
        $subscribers = Subscriber::all();

        DB::beginTransaction();
        try {
            $nr = 0;
            foreach ($subscribers as $subscriber) {
                $nr++;

                $notification = new NotificationMapItem();
                $notification->record_id = $subscriber->id;
                $notification->user_id = $subscriber->user_id;
                $notification->record_created_at = $subscriber->created_at;
                $notification->record_type = NotificationRecordType::SUBSCRIBER;
                $notification->save();

                if ($nr % self::BATCH_SIZE === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    private function addDonationsItems(): void
    {
        $donations = Donation::all();

        DB::beginTransaction();
        try {
            $nr = 0;
            foreach ($donations as $donation) {
                $nr++;

                $notification = new NotificationMapItem();
                $notification->record_id = $donation->id;
                $notification->user_id = $donation->user_id;
                $notification->record_created_at = $donation->created_at;
                $notification->record_type = NotificationRecordType::DONATION;
                $notification->save();

                if ($nr % self::BATCH_SIZE === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    private function addMerchSalesItems(): void
    {
        $merch_sales = MerchSale::all();

        DB::beginTransaction();
        try {
            $nr = 0;
            foreach ($merch_sales as $merch_sale) {
                $nr++;

                $notification = new NotificationMapItem();
                $notification->record_id = $merch_sale->id;
                $notification->user_id = $merch_sale->user_id;
                $notification->record_created_at = $merch_sale->created_at;
                $notification->record_type = NotificationRecordType::MERCH_SALE;
                $notification->save();

                if ($nr % self::BATCH_SIZE === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }
}
