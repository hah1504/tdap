<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class att_machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ip','port'
    ];
}
