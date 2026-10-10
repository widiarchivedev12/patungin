<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settlement extends Model
{
    protected $fillable = [
        'group_id',
        'from_member_id',
        'to_member_id',
        'amount',
        'status',
        'confirmed_at',
        'proof_id',
        'note',
    ];

    protected $casts = [
        'amount' => 'integer',
        'confirmed_at' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function fromMember(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'from_member_id');
    }

    public function toMember(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'to_member_id');
    }

    public function proof(): BelongsTo
    {
        return $this->belongsTo(Attachment::class, 'proof_id');
    }

    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }
}
