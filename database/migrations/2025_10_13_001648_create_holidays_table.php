<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('holiday', function (Blueprint $table) {
            $table->id();
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->string('description',80);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holiday');
    }
};
