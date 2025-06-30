<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuidanceReferral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guidance_referral', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('studid');
            $table->string('studname');
            $table->string('filleddate');
            $table->string('counselingdate')->nullable();
            $table->integer('processingofficer')->nullable();
            $table->integer('counselor')->nullable();
            $table->integer('referredby');
            $table->integer('status')->default(0);
            $table->string('datereceived')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('guidance_referral');
    }
}
