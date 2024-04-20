<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Peminjaman;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule the checkOverdueItems job to run daily at a specified time
        $schedule->call(function () {
            $this->checkOverdueItems();
        })->daily();
    }
    
    /**
     * Check for overdue items and notify the technician if necessary.
     */
    protected function checkOverdueItems()
    {
        // Get current date
        $currentDate = now();
        
        // Find all borrowed items that are overdue
        $overdueItems = Peminjaman::where('tgl_kembali', '<', $currentDate)
                                 ->where('status', '!=', 'sudah_dikembalikan') // Not yet returned
                                 ->get();
    
        // Notify the technician for each overdue item
        foreach ($overdueItems as $item) {
            // Get the technician to notify (you may need to adjust this logic according to your app's data model)
            $teknisi = User::where('role', 'teknisi')->first();
    
            if ($teknisi) {
                // Send the notification to the technician
                $teknisi->notify(new OverdueNotification($item));
            }
        }
    }
    

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}