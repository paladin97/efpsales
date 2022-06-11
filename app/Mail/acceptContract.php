<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class acceptContract extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public $data;
     public function __construct($data)
     {
         $this->data = $data;
        //  dd($data[0]['client_name']);
     }
 
     /**
      * Build the message.
      *
      * @return $this
      */
     public function build()
     {
         
         return $this->view('mails.acceptcontract')
             ->with(['data', $this->data])
             ->from('no-responder@efpsales.com', 'grupoƎFP')
             ->subject('[NOTIFICACIÓN] Un Contrato ha sido aceptado'); 
     }
}
