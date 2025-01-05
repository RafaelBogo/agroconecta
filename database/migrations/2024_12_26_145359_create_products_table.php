<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do produto
            $table->text('description'); // Descrição do produto
            $table->decimal('price', 8, 2); // Preço
            $table->string('city'); // Cidade
            $table->integer('quantity'); // Quantidade disponível
            $table->string('unit'); // Unidade de medida
            $table->date('validity'); // Validade
            $table->string('contact'); // Contato (telefone)
            $table->string('address'); // Endereço
            $table->string('photo')->nullable(); // Caminho para a foto do produto
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Referência ao usuário
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
