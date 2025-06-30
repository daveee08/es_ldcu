<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GuidanceCoursePercentage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guidance_course_percentage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('passing_rate_setup_id');
            $table->integer('courseid');
            // $table->string('category_name');
            // $table->decimal('percentage');
            $table->decimal('general_percentage');
            $table->tinyInteger('isprovision')->default(0);
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
        Schema::dropIfExists('guidance_course_percentage');
    }
}
