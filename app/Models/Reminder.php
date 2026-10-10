<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    protected $fillable = [
        'due_payment_id',
        'channel',
        'tone',
        'sent_at',
        'is_sent',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function duePayment(): BelongsTo
    {
        return $this->belongsTo(DuePayment::class);
    }
}
