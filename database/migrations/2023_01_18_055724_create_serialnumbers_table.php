<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerialnumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serialnumbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('serial_number');
            $table->integer('product_id');
            $table->integer('supplier_id');
            $table->string('status');
            $table->string('createdby');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serialnumbers');
    }
}
