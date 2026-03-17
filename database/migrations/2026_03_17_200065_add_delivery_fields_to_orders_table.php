<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_type')->nullable()->after('delivery_date');
            $table->string('delivery_time')->nullable()->after('delivery_type');
            $table->text('delivery_notes')->nullable()->after('delivery_time');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('delivery_notes');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_type', 'delivery_time', 'delivery_notes', 'shipping_cost']);
        });
    }
};
