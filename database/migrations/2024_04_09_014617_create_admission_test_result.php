<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionTestResult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_test_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('studid');
            $table->integer('examid');
            $table->integer('score')->default(0);
            $table->string('starttime')->nullable();
            $table->string('finishtime')->nullable();
            $table->tinyInteger('deleted')->default(0);
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
        Schema::dropIfExists('admission_test_result');
    }
}
