<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSigneeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_signee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('document_tracking_id');
            $table->integer('userid');
            $table->string('name');
            $table->string('status')->nullable();
            $table->tinyInteger('deleted')->default(0);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('document_signee');
    }
}
