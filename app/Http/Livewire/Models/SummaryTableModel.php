<?php

namespace App\Http\Livewire\Models;


class SummaryTableModel
{
    public string $total_revenue_label;
    public int $followers_amount;   // in the past 30 days
    public array $top_items;        // top 3 items
}
