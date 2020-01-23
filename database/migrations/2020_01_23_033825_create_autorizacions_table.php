<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutorizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblautorizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->text('fvcdescripcion');
            $table->date('fvcfechaAutorizacion');
            $table->string('fvcestado', 50);
            $table->unsignedInteger('fvcusuario_id');
            $table->unsignedInteger('fvctipoautorizacion_id');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            $table->foreign('fvctipoautorizacion_id')->references('id')->on('tbltipoautorizacion');
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
        Schema::dropIfExists('tblautorizacion');
    }
}
