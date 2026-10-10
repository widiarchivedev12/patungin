<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Due extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'group_id',
        'title',
        'amount',
        'recurrence',
        'due_date',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'integer',
        'due_date' => 'date',
        'ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(DuePayment::class);
    }

    public function unpaidPayments(): HasMany
    {
        return $this->hasMany(DuePayment::class)->where('status', 'unpaid');
    }

    public function paidPayments(): HasMany
    {
        return $this->hasMany(DuePayment::class)->where('status', 'paid');
    }
}
