<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersDetailToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique();
            $table->string('nickname')->unique();
            $table->string('phone')->unique();
            $table->decimal('balance', 10, 2)->default('0');
            $table->date('birth');
            $table->integer('status')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('nickname');
            $table->dropColumn('phone');
            $table->dropColumn('balance');
            $table->dropColumn('birth');
            $table->dropColumn('status');
        });
    }
}
