<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serials extends Model
{
    protected $fillable = [
        'invoiceNumber', 'serialNo', 'customerId', 'customerName', 'product_id', 'product_name', 'order_quantity', 'product_price', 'created_by', 'category_id'
    ];
}
