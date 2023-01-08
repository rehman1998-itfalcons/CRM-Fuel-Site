<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('parent_account');
            $table->string('opening_date');
            $table->string('account_type');
            $table->string('bank_name');
            $table->string('opening_balance');
            $table->string('description');
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
        Schema::dropIfExists('account_models');
    }
}
