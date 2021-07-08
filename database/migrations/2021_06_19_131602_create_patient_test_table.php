<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_test', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('test_id');
            $table->uuid('folio')->index();
            $table->string('outcome');
            $table->string('reference');
            $table->string('result');
            $table->boolean('send_report')->default(0);
            $table->boolean('delivered')->default(0);
            $table->timestamps();
            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');
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
        Schema::dropIfExists('patient_test');
    }
}
