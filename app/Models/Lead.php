<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = ['external_id',
        'dt_reception', 'dt_assignment','dt_last_update','dt_activation', 'dt_enrollment','dt_payment', 'course_id', 'student_first_name', 
        'student_last_name', 'student_mobile', 'student_email', 'student_dt_birth',
        'student_laboral_situation', 'province_id', 'student_email', 'country_id',
        'lead_status_id','lead_sub_status_id', 'dt_call_reminder','lead_type_id','observations', 
        'prev_agent_id', 'agent_id','original_agent_id','leads_origin_id','int_observ'
    ];
}
