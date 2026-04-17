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
        Schema::table('medicines', function (Blueprint $table) {

            // 🔥 TAMBAHAN KOLOM UNTUK PISAH GROSIR
            $table->boolean('is_grosir')->default(false)->after('is_resep');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {

            // 🔥 HAPUS SAAT ROLLBACK
            $table->dropColumn('is_grosir');

        });
    }
};