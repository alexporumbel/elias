<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospitalization extends Model
{
    use HasFactory;

    protected $fillable = ['name',
        'lname',
        'email',
        'phone',
        'speciality_id',
        'user_provider_id',
        'appointment_type',
        'hospitalization_type',
        'start_datetime',];

    public function medic()
    {
        return $this->belongsTo(User::class, 'user_provider_id');
    }

}
