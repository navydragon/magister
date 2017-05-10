<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachinePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('machine_type_id')->unsigned();
            $table->integer('part_type_id')->unsigned();
            $table->timestamps();

            $table->foreign('machine_type_id')
                  ->references('id')
                  ->on('machine_types')
                  ->onDelete('cascade');

            $table->foreign('part_type_id')
                  ->references('id')
                  ->on('part_types')
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
        Schema::dropIfExists('machine_parts');
    }
}
