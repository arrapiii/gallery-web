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
        Schema::create('laporan_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('foto_id');
            $table->foreign('foto_id')->references('id')->on('fotos');
            $table->unsignedBigInteger('user_id'); // User who reported the photo
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('jenis_laporan_id');
            $table->foreign('jenis_laporan_id')->references('id')->on('jenis_laporans');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_fotos');
    }
};
