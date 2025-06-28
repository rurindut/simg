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
        Schema::create('pernikahans', function (Blueprint $table) {
            $table->id();
            // Suami
            $table->foreignId('anggota_id_suami')->nullable()->constrained('anggotas')->nullOnDelete();
            $table->string('nia_suami')->nullable();
            $table->string('nama_suami');

            // Istri
            $table->foreignId('anggota_id_istri')->nullable()->constrained('anggotas')->nullOnDelete();
            $table->string('nia_istri')->nullable();
            $table->string('nama_istri');

            // Catatan Sipil
            $table->string('no_akta_nikah')->nullable();
            $table->date('tanggal_catatan_sipil')->nullable();
            $table->string('tempat_catatan_sipil')->nullable();
            $table->string('akta_catatan_sipil')->nullable(); // upload akta

            // Pemberkatan Gereja
            $table->string('no_piagam')->nullable();
            $table->date('tanggal_pemberkatan')->nullable();
            $table->string('pendeta')->nullable();
            $table->string('gereja')->nullable();
            $table->text('alamat_gereja')->nullable();
            $table->string('piagam_pemberkatan')->nullable(); // upload piagam
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pernikahans');
    }
};
