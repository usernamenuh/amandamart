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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_transaksi');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2); // Harga per unit saat transaksi
            $table->decimal('total_harga', 15, 2); // Total akhir (qty * harga_satuan)
            $table->text('keterangan')->nullable();
            $table->string('no_referensi')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index(['barang_id', 'tanggal_transaksi']);
            $table->index(['jenis_transaksi', 'tanggal_transaksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
