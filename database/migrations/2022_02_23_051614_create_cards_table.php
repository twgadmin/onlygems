<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {

            $table->id();
            $table->string('internal_serial_number')->nullable();
            $table->string('cardhedger_internal_serial_number')->nullable();
            $table->string('name')->nullable();
            $table->string('grading_co')->nullable();
            $table->string('grading_co_serial_number')->nullable();
            $table->string('year')->nullable();
            $table->string('set')->nullable();
            $table->string('card')->nullable();
            $table->string('parralel')->nullable();
            $table->string('grade')->nullable();
            $table->string('category')->nullable();
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
        Schema::dropIfExists('cards');
    }
}
