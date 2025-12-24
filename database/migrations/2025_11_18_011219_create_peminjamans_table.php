<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('peminjamans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('anggota_id')->constrained('anggotas')->cascadeOnDelete();
        $table->foreignId('buku_id')->constrained('bukus')->cascadeOnDelete();
        $table->date('tanggal_pinjam');
        $table->date('batas_kembali');
        $table->date('tanggal_kembali')->nullable();
        $table->enum('status', ['Dipinjam', 'Dikembalikan'])->default('Dipinjam');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
