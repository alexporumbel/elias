<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalSpeciality extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name', 'is_paid'];

    public function doctors()
    {
        return $this->belongsTo(UserSettings::class, 'speciality_id');
    }
}
