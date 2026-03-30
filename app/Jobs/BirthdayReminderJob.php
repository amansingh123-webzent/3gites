<?php

namespace App\Jobs;

use App\Mail\BirthdayReminderMail;
use App\Models\Birthday;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BirthdayReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum attempts before job is marked failed.
     */
    public int $tries = 3;

    /**
     * Run the birthday reminder job.
     *
     * Logic:
     *  1. Find classmates whose birthday is TODAY (month + day match).
     *  2. For each birthday person, send a personal "Happy Birthday" email.
     *  3. For each OTHER active member, send a "It's [Name]'s birthday!" reminder.
     *
     * This job runs daily at 8:00 AM via the scheduler.
     */
    public function handle(): void
    {
        $today = now();

        // Find members whose birthday is today
        $celebrants = Birthday::where('birth_month', $today->month)
            ->where('birth_day', $today->day)
            ->with('user')
            ->get()
            ->filter(fn ($b) => $b->user
                && $b->user->member_status === 'active'
                && ! $b->user->account_locked
            );

        if ($celebrants->isEmpty()) {
            Log::info('BirthdayReminderJob: No birthdays today.');
            return;
        }

        // All active members (for sending them reminder about celebrants)
        $activeMembers = User::where('member_status', 'active')
            ->where('account_locked', false)
            ->whereNotNull('email')
            ->get();

        foreach ($celebrants as $birthday) {
            $celebrant = $birthday->user;

            // Send "Happy Birthday" directly to the celebrant
            try {
                Mail::to($celebrant->email)
                    ->queue(new BirthdayReminderMail($celebrant, isCelebrant: true));
            } catch (\Exception $e) {
                Log::error("Birthday email failed for {$celebrant->name}: " . $e->getMessage());
            }

            // Notify other active members about this classmate's birthday
            $others = $activeMembers->where('id', '!=', $celebrant->id);

            foreach ($others as $member) {
                try {
                    Mail::to($member->email)
                        ->queue(new BirthdayReminderMail($celebrant, isCelebrant: false, recipient: $member));
                } catch (\Exception $e) {
                    Log::error("Birthday reminder failed to {$member->name}: " . $e->getMessage());
                }
            }

            Log::info("BirthdayReminderJob: Sent greetings for {$celebrant->name}.");
        }
    }
}
