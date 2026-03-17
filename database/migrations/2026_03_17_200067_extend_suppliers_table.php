<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->text('address')->nullable()->after('email');
            $table->jsonb('products')->nullable()->after('address');
            $table->integer('rating')->nullable()->after('products');
            $table->string('status')->default('active')->after('rating');
        });

        // Backfill status from is_active
        DB::statement("UPDATE suppliers SET status = CASE WHEN is_active THEN 'active' ELSE 'inactive' END");

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        DB::statement("UPDATE suppliers SET is_active = (status = 'active')");

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['address', 'products', 'rating', 'status']);
        });
    }
};
