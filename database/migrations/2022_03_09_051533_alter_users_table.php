<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->integer('email_code')->nullable()->after('token');
            $table->integer('sms_code')->nullable()->after('email_code');
            $table->string('walletid')->nullable()->after('sms_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('phone_verified_at');
            $table->dropColumn('email_code');
            $table->dropColumn('sms_code');
            $table->dropColumn('walletid');
        });

    }
}
