<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_id', 'user_id','lead_status_id','lead_sub_status_id', 
        'dt_call_reminder','sent_method', 'observation'
    ];
}
