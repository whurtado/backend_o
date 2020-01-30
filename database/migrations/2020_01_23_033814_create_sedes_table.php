<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblsede', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fvcnombre', 100);
            $table->string('fvcestado', 50);
            $table->unsignedBigInteger('fvcusuario_id');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
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
        Schema::dropIfExists('tblsede');
    }
}
