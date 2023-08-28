<?php

namespace App\Models;

use App\Enums\NotificationRecordType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NotificationMapItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'record_type',
        'record_created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'record_type' => NotificationRecordType::class,
        'record_created_at' => 'datetime',
    ];
}
