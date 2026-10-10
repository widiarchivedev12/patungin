<?php

namespace App\Console\Commands;

use App\Mail\DueReminderMail;
use App\Models\DuePayment;
use App\Models\Reminder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('app:send-due-reminders')]
#[Description('Kirim pengingat iuran yang belum dibayar kepada anggota')]
class SendDueReminders extends Command
{
    public function handle(): int
    {
        $payments = DuePayment::query()
            ->where('status', 'unpaid')
            ->whereHas('due', fn ($q) => $q->where('is_active', true)->where('due_date', '<=', today()))
            ->whereHas('member', fn ($q) => $q->whereNotNull('user_id'))
            ->with(['due.group', 'member.user'])
            ->get();

        $sent = 0;
        foreach ($payments as $payment) {
            $user = $payment->member->user;
            if (! $user) {
                continue;
            }

            // Jangan kirim lebih dari 1 pengingat per hari per payment
            $alreadySentToday = $payment->reminders()
                ->whereDate('created_at', today())
                ->where('is_sent', true)
                ->exists();

            if ($alreadySentToday) {
                continue;
            }

            Mail::to($user->email)->queue(new DueReminderMail($payment));

            Reminder::create([
                'due_payment_id' => $payment->id,
                'channel' => 'email',
                'tone' => 'santai',
                'sent_at' => now(),
                'is_sent' => true,
            ]);

            $sent++;
        }

        $this->info("Pengingat terkirim: {$sent}");

        return Command::SUCCESS;
    }
}
