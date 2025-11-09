<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_mantenaince', function (Blueprint $table) {
            $table->id();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedBigInteger('id_room');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('id_room')->references('id')->on('room')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_mantenaince');
    }
};
