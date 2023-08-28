<?php

namespace App\Http\Livewire;

use App\Enums\SubscriptionTier;
use App\Http\Livewire\Models\SummaryTable\TopItem;
use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Http\Livewire\Models\SummaryTableModel;
use DateTime;
use phpDocumentor\Reflection\Types\Void_;

class SummaryTable extends Component
{
    public function render()
    {
        $summary_table_model = $this->prepareSummaryInPast30Days(Auth::user());


        return view('livewire.summary-table')
            ->with(
                'summary_table',
                $summary_table_model
            )
        ;
    }

    private function prepareSummaryInPast30Days(User $user): SummaryTableModel
    {
        $month_ago = new DateTime('now - 1 months');

        $summary_table_model = new SummaryTableModel();
        // fill the model with data
        $this->fillSummaryTotalRevenue($summary_table_model, $user, $month_ago);
        $this->fillSummaryFollowersAmount($summary_table_model, $user, $month_ago);
        $this->fillSummaryTopItems($summary_table_model, $user, $month_ago);

        return $summary_table_model;
    }

    private function fillSummaryTotalRevenue(SummaryTableModel $data, User $user, DateTime $lower_date_limit): void
    {
        $total_revenue = 0.;

        // resolve total revenue
        // - subscription revenue
        $sum_select_part = 'CASE';
        foreach(SubscriptionTier::cases() as $subscription_tier) {
            $sum_select_part.= " WHEN subscription_tier = '{$subscription_tier->value}' THEN {$subscription_tier->getTierPrice()}";
        }
        $sum_select_part .= " ELSE 0 END";

        $subscription_revenue = Subscriber::where('created_at', '>=', $lower_date_limit)
            ->where('user_id', '=', $user->id)
            ->sum(\DB::raw($sum_select_part));
        // - donations revenue
        $donation_revenue = Donation::where('created_at', '>=', $lower_date_limit)
            ->where('user_id', '=', $user->id)
            ->sum('amount')
        ;
        // - merch revenue
        $merch_revenue = MerchSale::where('created_at', '>=', $lower_date_limit)
            ->where('user_id', '=', $user->id)
            ->sum('amount')
        ;
        $total_revenue += 
            (float)$subscription_revenue +
            (float)$donation_revenue +
            (float)$merch_revenue
        ;
        $data->total_revenue_label = $this->formatCurrency($total_revenue, 'USD');
    }

    private function fillSummaryFollowersAmount(SummaryTableModel $data, User $user, DateTime $lower_date_limit): void
    {
        // resolve followers amount
        $data->followers_amount = Follower::where('created_at', '>=', $lower_date_limit)
            ->where('user_id', '=', $user->id)
            ->count()
        ;
    }

    private function fillSummaryTopItems(SummaryTableModel $data, User $user, DateTime $lower_date_limit, int $items_limit = 3): void
    {
        // resolve top 3 items
        $data->top_items = [];

        // let's assume that a unique item is identified by name
        $top_merch_sale_data = MerchSale::where('created_at', '>=', $lower_date_limit)
            ->where('user_id', '=', $user->id)
            ->groupBy('name')
            ->orderBy('total_price')
            ->limit($items_limit)
            ->get(['name', \DB::raw('SUM(price) AS total_price')])
        ;

        foreach($top_merch_sale_data as $record)
        {
            $top_item = new TopItem();
            $top_item->name = $record['name'];
            $top_item->total_price_label = $this->formatCurrency((float)$record['total_price'], 'USD');
            $data->top_items[]= $top_item;
        }
    }

    // ___________________ HELP FUNCS SCOPE ___________________
    private function formatCurrency(float $amount, string $currency): string
    {
        $formatter = new \NumberFormatter("en_US", \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, 'USD'); // outputs $1,123.00
    }
}
