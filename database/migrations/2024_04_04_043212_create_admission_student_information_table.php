<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionStudentInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_student_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('suffix')->nullable();
            $table->date('dob');
            $table->integer('sy');
            $table->string('pob')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->integer('age');
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->string('present_address')->nullable();
            $table->string('houseno')->nullable();
            $table->string('street')->nullable();
            $table->string('brgy')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('last_school_attended')->nullable();
            $table->string('last_grade_level_completed')->nullable();
            $table->string('school_contact_number')->nullable();
            $table->string('last_school_mailing_address')->nullable();
            $table->string('poolingnumber');
            $table->unsignedBigInteger('acadprog_id');
            $table->unsignedBigInteger('gradelevel_id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('fitted_course_id')->nullable();
            $table->decimal('jhs_gwa', 5, 2)->nullable();
            $table->decimal('shs_gwa', 5, 2)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('deleted')->default(0);
            $table->boolean('probation')->default(0);
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
        Schema::dropIfExists('admission_student_information');
    }
}
