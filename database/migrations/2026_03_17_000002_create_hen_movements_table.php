<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('hen_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('hen_batch_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->integer('quantity');
            $table->date('date');
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'hen_batch_id']);
            $table->index(['hen_batch_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hen_movements');
    }
};
