<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = [
        'fan_temp_threshold', 
        'door_dist_threshold', 
        'schedule_open', 
        'schedule_close'
    ];
}
