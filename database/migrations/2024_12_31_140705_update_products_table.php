<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit')->nullable();         // Unidade de medida
            $table->integer('quantity')->nullable();    // Quantidade disponível
            $table->date('validity')->nullable();       // Validade
            $table->string('contact')->nullable();      // Contato do vendedor
            $table->text('address')->nullable();        // Endereço de retirada
            $table->string('photo')->nullable();        // Caminho da foto
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['unit', 'quantity', 'validity', 'contact', 'address', 'photo']);
        });
    }
}
