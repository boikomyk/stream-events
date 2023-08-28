<?php

namespace App\Models;

use App\Models\Traits\ReadableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * @property-read int $id
 * @property string $username
 * @property float $amount
 * @property string $currency
 * @property string $message
 */
class Donation extends Model
{
    use HasFactory;
    use ReadableTrait;

    // Let's pretend that we receive donations in dollars,
    // otherwise it would be easiest to add an additional column to represent
    //  the amount of the donation converted to dollars. (ex. internal_currency_amount)
    protected $fillable = [
        'username',
        'amount',
        'currency',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
