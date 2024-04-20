<?php

namespace App\Observers;

use App\Mail\NotifikasiMail;
use App\Models\Notifikasi;
use App\Models\EmailConfiguration; // Adjust the namespace as per your models
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;


class NotifikasiObserver
{
    /**
     * Handle the Notifikasi "created" event.
     */
    public function created(Notifikasi $notifikasi): void
    {

         // Retrieve the email configuration from the database
         $emailConfig = EmailConfiguration::find(1); // Replace '1' with the appropriate ID

         // Set the SMTP settings using the retrieved configuration
         if ($emailConfig) {
             Config::set('mail.mailers.smtp', [
                 'transport' => 'smtp',
                 'host' => $emailConfig->host,
                 'port' => $emailConfig->port,
                 'username' => $emailConfig->username,
                 'email' => $emailConfig->email,
                 'password' => $emailConfig->password,
                 'timeout' => $emailConfig->timeout,
                 'auth_mode' => null, // Adjust if necessary
             ]);
         }
 
        if ($notifikasi->send_email == 'yes') {
            Mail::to($notifikasi->users)->send(new NotifikasiMail($notifikasi));
        }
    }

    /**
     * Handle the Notifikasi "updated" event.
     */
    public function updated(Notifikasi $notifikasi): void
    {
        //
    }

    /**
     * Handle the Notifikasi "deleted" event.
     */
    public function deleted(Notifikasi $notifikasi): void
    {
        //
    }

    /**
     * Handle the Notifikasi "restored" event.
     */
    public function restored(Notifikasi $notifikasi): void
    {
        //
    }

    /**
     * Handle the Notifikasi "force deleted" event.
     */
    public function forceDeleted(Notifikasi $notifikasi): void
    {
        //
    }
}
