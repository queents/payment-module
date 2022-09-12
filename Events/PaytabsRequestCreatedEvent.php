<?php

namespace Modules\Payment\Events;

use Illuminate\Queue\SerializesModels;

class PaytabsRequestCreatedEvent
{
    use SerializesModels;

    public array $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array &$data)
    {
        //
        $this->data = $data;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
