<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class serialnumbers extends Model
{
    protected $fillable = [
        'serial_number', 'product_id', 'supplier_id'
    ];
}
