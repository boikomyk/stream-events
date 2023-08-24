<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationsTable extends Component
{
    public $totalRecords;
    public $loadAmount = 20;

    public function loadMore()
    {
        $this->loadAmount += 20;
    }

    public function mount()
    {
        $this->totalRecords = 1000;
    }

    public function render()
    {
        $res = range(1, $this->loadAmount);

        return view('livewire.notifications-table')
            ->with(
                'notifications',
                array_values($res)
                // User::orderBy('created_at', 'desc')
                //     ->limit($this->loadAmount)
                //     ->get()
            )
        ;
    }
}
