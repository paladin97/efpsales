<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','company_id','type_id','area_id','pvp','tax_amount','duration','program','dossier_path'
    ];
}
