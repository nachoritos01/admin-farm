<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('hen_batches', function (Blueprint $table) {
            $table->date('acquisition_date')->nullable()->after('breed');
        });
    }

    public function down(): void
    {
        Schema::table('hen_batches', function (Blueprint $table) {
            $table->dropColumn('acquisition_date');
        });
    }
};
