<?php

namespace App\Models;

use App\Models\Traits\ReadableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * @property-read int $id // column
 * @property string $name // column
 */
class Follower extends Model
{
    use HasFactory;
    use ReadableTrait;

    protected $fillable = [
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
