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
        Schema::table('pekerjaans', function (Blueprint $table) {
            $table->dropColumn('profesi');
            $table->foreignId('profesi_id')->nullable()->constrained('profesis')->nullOnDelete()->after('anggota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pekerjaans', function (Blueprint $table) {
            $table->dropForeign(['profesi_id']);
            $table->dropColumn('profesi_id');
            $table->string('profesi')->nullable();
        });
    }
};
