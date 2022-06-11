<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menssage extends Model
{
    protected $table = 'chats_menssages';
 //   protected $fillable = ['contens','uer_send_id','user_receive_id'];


 public function getMenssages($send , $recive){

     $menssages = Menssage::where([
        ['user_send_id', '=', $send],
        ['user_receive_id', '=', $recive],

    ])->orWhere([
        ['user_send_id', '=', $recive],
        ['user_receive_id', '=', $send],

    ])->get();

    return $menssages;
 }

}
