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
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nia')->unique();
            $table->string('nik')->nullable();
            $table->string('sapaan')->nullable();
            $table->string('nama');
            $table->string('panggilan')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->foreignId('suku_id')->nullable()->constrained()->nullOnDelete();
            $table->string('golongan_darah')->nullable();
            $table->boolean('donor')->default(false);
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('telepon')->nullable();
            $table->date('tanggal_registrasi')->nullable();
            $table->string('maps')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->string('kecamatan_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('kecamatan_domisili')->nullable();
            $table->string('status_tinggal')->nullable();
            $table->string('status_hidup')->nullable();
            $table->foreignId('pendidikan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('disiplin_ilmu')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('gelar')->nullable();
            $table->foreignId('hobi_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('minat_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status_jemaat')->nullable();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cluster_id')->nullable()->constrained()->nullOnDelete();
            $table->string('foto_anda')->nullable();
            $table->string('foto_keluarga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggotas');
    }
};
