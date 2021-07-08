<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('test_id');
            $table->uuid('folio')->index();
            $table->string('outcome');
            $table->string('reference');
            $table->boolean('result')->default(0);
            $table->boolean('send_report')->default(0);
            $table->boolean('delivered')->default(0);
            $table->timestamps();
            $table->foreign('test_id')
                ->references('id')
                ->on('tests')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
