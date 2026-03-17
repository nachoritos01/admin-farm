<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('piece');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'supplier_id']);
            $table->index(['tenant_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
