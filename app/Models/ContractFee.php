<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractFee extends Model
{
    use HasFactory;
    protected $fillable = ['fee_number','fee_value','fee_paid','dt_payment',
                            'dt_paid','status','fee_path','contract_id',
                            'reason_unpaid','observations','dt_unpaid','fee_unpaid_path'
                        ];
}
