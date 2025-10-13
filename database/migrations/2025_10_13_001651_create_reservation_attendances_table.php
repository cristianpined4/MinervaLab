<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservation_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reservation');
            $table->string('carnet', 50)->nullable();
            $table->timestamp('date')->useCurrent();
            $table->timestamp('attendance')->nullable();

            $table->foreign('id_reservation')->references('id')->on('reservation')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_attendance');
    }
};
