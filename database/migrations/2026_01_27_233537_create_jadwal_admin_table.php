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
        Schema::create('jadwal_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->enum('status', ['tersedia', 'tutup']);
            $table->timestamps();

            $table->unique(['lapangan_id', 'tanggal', 'jam_mulai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_admin');
    }
};
