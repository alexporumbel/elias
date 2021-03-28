<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmbulatoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ambulatories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('name');
            $table->string('lname');
            $table->string('email');
            $table->string('phone');
            $table->string('appointment_type');
            $table->string('notes');
            $table->unsignedBigInteger('user_provider_id');
            $table->unsignedBigInteger('speciality_id');
            $table->timestamps();

            $table->foreign('user_provider_id')->references('id')->on('users');
            $table->foreign('speciality_id')->references('id')->on('medical_specialities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ambulatories');
    }
}
