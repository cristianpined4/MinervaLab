<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_calendar');
            $table->unsignedBigInteger('id_room');
            $table->date('day');
            $table->time('hour');
            $table->decimal('time', 5, 2)->default(15);
            $table->integer('count')->default(1);
            $table->tinyInteger('status')->default(0)->comment('0=queue, 1=approved, 2=declined, 3=canceled, 4=lost');
            $table->timestamp('date')->useCurrent();

            $table->foreign('id_user')->references('id')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_calendar')->references('id')->on('calendar')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_room')->references('id')->on('room')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation');
    }
};
