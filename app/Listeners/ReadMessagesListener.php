<?php

namespace App\Listeners;

use App\Events\ReadMessagesEvent;
use App\Models\Menssage ;
use App\Models\Notify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReadMessagesListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ReadMessagesEvent  $event
     * @return void
     */
    public function handle(ReadMessagesEvent $event)
    {
        //

        if($event->id_2 == null){

            Menssage::where('user_receive_id',$event->id_1)
            ->where('status_id',1)
            ->update(['status_id'=>2,]);
            
            Notify::where('user_receive_id',$event->id_1)->update(['count_notify'=>0]);

        }else{

            Notify::where('user_receive_id',$event->id_1)
            ->where('user_send_id',$event->id_2)
            ->update(['count_notify'=>0]);

            Menssage::where('user_receive_id',$event->id_1)
            ->where('user_send_id',$event->id_2)
            ->where('status_id',1)
            ->update(['status_id'=>2]);
        }

    }
}
