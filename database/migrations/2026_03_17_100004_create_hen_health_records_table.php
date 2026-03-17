<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('hen_health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('hen_batch_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->date('date');
            $table->string('medication')->nullable();
            $table->string('dosage')->nullable();
            $table->date('next_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'hen_batch_id']);
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'next_due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hen_health_records');
    }
};
