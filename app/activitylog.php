<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activitylog extends Model
{
    protected $fillable = [
        'activity', 'createdby'
    ];
}
