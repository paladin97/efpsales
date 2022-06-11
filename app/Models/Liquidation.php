<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    use HasFactory;

    protected $fillable = ['agent_id','period_liq','status','company_id','signature_agent','positive_value','negative_value'];
}
