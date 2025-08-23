<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'unit'))     $table->string('unit')->nullable();
            if (!Schema::hasColumn('products', 'quantity')) $table->integer('quantity')->nullable();
            if (!Schema::hasColumn('products', 'validity')) $table->date('validity')->nullable();
            if (!Schema::hasColumn('products', 'contact'))  $table->string('contact')->nullable();
            if (!Schema::hasColumn('products', 'address'))  $table->text('address')->nullable();
            if (!Schema::hasColumn('products', 'photo'))    $table->string('photo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'photo'))    $table->dropColumn('photo');
            if (Schema::hasColumn('products', 'address'))  $table->dropColumn('address');
            if (Schema::hasColumn('products', 'contact'))  $table->dropColumn('contact');
            if (Schema::hasColumn('products', 'validity')) $table->dropColumn('validity');
            if (Schema::hasColumn('products', 'quantity')) $table->dropColumn('quantity');
            if (Schema::hasColumn('products', 'unit'))     $table->dropColumn('unit');
        });
    }
};
