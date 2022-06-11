<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'contract_id', 'user_id','management_note_category_id', 
        'dt_reminder','contact_type', 'contact_method','observations','path'
    ];
}
