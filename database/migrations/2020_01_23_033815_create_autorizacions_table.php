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
            $table->unsignedBigInteger('fvcusuario_id');
            $table->unsignedBigInteger('fvctipoautorizacion_id');
            $table->integer('fvcsede_creacion');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            //$table->foreign('fvctipoautorizacion_id')->references('id')->on('tbltipoautorizacion');
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
