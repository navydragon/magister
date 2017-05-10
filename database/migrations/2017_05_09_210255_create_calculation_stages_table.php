<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalculationStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculation_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calculation_id')->unsigned()->index();
            $table->foreign('calculation_id')->references('id')->on('calculations')->onDelete('cascade');
            $table->integer('stage_num');
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
        Schema::dropIfExists('calculation_stages');
    }
}
