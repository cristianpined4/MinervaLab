<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vr_glasses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->timestamp('entry_date');
            $table->integer('life_hours');
            $table->integer('usefull_years');
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('id_room');
            $table->foreign('id_room')->references('id')->on('room')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vr_glasses');
    }
};
