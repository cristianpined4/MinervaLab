<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vr_mantenaince', function (Blueprint $table) {
            $table->id();
            $table->integer('count')->default(0);
            $table->enum('status', ['ongoing', 'finished'])->default('ongoing');
            $table->timestamp('date')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vr_mantenaince');
    }
};
