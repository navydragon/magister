<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOurMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tabnum');
            $table->integer('machine_type_id')->unsigned();
            $table->integer('driver_id')->unsigned();
            $table->timestamps();

            $table->foreign('machine_type_id')
                  ->references('id')
                  ->on('machine_types')
                  ->onDelete('cascade');

            $table->foreign('driver_id')
                  ->references('id')
                  ->on('drivers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('our_machines');
    }
}
