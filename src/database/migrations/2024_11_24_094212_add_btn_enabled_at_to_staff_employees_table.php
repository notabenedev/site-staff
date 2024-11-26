<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBtnEnabledAtToStaffEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_employees', function (Blueprint $table) {
            $table->boolean("btn_enabled")
                ->nullable()
                ->comment("Доступна кнопка записи");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_employees', function (Blueprint $table) {
            $table->dropColumn("btn_enabled");
        });
    }
}
