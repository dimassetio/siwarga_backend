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
    Schema::create('wargas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('rt_id')->constrained()->onDelete('cascade');
      $table->integer('jumlah_laki');
      $table->integer('jumlah_perempuan');
      $table->integer('jumlah_kk');
      $table->string('status');
      $table->unsignedBigInteger('created_by')->nullable();
      $table->timestamps();

      $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
      $table->unique('rt_id');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('wargas');
  }
};
