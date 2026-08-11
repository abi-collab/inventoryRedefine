<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialNumber extends Model
{
    protected $table = 'serialnumbers';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
