<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Readings extends Model
{
    use HasFactory;

    protected $table = 'readings';
    protected $fillable = [
        'readingsID',
        'locationID',
        'water_level',
        'timestamp',
        "status",
    ];

}
