<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'path',
        'original_name',
        'mime_type',
        'size',
        'type',
        'ai_extraction',
        'confidence',
        'is_processed',
    ];

    protected $casts = [
        'ai_extraction' => 'array',
        'is_processed' => 'boolean',
        'size' => 'integer',
        'confidence' => 'integer',
    ];

    public function url(): string
    {
        return route('attachments.show', $this);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    protected static function booted(): void
    {
        static::deleting(function (Attachment $attachment) {
            Storage::disk('private')->delete($attachment->path);
        });
    }
}
