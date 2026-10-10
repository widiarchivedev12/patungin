<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupMember extends Model
{
    protected $fillable = [
        'group_id',
        'user_id',
        'display_name',
        'role',
        'is_guest',
    ];

    protected $casts = [
        'is_guest' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expensesPaid(): HasMany
    {
        return $this->hasMany(Expense::class, 'paid_by_member_id');
    }

    public function expenseShares(): HasMany
    {
        return $this->hasMany(ExpenseShare::class, 'member_id');
    }

    public function duePayments(): HasMany
    {
        return $this->hasMany(DuePayment::class, 'member_id');
    }

    public function settlementsFrom(): HasMany
    {
        return $this->hasMany(Settlement::class, 'from_member_id');
    }

    public function settlementsTo(): HasMany
    {
        return $this->hasMany(Settlement::class, 'to_member_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
