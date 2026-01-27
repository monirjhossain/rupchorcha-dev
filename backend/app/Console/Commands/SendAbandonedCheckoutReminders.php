<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AbandonedCheckout;
use App\Mail\AbandonedCheckoutReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAbandonedCheckoutReminders extends Command
{
    protected $signature = 'abandoned-checkouts:remind';
    protected $description = 'Send reminder emails for abandoned checkouts';

    public function handle()
    {
        $cutoff = Carbon::now()->subHours(2); // Remind after 2 hours
        $checkouts = AbandonedCheckout::where('status', 'abandoned')
            ->whereNull('recovered_at')
            ->whereNotNull('email')
            ->where('last_activity_at', '<', $cutoff)
            ->get();
        $count = 0;
        foreach ($checkouts as $checkout) {
            Mail::to($checkout->email)->queue(new AbandonedCheckoutReminder($checkout));
            $checkout->update(['status' => 'reminder_sent']);
            $count++;
        }
        $this->info("Sent $count abandoned checkout reminders.");
    }
}
