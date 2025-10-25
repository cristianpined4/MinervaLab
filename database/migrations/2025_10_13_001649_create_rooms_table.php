<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room', function (Blueprint $table) {
            $table->id();
            $table->string('description', 255)->nullable();
            $table->integer('vr_glasses')->default(0);
            $table->integer('max_students')->default(0);
            $table->enum('status', ['available', 'unavailable', 'maintenance'])->default('available');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room');
    }
};
