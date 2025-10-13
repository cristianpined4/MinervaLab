<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reservation');
            $table->unsignedBigInteger('id_scene');
            $table->integer('count_sessions')->default(1);

            $table->foreign('id_reservation')->references('id')->on('reservation')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_scene')->references('id')->on('scene')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_details');
    }
};
