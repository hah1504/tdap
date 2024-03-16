<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
    use HasFactory;

    protected $fillable = [
        'date','emp_id','l_type'
    ];

    public function emp()
    {
        return $this->hasOne(Employee::class,'attendance_machine_id','emp_id');
    }
}
