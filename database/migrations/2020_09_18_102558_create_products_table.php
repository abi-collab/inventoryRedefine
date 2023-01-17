<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id');
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('root')->nullable();
            $table->string('buying_price')->nullable();
            $table->string('selling_price');
            $table->integer('supplier_id')->nullable();
            $table->string('buying_date')->nullable();
            $table->string('image')->nullable();
            $table->string('product_quantity')->nullable();

            $table->string('manufacturer')->nullable();
            $table->string('connectivity')->nullable();
            $table->string('Processor')->nullable();
            $table->integer('ram')->nullable();
            $table->string('hard_drive')->nullable();
            $table->integer('hard_drive_size')->nullable();
            $table->string('hard_drive_byte')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
