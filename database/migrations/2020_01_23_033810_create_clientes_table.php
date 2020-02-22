<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::create('clientes', function (Blueprint $table) {
             $table->increments('id');
             $table->timestamps();
         });*/

        Schema::create('tblcliente', function (Blueprint $table) {
            $table->increments('id');

            $table->string('fvcprimernombre', 50);
            $table->string('fvcsegundonombre', 50);
            $table->string('fvcprimerapellido', 50);
            $table->string('fvcsegundoapellido', 50);
            $table->string('fvcdocumento', 25);
            $table->string('fvcdireccion', 100);
            $table->string('fvccelular', 25);
            $table->string('fvctelefono',25);
            /* $table->string('fvcreferencia',50)->nullable();
             $table->string('fvctelefonoref',25);*/
            $table->string('fvctelefonoo',50);
            $table->string('fvcobservacion',100)->nullable();
            $table->string('fvcdirecciontrabajo',100);

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('fvcfechacumpleano');
            $table->string('fvcestado',20)->nullable();
            $table->integer('fvcsede_creacion');

            /*$table->string('fvcreferencia2',50);
            $table->string('fvctelefonoref2',50);*/

            $table->unsignedBigInteger('fvcusuario_id');
            $table->foreign('fvcusuario_id')->references('id')->on('users');
            $table->integer('clienteestado_id')->unsigned();

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
        Schema::dropIfExists('tblcliente');
    }
}
