<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('la_user_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('la_user_id')->index();
            $table->foreign('la_user_id')->references('id')->on('la_app_users');
            $table->text('uuid');
            $table->string('name');
            $table->string('display_name');
            $table->string('version');
            $table->text('push_token')->nullable();
            $table->json('push_settings');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('la_user_devices');
    }
}
