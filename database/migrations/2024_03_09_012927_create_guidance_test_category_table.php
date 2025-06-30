<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidanceTestCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guidance_test_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('passing_rate_setup_id');
            $table->string('category_name');
            $table->decimal('category_percent', 5, 2)->default(0);
            $table->integer('category_timelimit')->default(0);
            $table->integer('category_timelimit_hrs')->default(0);
            $table->integer('category_timelimit_min')->default(0);
            $table->boolean('category_hastest')->default(true);
            $table->tinyInteger('category_deleted')->default(0);
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
        Schema::dropIfExists('guidance_test_category');
    }
}
