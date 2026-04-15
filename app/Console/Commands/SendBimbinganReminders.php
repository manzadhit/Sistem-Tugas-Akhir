<?php

namespace App\Console\Commands;

use App\Models\Submission;
use App\Notifications\ReminderBimbinganDosen;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendBimbinganReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bimbingan:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim reminder review bimbingan dosen untuk submission pending > 3 hari';

    public function handle(): int
    {
        $threshold = Carbon::now()->subDays(3);

        $submissions = Submission::with([
            'tugasAkhir.mahasiswa',
            'dosenPembimbing.dosen.user',
        ])
            ->where('status', 'pending')
            ->where(function ($query) use ($threshold) {
                $query
                    ->where(function ($q) use ($threshold) {
                        $q->whereNull('reminder_sent_at')
                            ->where('created_at', '<=', $threshold);
                    })
                    ->orWhere('reminder_sent_at', '<=', $threshold);
            })
            ->get();

        $sent = 0;
        $skipped = 0;

        foreach ($submissions as $submission) {
            $dosenUser = $submission->dosenPembimbing?->dosen?->user;

            if (! $dosenUser) {
                $skipped++;
                continue;
            }

            $dosenUser->notify(new ReminderBimbinganDosen($submission));
            $submission->forceFill([
                'reminder_sent_at' => now(),
            ])->save();

            $sent++;
        }

        $this->info("Reminder terkirim: {$sent}");

        if ($skipped > 0) {
            $this->warn("Submission dilewati (relasi dosen/user tidak lengkap): {$skipped}");
        }

        return self::SUCCESS;
    }
}
