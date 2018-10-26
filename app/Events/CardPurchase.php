<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Event;

class CardPurchase extends Event implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * FUT transaction
     */
    private $transaction;

    /**
     * Create a new Event container
     *
     * CardPurchase constructor.
     * @param $transaction
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastWith()
    {
        return [
            'player_name' => $this->transaction->player->name,
            'bought_for' => $this->transaction->buy_bin,
            'bought_at' => $this->transaction->bought_time
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('autobuyer');
    }
}
