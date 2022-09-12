<?php

namespace Modules\Payment\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class FawryRequestCreatedEvent
{
    use SerializesModels;

    public array|Collection $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array|Collection &$data)
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
