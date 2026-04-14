<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset')->unique();
            $table->string('nama');
            $table->string('merek')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->date('tanggal_beli');
            $table->date('masa_garansi')->nullable();
            $table->enum('kondisi', ['baik', 'rusak', 'hilang', 'tidak_aktif'])->default('baik');
            $table->string('lokasi');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
            $table->foreignId('departemen_id')->constrained('departemens')->onDelete('restrict');
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('karyawans')->onDelete('set null');
            $table->enum('metode_depresiasi', ['garis_lurus', 'saldo_menurun'])->default('garis_lurus');
            $table->integer('umur_ekonomis')->default(5); // tahun
            $table->decimal('nilai_sisa', 15, 2)->default(0);
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
