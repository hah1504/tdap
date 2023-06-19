<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'created_by','modified_by','attendance_machine_id','desgination','status'
    ];

    public function empDesignation()
    {
        return $this->hasOne(Designation::class,'id','desgination');
    }
}
