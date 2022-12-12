<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('invoiceNumber');
            $table->string('serialNo');
            $table->integer('customerId');
            $table->integer('product_id');
            $table->string('product_name');
            $table->string('product_quantity');   
            $table->string('product_price'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serials');
    }
}
