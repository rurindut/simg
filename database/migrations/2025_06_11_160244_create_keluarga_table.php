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
        Schema::create('orang_tuas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->enum('hubungan', ['ayah', 'ibu']);
            $table->string('nia')->nullable();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('pasangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->string('nia')->nullable();
            $table->string('nama');
            $table->string('no_akta_nikah')->nullable();
            $table->date('tanggal_catatan_sipil')->nullable();
            $table->string('tempat_catatan_sipil')->nullable();
            $table->string('no_piagam')->nullable();
            $table->date('tanggal_pemberkatan')->nullable();
            $table->string('pendeta')->nullable();
            $table->string('gereja')->nullable();
            $table->text('alamat_gereja')->nullable();
            $table->string('akta_catatan_sipil')->nullable();
            $table->string('piagam_pemberkatan')->nullable();
            $table->timestamps();
        });

        Schema::create('anaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->onDelete('cascade');
            $table->string('nia')->nullable();
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('jemaat')->nullable();
            $table->text('alamat')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua');
        Schema::dropIfExists('pasangan');
        Schema::dropIfExists('anak');
    }
};
