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
        Schema::create('minats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        
        Schema::create('hobis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('sukus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('pendidikans', function (Blueprint $table) {
            $table->id();
            $table->string('initial');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('login_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote');
            $table->timestamps();
        });

        Schema::create('profesis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minats');
        Schema::dropIfExists('hobis');
        Schema::dropIfExists('sukus');
        Schema::dropIfExists('pendidikans');
        Schema::dropIfExists('login_quotes');
        Schema::dropIfExists('profesis');
    }
};
