<?php

namespace App\Events;

use App\Models\Liquidation;
use App\Models\Vehicle;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLiquidationRegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $liquidation;
    public $type_travel;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Liquidation $liquidation,string $type_travel)
    {
        $this->liquidation = $liquidation;
        $this->type_travel = $type_travel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
