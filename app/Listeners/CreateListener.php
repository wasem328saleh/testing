<?php

namespace App\Listeners;

use App\Jobs\TraslateJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateListener
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
    public function handle(object $event): void
    {
        switch ($event->FileName){
            case 'name':{
                TraslateJob::dispatch($event->object,$event->object->name,$event->FileName,"store");

            }
            case 'title':{
                TraslateJob::dispatch($event->object,$event->object->title,$event->FileName,"store");
            }
            case 'key':{
                TraslateJob::dispatch($event->object,$event->object->key,$event->FileName,"store");
            }
            default:
        }

    }
}
