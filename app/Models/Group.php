<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Group extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'owner_id',
        'invite_code',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Group $group) {
            if (! $group->invite_code) {
                $group->invite_code = strtoupper(Str::random(8));
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function dues(): HasMany
    {
        return $this->hasMany(Due::class);
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }

    public function memberForUser(User $user): ?GroupMember
    {
        return $this->members()->where('user_id', $user->id)->first();
    }

    public function regenerateInviteCode(): void
    {
        $this->update(['invite_code' => strtoupper(\Illuminate\Support\Str::random(8))]);
    }
}
