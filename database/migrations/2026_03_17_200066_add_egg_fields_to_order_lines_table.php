<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('order_lines', function (Blueprint $table) {
            $table->string('egg_size')->nullable()->after('variant');
            $table->string('unit_type')->nullable()->after('egg_size');
        });
    }

    public function down(): void
    {
        Schema::table('order_lines', function (Blueprint $table) {
            $table->dropColumn(['egg_size', 'unit_type']);
        });
    }
};
