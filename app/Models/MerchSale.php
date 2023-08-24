<?php

namespace App\Models;

use App\Enums\MerchItemType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MerchSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount', // quantity
        'price',  // total price
        'currency',
        'item_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'item_type' => MerchItemType::class
    ];
}
