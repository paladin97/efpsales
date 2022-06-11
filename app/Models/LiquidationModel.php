<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidationModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','company_id','description','base_salary','enroll_commission',
        'enroll_bonus_commission','enroll_delegation','created_at','updated_at'
    ];
}
