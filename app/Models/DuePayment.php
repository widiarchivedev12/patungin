<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DuePayment extends Model
{
    protected $fillable = [
        'due_id',
        'member_id',
        'status',
        'paid_at',
        'proof_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function due(): BelongsTo
    {
        return $this->belongsTo(Due::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'member_id');
    }

    public function proof(): BelongsTo
    {
        return $this->belongsTo(Attachment::class, 'proof_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function markAsPendingVerification(): void
    {
        $this->update(['status' => 'pending_verification']);
    }
}
