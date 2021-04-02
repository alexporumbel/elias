<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    public $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function speciality()
    {
        return $this->belongsTo(MedicalSpeciality::class, 'speciality_id');
    }

    public function is_admin()
    {
        if($this->is_admin == 1){
            return true;
        }else{
            return false;
        }
    }


}
