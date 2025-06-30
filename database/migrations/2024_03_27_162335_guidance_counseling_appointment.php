<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuidanceCounselingAppointment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guidance_counseling_appointment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('studid');
            $table->string('studname');
            $table->string('filleddate');
            $table->string('counselingdate');
            $table->string('processingofficer');
            $table->text('reason');
            $table->integer('counselor');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('guidance_counseling_appointment');
    }
}
