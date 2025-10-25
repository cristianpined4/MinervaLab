<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scene', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_scene_category');
            $table->string('description', 255)->nullable();
            $table->decimal('duration', 5, 2)->default(15);
            $table->string('resource_demo', 255)->nullable();
            $table->timestamps();
            $table->foreign('id_scene_category')->references('id')->on('scene_category')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scene');
    }
};
