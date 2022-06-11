<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id','user_id','title','start','end','allDay','color','text_color','type_id'
    ];
}
