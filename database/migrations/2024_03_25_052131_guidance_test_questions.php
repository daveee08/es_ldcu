<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuidanceTestQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guidance_test_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('examid');
            $table->text('testquestion');
            $table->text('testoptions');
            $table->string('testanswer');
            $table->integer('testtype');
            $table->integer('points')->default(1);
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
        Schema::dropIfExists('guidance_test_questions');
    }
}
