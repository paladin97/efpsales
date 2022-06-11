<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInf extends Model
{
    use HasFactory;
    protected $table = 'people_inf';
    protected $fillable = ['name', 'last_name','legal_name', 'gender', 'dt_birth', 'country_id',
                           'previous_studies', 'marital_status', 'has_work', 'mail',
                           'studies','profession','phone','deletegate_id',
                           'mobile', 'dni', 'cif', 'address', 'town', 'province_id', 'postal_code',
                           'bank_id', 'bank_account', 'salary', 'person_type_id','income_range_id','study_id', 'company_id',
                           'contract_type_id','ss_number','created_at','updated_at,liquidation_model_id'];
}
