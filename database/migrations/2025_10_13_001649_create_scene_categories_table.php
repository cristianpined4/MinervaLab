<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scene_category', function (Blueprint $table) {
            $table->id();
            $table->string('description', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->string('icon', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scene_category');
    }
};
