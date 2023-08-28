<?php

namespace App\Services;

use App\Enums\MerchItemType;
use App\Enums\SubscriptionTier;
use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use DateTime;

class FakeDataGenerator {
    const BATCH_SIZE = 200;
    const NUMBER_OF_RECORDS_TO_CREATE = 500;

    function __construct(private NotificationsTreeBuilder $bulder) {}

    public function generateFakeDataForUserNotificationsTable(User $user): void {
        $this->generateFollowersForUser($user);
        $this->generateSubscribersForUser($user);
        $this->generateDonationsForUser($user);
        $this->generateMerchSalesForUser($user);

        // build tree
        $this->bulder->buildTree();
    }

    private function generateFollowersForUser(User $user): void
    {
        DB::table('followers')->truncate();
        DB::beginTransaction();
        try {
            $nr = 0;
            for ($i = 0; $i < self::NUMBER_OF_RECORDS_TO_CREATE; $i++) {
                $nr++;

                $follower = new Follower();
                $follower->name = "RandomFollower{$nr}";
                $follower->user_id = $user->id;
                $follower->created_at = $follower->updated_at = $this->getRandomDateInTheLastThreeMonths();
                $follower->save();

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

    private function generateSubscribersForUser(User $user): void
    {
        DB::table('subscribers')->truncate();
        DB::beginTransaction();
        try {
            $nr = 0;
            for ($i = 0; $i < self::NUMBER_OF_RECORDS_TO_CREATE; $i++) {
                $nr++;

                $subscriber = new Subscriber();
                $subscriber->name = "RandomSubscriber{$nr}";
                $subscriber->user_id = $user->id;
                $subscriber->created_at = $subscriber->updated_at = $this->getRandomDateInTheLastThreeMonths();
                $subscriber->subscription_tier = SubscriptionTier::cases()[array_rand(SubscriptionTier::cases())];

                $subscriber->save();

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

    private function generateDonationsForUser(User $user): void
    {
        DB::table('donations')->truncate();
        DB::beginTransaction();
        try {
            $nr = 0;
            for ($i = 0; $i < self::NUMBER_OF_RECORDS_TO_CREATE; $i++) {
                $nr++;

                $donation = new Donation();
                $donation->amount = round(10 + mt_rand() / mt_getrandmax() * (200 - 10), 2);
                $donation->currency = 'USD';
                $donation->username = "RadnomDonor{$nr}";
                $donation->message = "Thanks from donor{$nr} (donated {$donation->amount} {$donation->currency})";
                $donation->created_at = $donation->updated_at = $this->getRandomDateInTheLastThreeMonths();
                $donation->user_id = $user->id;
                $donation->save();

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

    private function generateMerchSalesForUser(User $user): void
    {
        $getRandomColor = function () {
            $colors = ['black', 'white', 'red', 'yellow'];
            return $colors[array_rand($colors)];
        };

        DB::table('merch_sales')->truncate();
        DB::beginTransaction();
        try {
            $nr = 0;
            for ($i = 0; $i < self::NUMBER_OF_RECORDS_TO_CREATE; $i++) {
                $nr++;

                $merch_sale = new MerchSale();
                $merch_sale->item_type = MerchItemType::cases()[array_rand(MerchItemType::cases())];
                $merch_sale->name = "{$merch_sale->item_type->value} " . $getRandomColor();
                $merch_sale->username = "RandomUser{$nr}";
                $merch_sale->amount = random_int(1, 3);
                $merch_sale->price = round(10 + mt_rand() / mt_getrandmax() * (200 - 10), 2);
                $merch_sale->currency = 'USD';
                $merch_sale->created_at = $merch_sale->updated_at = $this->getRandomDateInTheLastThreeMonths();
                $merch_sale->user_id = $user->id;
                $merch_sale->save();

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

    private function getRandomDateInTheLastThreeMonths(): DateTime
    {
        $start_date = new DateTime('now');
        $end_date = new DateTime('now - 3 months');

        return (new DateTime())->setTimestamp(rand($end_date->getTimestamp(), $start_date->getTimestamp()));
    }
}
