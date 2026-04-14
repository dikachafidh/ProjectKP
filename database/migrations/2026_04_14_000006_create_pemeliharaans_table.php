<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemeliharaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained('asets')->onDelete('cascade');
            $table->enum('jenis', ['rutin', 'perbaikan', 'penggantian_komponen'])->default('rutin');
            $table->date('tanggal_jadwal');
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('biaya', 15, 2)->default(0);
            $table->string('teknisi')->nullable();
            $table->enum('status', ['terjadwal', 'dalam_proses', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->text('deskripsi');
            $table->text('hasil')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeliharaans');
    }
};
