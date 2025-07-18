<?php

namespace App\Listeners;

use App\Events\WinnerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SetWinnerJob;

class WinnerEventNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WinnerEvent $event): void
    {
        //
        dispatch(new SetWinnerJob($event->slotId))->afterCommit();
        //new SetWinnerJob($event->slotId)::dispatch()->afterCommit();

    }
}
