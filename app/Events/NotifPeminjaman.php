<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifPeminjaman
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $detail_peminjaman;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\DetailPeminjaman $detail_peminjaman
     */
    public function __construct($detail_peminjaman)
    {
        $this->detail_peminjaman = $detail_peminjaman;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
