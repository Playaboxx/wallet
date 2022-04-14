<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vips', function (Blueprint $table) {
            $table->id();
            $table->string('rank');
            $table->decimal('livecasinorebates', 5, 4)->default('0');
            $table->decimal('sportsbookrebates', 5, 4)->default('0');
            $table->decimal('slotsrebate', 5, 4)->default('0');
            $table->integer('birthdaybonus')->default('0');
            $table->integer('upgradebonus')->default('0');
            $table->integer('withdrawalfrequency')->default('1');
            $table->decimal('withdrawalamount', 10, 2)->default('0');
            $table->string('withdrawalchannels');
            $table->decimal('amount', 10, 2)->default('0');
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
        Schema::dropIfExists('vips');
    }
}
