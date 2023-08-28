<?php

namespace App\Models;

use App\Enums\MerchItemType;
use App\Models\Traits\ReadableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * @property-read int $id
 * @property string $name
 * @property int $amount
 * @property float $price
 * @property string $currency
 * @property string $username
 * @property MerchItemType $item_type
 */
class MerchSale extends Model
{
    use HasFactory;
    use ReadableTrait;

    protected $fillable = [
        'name',
        'username',
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
