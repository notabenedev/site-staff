<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_employees', function (Blueprint $table) {
            $table->id();

            $table->string("title");
            $table->string("slug")
                ->unique();

            $table->string('main_image')
                ->nullable();

            $table->tinyText("short")
                ->nullable();

            $table->longText("description")
                ->nullable();

            $table->longText("comment")
                ->nullable();

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
        Schema::dropIfExists('staff_employees');
    }
}
