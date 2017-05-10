<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOurMachinePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_machine_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('our_machine_id')->unsigned();
            $table->integer('machine_part_id')->unsigned();
            $table->integer('part_id')->unsigned();
            $table->integer('init_time');
            $table->timestamps();

            $table->foreign('our_machine_id')
                  ->references('id')
                  ->on('our_machines')
                  ->onDelete('cascade');
            $table->foreign('machine_part_id')
                  ->references('id')
                  ->on('machine_parts')
                  ->onDelete('cascade');
            $table->foreign('part_id')
                  ->references('id')
                  ->on('parts')
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
        Schema::dropIfExists('our_machine_parts');
    }
}
