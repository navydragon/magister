<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CalculationsStagesOurMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculation_stages_our_machines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calculation_stage_id')->unsigned()->index();
            $table->foreign('calculation_stage_id')->references('id')->on('calculation_stages')->onDelete('cascade');
            $table->integer('our_machine_id')->unsigned()->index();
            $table->foreign('our_machine_id')->references('id')->on('our_machines')->onDelete('cascade');
            $table->float('kiv');
            $table->float('moving_kmode');
            $table->float('rotation_kmode');
            //$table->float('kmode');
            //$table->integer('moving_perc');
            //$table->integer('rotation_perc');
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
        Schema::dropIfExists('calculation_stages_our_machines');
    }
}
