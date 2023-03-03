<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 
        'product_name',
        'image',
        'selling_price',

        'manufacturer',
        'Processor',
        'ram',
        'connectivity',
        'hard_drive',
        'hard_drive_size',
        'hard_drive_byte',
        'os',
        'os_version'
    ];
}
