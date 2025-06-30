<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuidancePassingRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guidance_passing_rate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->integer('acadprog_id');
            $table->string('gradelevel');
            $table->tinyInteger('isactive')->default(0);
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
        Schema::dropIfExists('guidance_passing_rate');
    }
}
