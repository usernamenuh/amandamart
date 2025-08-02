<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'user_id',
        'tanggal_transaksi',
        'jenis_transaksi',
        'qty',
        'harga_satuan',
        'total_harga',
        'keterangan',
        'no_referensi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get subtotal (qty * harga_satuan)
     */
    public function getSubtotalAttribute()
    {
        return $this->qty * $this->harga_satuan;
    }

    /**
     * Get discount amount from barang
     */
    public function getDiscountAmountAttribute()
    {
        return $this->barang->disc_amt ?? 0;
    }

    /**
     * Get subtotal after discount
     */
    public function getSubtotalAfterDiscountAttribute()
    {
        $subtotal = $this->subtotal;
        $discountPerUnit = $this->barang->disc_amt ?? 0;
        $totalDiscount = $discountPerUnit * $this->qty;
        return $subtotal - $totalDiscount;
    }

    /**
     * Get PPN amount from barang
     */
    public function getPpnAmountAttribute()
    {
        $subtotalAfterDisc = $this->subtotal_after_discount;
        $ppnRate = 0.11; // 11%
        return $subtotalAfterDisc * $ppnRate;
    }

    /**
     * Get final total with discount and PPN
     */
    public function getFinalTotalAttribute()
    {
        return $this->subtotal_after_discount + $this->ppn_amount;
    }

    /**
     * Scope untuk transaksi masuk
     */
    public function scopeMasuk($query)
    {
        return $query->where('jenis_transaksi', 'masuk');
    }

    /**
     * Scope untuk transaksi keluar
     */
    public function scopeKeluar($query)
    {
        return $query->where('jenis_transaksi', 'keluar');
    }

    /**
     * Scope untuk periode tertentu
     */
    public function scopePeriode($query, $start, $end)
    {
        return $query->whereBetween('tanggal_transaksi', [$start, $end]);
    }
}
