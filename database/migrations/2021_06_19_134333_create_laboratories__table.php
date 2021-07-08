<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratories_', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('licence');
            $table->string('licence_folio');
            $table->string('procedure');
            $table->string('rfc');
            $table->string('taxpayer');
            $table->string('risk');
            $table->date('last_predial_payment');
            $table->string('siresol_folio');
            $table->date('last_siresol_payment');
            $table->string('land_use');
            $table->string('land_use_folio');
            $table->string('land_use_type');
            $table->date('land_use_vigency');
            $table->string('commercial_business');
            $table->string('specific_activity');
            $table->string('digital_file_folio');
            $table->date('expiration_ocuppation');
            $table->string('proof_of_ownership');
            $table->string('civil_protection');
            $table->date('civil_protection_vigency');
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
        Schema::dropIfExists('laboratories_');
    }
}
