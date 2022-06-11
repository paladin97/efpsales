<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name','description','mail','phone','cif','address','town','postal_code','leg_rep_nif','leg_rep_full_name','logo_path','bank_account','swift','ceo_signature_path'
        ,'url_facebook','url_instagram','url_website','url_business'
    ];
}
