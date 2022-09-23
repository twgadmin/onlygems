<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id')->nullable();
			$table->integer('order_id')->nullable();
            $table->string('name')->nullable();
            $table->string('grading_co')->nullable();
            $table->string('grading_co_serial_number')->nullable();
            $table->string('year')->nullable();
            $table->string('set')->nullable();
            $table->string('card')->nullable();
            $table->string('parralel')->nullable();
            $table->string('grade')->nullable();
            $table->string('category')->nullable();
            $table->string('estimated_value')->nullable();
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
        Schema::dropIfExists('order_details');
    }
}
