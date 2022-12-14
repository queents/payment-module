<?php

namespace Modules\Payment\Events;

use Illuminate\Queue\SerializesModels;

class PaymobCallBackEvent
{
    use SerializesModels;

    public mixed $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        //
        $this->request = $request;
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
