<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptItem extends Model
{
    protected $fillable = ['expense_id', 'name', 'price', 'qty'];

    protected $casts = [
        'price' => 'integer',
        'qty' => 'integer',
    ];

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function subtotal(): int
    {
        return $this->price * $this->qty;
    }
}
