<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCompanyRateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_company_rate_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sub_company_id');
            $table->integer('product_id');
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
        Schema::dropIfExists('sub_company_rate_areas');
    }
}
