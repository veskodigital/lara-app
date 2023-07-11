<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UninstallLaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $arrTablesToRemove = [];
        if (Schema::hasTable('la_app_requests')) {
            $arrTablesToRemove[] = 'la_app_requests';
        }
        if (Schema::hasTable('la_user_devices')) {
            $arrTablesToRemove[] = 'la_user_devices';
        }
        if (Schema::hasTable('la_users')) {
            $arrTablesToRemove[] = 'la_users';
        }

        if (empty($arrTablesToRemove)) {
            return;
        }

        foreach ($arrTablesToRemove as $table) {
            Schema::dropIfExists($table);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
