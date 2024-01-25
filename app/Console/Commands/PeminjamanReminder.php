<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PeminjamanReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:peminjaman-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notifikasi = Notifikasi::where('reminder_date', '<=', now())
            ->where('status', 'dipinjam')
            ->get();
    
        foreach ($notifikasi as $notifikasi) {
         
            $notifikasi->user->notify(new ReminderNotification($notifikasi));
    
         
            $notifikasi->update(['status' => 'completed']);
        }
    }
}