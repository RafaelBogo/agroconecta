<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveQuantityFromProductsTable extends Migration
{
    /**
     * Execute as alterações no banco de dados.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('quantity'); // Remove a coluna 'quantity'
        });
    }

    /**
     * Reverte as alterações no banco de dados.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->nullable(); // Adiciona a coluna 'quantity' de volta
        });
    }
}
