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
        Schema::table('komentar_fotos', function (Blueprint $table) {
            $table->dropColumn('tanggal_komentar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komentar_fotos', function (Blueprint $table) {
            $table->date('tanggal_komentar')->after('isi_komentar');
        });
    }
};
