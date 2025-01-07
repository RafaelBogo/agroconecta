<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID do usuário que fez o pedido
            $table->unsignedBigInteger('product_id'); // ID do produto comprado
            $table->integer('quantity'); // Quantidade comprada
            $table->decimal('total_price', 10, 2); // Preço total
            $table->enum('status', ['Processando', 'Retirado', 'Cancelado'])->default('Processando'); // Status do pedido
            $table->timestamps();

            // Relacionamentos
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
