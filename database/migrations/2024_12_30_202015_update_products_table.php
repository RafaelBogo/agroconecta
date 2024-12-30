<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'city')) {
                $table->string('city')->after('price')->nullable(); // Adiciona a coluna 'city'
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'city')) {
                $table->dropColumn('city'); // Remove a coluna 'city'
            }
        });
    }
};
