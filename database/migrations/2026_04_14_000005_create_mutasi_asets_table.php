<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mutasi_asets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained('asets')->onDelete('cascade');
            $table->string('dari_lokasi');
            $table->string('ke_lokasi');
            $table->foreignId('dari_departemen_id')->nullable()->constrained('departemens')->onDelete('set null');
            $table->foreignId('ke_departemen_id')->nullable()->constrained('departemens')->onDelete('set null');
            $table->foreignId('dari_karyawan_id')->nullable()->constrained('karyawans')->onDelete('set null');
            $table->foreignId('ke_karyawan_id')->nullable()->constrained('karyawans')->onDelete('set null');
            $table->date('tanggal_mutasi');
            $table->string('alasan');
            $table->text('keterangan')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_asets');
    }
};
