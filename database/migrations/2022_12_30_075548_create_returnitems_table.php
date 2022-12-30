<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returnitems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('return_no'); 
            $table->string('invoiceNumber');
            $table->string('serialNo');
            $table->integer('product_id');
            $table->string('product_name');
            $table->string('created_by'); 
            $table->string('remarks')->nullable(); 
            $table->string('status'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('returnitems');
    }
}
