<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->primary('user_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('speciality_id')->nullable();
            $table->tinyInteger('is_admin')->default(0);
            $table->text('working_plan')->nullable();
            $table->string('mapimage')->nullable();
            $table->text('mapdescription')->nullable();
            //$table->tinyInteger('status')->default(1);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('user_settings');
    }
}
