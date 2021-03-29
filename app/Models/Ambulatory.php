<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulatory extends Model
{
    use HasFactory;

    public function medic()
    {
        return $this->belongsTo(User::class, 'user_provider_id');
    }

    public function medicprofile()
    {
        return $this->belongsTo(UserSettings::class, 'speciality_id');
    }
}
