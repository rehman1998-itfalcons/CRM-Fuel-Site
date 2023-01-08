<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('record_id');
            $table->integer('product_id');
            $table->double('qty');
            $table->double('whole_sale');
            $table->double('discount');
            $table->double('delivery_rate');
            $table->double('brand_charges');
            $table->double('cost_of_credit');
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
        Schema::dropIfExists('record_products');
    }
}
