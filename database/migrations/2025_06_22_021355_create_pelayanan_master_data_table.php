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
        Schema::create('jadwal_ibadahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal')->nullable();
            $table->string('hari')->nullable();
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('daftar_pelayanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('komsels', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan_master_data');
    }
};
