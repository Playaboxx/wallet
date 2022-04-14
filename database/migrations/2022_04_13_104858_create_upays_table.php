<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upays', function (Blueprint $table) {
            $table->id();
            $table->integer('merchantId')->unique();
            $table->string('apiKey');
            $table->string('referenceId')->unique();
            $table->text('itemDesc');
            $table->integer('status');
            $table->text('statusDesc');
            $table->text('declineReason');
            $table->integer('transactionId')->unique();
            $table->string('transactionDesc');
            $table->double('amount');
            $table->double('transactionAmount');
            $table->string('currency');
            $table->string('designatedBank');
            $table->bigInteger('designatedAccountNo');
            $table->string('designatedAccountName');
            $table->bigInteger('srcAccountNo');
            $table->string('bankRef')->unique();
            $table->date('requestDate');
            $table->date('transactionDate');
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
        Schema::dropIfExists('upays');
    }
}
