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
            
            // Foreign Keys
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Basic Transaction Info
            $table->date('tanggal_transaksi');
            $table->enum('jenis_transaksi', ['masuk', 'keluar']);
            $table->integer('qty');
            
            // Pricing Information
            $table->decimal('harga_satuan', 15, 2); // Auto-filled from barang (cost_price for masuk, unit_price for keluar)
            
            // Calculation Fields
            $table->decimal('subtotal', 15, 2)->default(0); // qty * harga_satuan
            $table->decimal('discount_amount', 15, 2)->default(0); // disc_amt * qty from barang
            $table->decimal('subtotal_after_discount', 15, 2)->default(0); // subtotal - discount_amount
            $table->decimal('ppn_amount', 15, 2)->default(0); // 11% from subtotal_after_discount (only for keluar)
            $table->decimal('total_harga', 15, 2); // Final total (subtotal_after_discount + ppn_amount)
            
            // Additional Information
            $table->text('keterangan')->nullable();
            $table->string('no_referensi')->nullable();
            
            $table->timestamps();

            // Indexes for better performance
            $table->index(['barang_id', 'tanggal_transaksi']);
            $table->index(['jenis_transaksi', 'tanggal_transaksi']);
            $table->index(['user_id', 'tanggal_transaksi']);
            $table->index('tanggal_transaksi');
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