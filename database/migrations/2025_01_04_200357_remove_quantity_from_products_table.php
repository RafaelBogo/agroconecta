<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as alterações no banco de dados.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'quantity')) {
                $table->dropColumn('quantity'); // Remove a coluna se existir
            }
        });
    }

    /**
     * Reverte as alterações no banco de dados.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'quantity')) {
                $table->integer('quantity')->nullable(); // Recria a coluna se não existir
            }
        });
    }
};
