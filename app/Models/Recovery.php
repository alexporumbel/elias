<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recovery extends Model
{
    use HasFactory;

    protected $fillable = ['name',
        'lname',
        'email',
        'phone',
        'appointment_type',
        'start_date',
        'end_date',
        ];

}
