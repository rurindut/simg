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
        Schema::create('anggota_hobi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hobi_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('anggota_minat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained()->cascadeOnDelete();
            $table->foreignId('minat_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_hobi');
        Schema::dropIfExists('anggota_minat');
    }
};
