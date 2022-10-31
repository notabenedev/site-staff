<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_departments', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("slug")
                ->unique();
            $table->tinyText('short')
                ->nullable();
            $table->unsignedBigInteger('parent_id')
                ->nullable()
                ->comment('Родительский департамент');
            $table->unsignedBigInteger("priority")
                ->default(0)
                ->comment('Приоритет');
            $table->dateTime("published_at")
                ->nullable()
                ->comment('Дата публикации');
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
        Schema::dropIfExists('staff_departments');
    }
}
