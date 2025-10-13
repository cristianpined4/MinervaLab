<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->enum('resource_type', ['video', 'image', 'article'])->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('path', 255)->nullable();
            $table->timestamp('date')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
