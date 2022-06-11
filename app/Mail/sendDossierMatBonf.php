<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendDossierMatBonf extends Mailable
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
         
         return $this->view('mails.senddossiermatbonf')
             ->with(['data', $this->data])
             ->from('no-responder@efpsales.com', 'grupoƎFP')
             ->attach($this->data['attachment'])
             ->attach($this->data['attachment1'])
             ->attach($this->data['attachment2'])
            //  ->attach($this->data['attachment3'])
             ->subject('Bienvenido a grupoƎFP | Construimos tu futuro'); 
     }
}
