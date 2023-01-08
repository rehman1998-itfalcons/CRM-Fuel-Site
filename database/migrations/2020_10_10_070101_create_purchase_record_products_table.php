<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRecordProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_record_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_record_id');
            $table->integer('product_id');
            $table->double('qty');
            $table->double('rate');
            $table->double('gst');
            $table->double('sub_amount');
            $table->double('gst_amount');
            $table->double('total_amount');
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
        Schema::dropIfExists('purchase_record_products');
    }
}
