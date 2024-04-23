<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        DB::unprepared('
            CREATE TRIGGER after_insert_laporan_fotos
            AFTER INSERT ON laporan_fotos
            FOR EACH ROW
            BEGIN
                DECLARE foto_count INT;

                -- Count how many times the foto_id has been reported
                SELECT COUNT(*) INTO foto_count
                FROM laporan_fotos
                WHERE foto_id = NEW.foto_id;

                -- If the foto_id has been reported 5 or more times, soft delete the corresponding foto
                IF foto_count >= 5 THEN
                    UPDATE fotos
                    SET deleted_at = NOW()
                    WHERE id = NEW.foto_id;
                END IF;
            END;
        ');
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trigger_after_insert_laporan_fotos');
    }
};
