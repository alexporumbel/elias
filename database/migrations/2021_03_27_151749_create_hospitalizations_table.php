<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitalizations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_datetime');
            $table->string('name');
            $table->string('lname');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('user_provider_id');
            $table->string('appointment_type');
            $table->string('hospitalization_type');
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('hospitalizations');
    }
}
