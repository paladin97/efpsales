<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractState extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','short_name','description','color_class'
    ];
}
