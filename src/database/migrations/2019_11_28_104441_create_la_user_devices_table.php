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
            $table->id();
            $table->foreignIdFor(\VeskoDigital\LaraApp\Models\LaUser::class)->constrained();
            $table->text('uuid');
            $table->string('name');
            $table->string('display_name');
            $table->string('version');
            $table->string('app_version')->nullable();
            $table->text('push_token')->nullable();
            $table->json('push_settings')->nullable();
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
