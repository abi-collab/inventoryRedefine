<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Returnitem extends Model
{
    protected $fillable = [
        'return_no', 'invoiceNumber', 'serialNo', 'product_id', 'product_name', 'created_by', 'remarks', 'status'
    ];

}
