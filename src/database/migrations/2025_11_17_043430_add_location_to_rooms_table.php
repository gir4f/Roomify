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
        Schema::table('rooms', function (Blueprint $table) {
            // Tambahkan 2 kolom baru setelah 'facilities'
            $table->string('gedung')->nullable()->after('facilities');
            $table->integer('lantai')->nullable()->after('gedung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $table->dropColumn('gedung');
            $table->dropColumn('lantai');
        });
    }
};