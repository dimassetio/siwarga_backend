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
    Schema::table('users', function (Blueprint $table) {
      $table->unsignedBigInteger('rw_id')->nullable();
      $table->unsignedBigInteger('rt_id')->nullable();

      $table->foreign('rw_id')->references('id')->on('rws')->onDelete('set null');
      $table->foreign('rt_id')->references('id')->on('rts')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('rw_id');
      $table->dropColumn('rt_id');
    });
  }
};
