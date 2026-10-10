<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'group_id',
        'paid_by_member_id',
        'amount',
        'description',
        'category',
        'split_type',
        'spent_at',
        'source',
        'attachment_id',
        'is_draft',
    ];

    protected $casts = [
        'amount' => 'integer',
        'spent_at' => 'date',
        'is_draft' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(GroupMember::class, 'paid_by_member_id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(ExpenseShare::class);
    }

    public function receiptItems(): HasMany
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function attachment(): BelongsTo
    {
        return $this->belongsTo(Attachment::class);
    }
}
