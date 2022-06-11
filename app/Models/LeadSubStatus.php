<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadSubStatus extends Model
{
    use HasFactory;
    protected $table = 'lead_sub_status';

    protected $fillable = [
        'name','lead_status_id','color_class'
    ];
}
