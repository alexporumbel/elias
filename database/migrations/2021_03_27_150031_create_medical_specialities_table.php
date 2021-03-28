<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalSpecialitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_specialities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('is_paid')->default(0);

            $table->foreign('id')
                ->references('speciality_id')
                ->on('user_settings')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_specialities');
    }
}
