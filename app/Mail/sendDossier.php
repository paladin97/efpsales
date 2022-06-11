<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendDossier extends Mailable
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
        //  dd($this->data['attachment']);
     }
 
     /**
      * Build the message.
      *
      * @return $this
      */
     public function build()
     {
         
         return $this->view('mails.senddossier')
             ->with(['data', $this->data])
             ->from('no-responder@efpsales.com', 'grupoƎFP')
             ->attach($this->data['attachment'])
             ->subject('Bienvenido a grupoƎFP | Construimos tu futuro'); 
     }
}
