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
        Schema::table('anaks', function (Blueprint $table) {
            $table->foreignId('anggota_id')->nullable()->change();

            $table->foreignId('ayah_id')->nullable()->after('anggota_id')->constrained('anggotas')->nullOnDelete();
            $table->foreignId('ibu_id')->nullable()->after('ayah_id')->constrained('anggotas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anaks', function (Blueprint $table) {
            $table->dropForeign(['ayah_id']);
            $table->dropColumn('ayah_id');

            $table->dropForeign(['ibu_id']);
            $table->dropColumn('ibu_id');
        });
    }
};
