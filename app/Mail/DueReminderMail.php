<?php

namespace App\Mail;

use App\Models\DuePayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DueReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly DuePayment $payment,
        public readonly string $tone = 'santai',
    ) {}

    public function envelope(): Envelope
    {
        $due = $this->payment->due;

        return new Envelope(
            subject: 'Pengingat Iuran: '.$due->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.due-reminder',
            with: [
                'payment' => $this->payment,
                'due' => $this->payment->due,
                'member' => $this->payment->member,
                'group' => $this->payment->due->group,
                'tone' => $this->tone,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
