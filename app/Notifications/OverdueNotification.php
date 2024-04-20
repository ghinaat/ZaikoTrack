<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Peminjaman;

class OverdueNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Peminjaman $item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['mail']; // Specify the notification channel(s) to use
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Item Overdue Notification')
            ->line('An item you are responsible for is overdue.')
            ->line('Item ID: ' . $this->item->id)
            ->line('Due Date: ' . $this->item->tgl_kembali->toFormattedDateString())
            ->action('View Item', route('peminjaman.show', $this->item->id))
            ->line('Please take necessary action.');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
