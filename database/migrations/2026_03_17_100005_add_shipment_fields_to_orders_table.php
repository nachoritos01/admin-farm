<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('shipment_id')->nullable()->after('location_id')->constrained()->nullOnDelete();
            $table->date('delivery_date')->nullable()->after('estimated_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipment_id');
            $table->dropColumn('delivery_date');
        });
    }
};
