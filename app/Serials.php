<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serials extends Model
{
    protected $fillable = [
        'invoiceNumber', 'serialNo', 'customerId', 'product_id', 'product_name', 'product_quantity', 'product_price'
    ];
}
