<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('document_type_id');
            $table->string('document_name');
            $table->integer('document_issuedby');
            $table->string('document_remarks')->nullable();
            $table->string('document_viewers')->nullable();
            $table->string('document_status')->nullable();
            $table->tinyInteger('document_deleted')->default(0);
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
        Schema::dropIfExists('document_tracking');
    }
}
