<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cliente');
            $table->string('produto');
            $table->longText('frase')->nullable();
            $table->decimal('valor', 15, 2)->nullable();
            $table->string('pago');
            $table->string('entregue')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
