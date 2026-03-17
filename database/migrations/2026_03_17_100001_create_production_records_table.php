<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('production_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('hen_batch_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('qty_morning')->default(0);
            $table->integer('qty_afternoon')->default(0);
            $table->integer('qty_total')->default(0);
            $table->integer('broken')->default(0);
            $table->integer('net_production')->default(0);
            $table->string('quality_grade')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'hen_batch_id', 'date']);
            $table->index(['tenant_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_records');
    }
};
