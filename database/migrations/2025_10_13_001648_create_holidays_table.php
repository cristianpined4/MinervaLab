<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('holiday', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_calendar');
            $table->integer('day');
            $table->timestamps();
            $table->foreign('id_calendar')->references('id')->on('calendar')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holiday');
    }
};
