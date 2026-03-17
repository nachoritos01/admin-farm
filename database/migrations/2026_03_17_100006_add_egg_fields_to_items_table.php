<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('price');
            $table->string('egg_size')->nullable()->after('unit');
            $table->string('egg_quality')->nullable()->after('egg_size');
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('egg_quality');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['unit', 'egg_size', 'egg_quality', 'wholesale_price']);
        });
    }
};
