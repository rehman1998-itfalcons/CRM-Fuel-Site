<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('sub_company_id');
            $table->integer('supplier_company_id');
            $table->string('datetime');
            $table->string('load_number');
            $table->string('order_number');
            $table->string('splitfullload');
            $table->double('total_without_gst');
            $table->double('gst');
            $table->string('gst_status');
            $table->tinyInteger('split_load_status');
            $table->double('split_load_charges');
            $table->string('split_load_des');
            $table->double('total_amount');
            $table->double('paid_amount');
            $table->tinyInteger('supervisor_status');
            $table->tinyInteger('invoice_no');
            $table->string('invoice_number');
            $table->text('delivery_docket');
            $table->text('bill_of_lading');
            $table->tinyInteger('status');
            $table->tinyInteger('email_status');
            $table->tinyInteger('paid_status');
            $table->tinyInteger('mass_match_status');
            $table->tinyInteger('deleted_status');
            $table->string('cancel_reason');
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
        Schema::dropIfExists('records');
    }
}
