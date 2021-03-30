<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulatory extends Model
{
    use HasFactory;

    protected $fillable = ['name',
        'lname',
        'email',
        'phone',
        'speciality_id',
        'user_provider_id',
        'appointment_type',
        'notes',
        'start_datetime',
        'end_datetime'];

    public function medic()
    {
        return $this->belongsTo(User::class, 'user_provider_id');
    }

    public function medicprofile()
    {
        return $this->belongsTo(UserSettings::class, 'speciality_id');
    }
}
