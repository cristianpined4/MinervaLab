<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vr_mantenaince', function (Blueprint $table) {
            $table->id();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('id_vr');
            $table->foreign('id')->references('id')->on('vr_glasses')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vr_mantenaince');
    }
};
