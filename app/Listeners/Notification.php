<?php

namespace App\Listeners;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class Notification implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    use InteractsWithQueue;

    public $tries = 5;

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return 'notifications';
    }

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
}
