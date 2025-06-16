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
        Schema::create('atestasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');

            $table->string('tipe');
            $table->date('tanggal')->nullable();

            $table->string('gereja_dari')->nullable();
            $table->text('alamat_asal')->nullable();

            $table->string('gereja_tujuan')->nullable();
            $table->text('alamat_tujuan')->nullable();

            $table->string('nomor_surat')->nullable();
            $table->text('alasan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atestasis');
    }
};
