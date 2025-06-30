<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuidanceExamdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guidance_examdate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('schoolyear');
            $table->string('examinationdate');
            $table->string('starttime');
            $table->integer('takers')->default(0);
            $table->string('venue');
            $table->integer('acadprog');
            $table->integer('examid');
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
        Schema::dropIfExists('guidance_examdate');
    }
}
