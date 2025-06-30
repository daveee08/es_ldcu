<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTrackingHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_tracking_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('document_tracking_id');
            $table->integer('forwarded_by')->nullable();
            $table->integer('forwarded_to')->nullable();
            $table->string('forwarddate')->nullable();
            $table->tinyInteger('received')->default(0);
            $table->string('receiveddate')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('document_tracking_history');
    }
}
