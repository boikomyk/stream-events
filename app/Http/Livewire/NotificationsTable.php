<?php

namespace App\Http\Livewire;

use App\Enums\NotificationRecordType;
use App\Models\NotificationMapItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Http\Livewire\Models\NotificationModel;
use Illuminate\Database\Eloquent\Collection;

class NotificationsTable extends Component
{
    public $totalRecords;
    public $loadAmount = 100;

    public function loadMore()
    {
        $this->loadAmount += 20;
    }

    public function mount()
    {
        $date_three_month_ago = new \DateTime('now - 3 months');
        $this->totalRecords = \DB::table('notification_map_items')
            ->where('record_created_at', '>=', $date_three_month_ago)
            ->where('user_id', '=', Auth::user()->id)
            ->count()
        ;
    }

    public function render()
    {
        $date_three_month_ago = new \DateTime('now - 3 months');
        $notification_map_items = NotificationMapItem::where('record_created_at', '>=', $date_three_month_ago)
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('record_created_at', 'desc') // latest first
            ->limit($this->loadAmount)
            ->get()
        ;

        $items = [
            ...$this->fetchNotificationMapItemsRelatedRecordsOfType(NotificationRecordType::FOLLOWER, $notification_map_items),
            ...$this->fetchNotificationMapItemsRelatedRecordsOfType(NotificationRecordType::DONATION, $notification_map_items),
            ...$this->fetchNotificationMapItemsRelatedRecordsOfType(NotificationRecordType::SUBSCRIBER, $notification_map_items),
            ...$this->fetchNotificationMapItemsRelatedRecordsOfType(NotificationRecordType::MERCH_SALE, $notification_map_items),
        ];

        /**
         * @var NotificationModel
         */
        $notifications = array_map(function(Model $object){
            return NotificationModel::createFromObject($object);
        }, $items);
        usort($notifications, fn($notification_1, $notification_2) => $notification_1->created_at < $notification_2->created_at);

        return view('livewire.notifications-table')
            ->with(
                'notifications',
                $notifications
            )
        ;
    }

    private function fetchNotificationMapItemsRelatedRecordsOfType(NotificationRecordType $type, Collection|array $notification_map_items) {
        $result_ids = [];

        foreach ($notification_map_items as $notification_map_item) {
            if ($notification_map_item->record_type === $type) {
                $result_ids[]= $notification_map_item->record_id;
            }
        }

        if (!$result_ids) {
            return [];
        }
        return $type->getRecordClass()::find($result_ids);
    }
}
