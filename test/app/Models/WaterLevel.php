<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterLevel extends Model
{
    use HasFactory;

    protected $table = 'water_levels';
    protected $fillable = [
        'id',
        'water-level',
        'location',
        "location",
        "latitude",
        "longitude",
        "green",
        "blue" ,
        "red",
    ];
}
