<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('supplier_company_id');
            $table->integer('category_id');
            $table->string('invoice_no');
            $table->string('purchase_no');
            $table->string('datetime');
            $table->string('load_number');
            $table->string('order_number');
            $table->string('gst_status');
            $table->tinyInteger('status');
            $table->tinyInteger('match_status');
            $table->string('purchaseinvoices');
            $table->double('total_quantity');
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
        Schema::dropIfExists('purchase_records');
    }
}
