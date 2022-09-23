<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('internal_serial_number')->nullable()->change();
            $table->string('cardhedger_internal_serial_number')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('grading_co')->nullable()->change();
            $table->string('grading_co_serial_number')->nullable()->change();
            $table->string('year')->nullable()->change();
            $table->string('set')->nullable()->change();
            $table->string('card')->nullable()->change();
            $table->string('parralel')->nullable()->change();
            $table->string('grade')->nullable()->change();
            $table->string('category')->nullable()->change();
            $table->text('description')->after('category')->nullable();
            $table->text('image')->after('category')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
