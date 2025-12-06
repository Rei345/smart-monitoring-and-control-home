<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActuatorLog extends Model
{
    protected $fillable = [
        'device_name', 
        'status', 
        'trigger_source'
    ];
}
