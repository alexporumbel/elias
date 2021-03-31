<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoverySeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'series',
        'start_date',
        'end_date',
        'capacity',
    ];
}
