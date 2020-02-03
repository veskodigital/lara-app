<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaAppRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('la_app_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('device_id')->index();
            $table->foreign('device_id')->references('id')->on('la_user_devices');
            $table->string('request_type');
            $table->string('ip');
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
        Schema::dropIfExists('la_app_requests');
    }
}
