<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('notify', function (Blueprint $table) {
      $table->index(['id_user', 'read_at'], 'notify_user_read_idx');
      $table->index(['id_user', 'date'], 'notify_user_date_idx');
    });
  }

  public function down(): void
  {
    Schema::table('notify', function (Blueprint $table) {
      $table->dropIndex('notify_user_read_idx');
      $table->dropIndex('notify_user_date_idx');
    });
  }
};
