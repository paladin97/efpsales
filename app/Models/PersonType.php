<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonType extends Model
{
    use HasFactory;

    public function persons_inf(){

        return $this->hasMany(PersonInf::class);
    }
}
