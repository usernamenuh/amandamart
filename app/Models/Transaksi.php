<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'subtotal',
        'discount_amount',
        'subtotal_after_discount',
        'ppn_amount',
        'total_harga',
        'keterangan',
        'no_referensi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'qty' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal_after_discount' => 'decimal:2',
        'ppn_amount' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Get the barang that owns the transaksi.
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Get the user that owns the transaksi.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include masuk transactions.
     */
    public function scopeMasuk($query)
    {
        return $query->where('jenis_transaksi', 'masuk');
    }

    /**
     * Scope a query to only include keluar transactions.
     */
    public function scopeKeluar($query)
    {
        return $query->where('jenis_transaksi', 'keluar');
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by barang.
     */
    public function scopeByBarang($query, $barangId)
    {
        return $query->where('barang_id', $barangId);
    }

    /**
     * Get formatted total harga.
     */
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Get formatted harga satuan.
     */
    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * Get transaction type badge color.
     */
    public function getJenisTransaksiBadgeColorAttribute()
    {
        return $this->jenis_transaksi === 'masuk' ? 'green' : 'red';
    }

    /**
     * Get transaction type icon.
     */
    public function getJenisTransaksiIconAttribute()
    {
        return $this->jenis_transaksi === 'masuk' ? 'fa-arrow-down' : 'fa-arrow-up';
    }

    /**
     * Check if transaction has discount.
     */
    public function hasDiscount()
    {
        return $this->discount_amount > 0;
    }

    /**
     * Check if transaction has PPN.
     */
    public function hasPpn()
    {
        return $this->ppn_amount > 0;
    }

    /**
     * Get discount percentage based on subtotal.
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->subtotal > 0) {
            return round(($this->discount_amount / $this->subtotal) * 100, 2);
        }
        return 0;
    }

    /**
     * Get PPN percentage (should always be 11% for keluar transactions).
     */
    public function getPpnPercentageAttribute()
    {
        if ($this->subtotal_after_discount > 0 && $this->ppn_amount > 0) {
            return round(($this->ppn_amount / $this->subtotal_after_discount) * 100, 2);
        }
        return 0;
    }

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate fields before saving
        static::saving(function ($transaksi) {
            // Calculate subtotal
            $transaksi->subtotal = $transaksi->qty * $transaksi->harga_satuan;

            // Get barang data for discount calculation
            if ($transaksi->barang_id) {
                $barang = Barang::find($transaksi->barang_id);
                if ($barang) {
                    // Calculate discount
                    $discountPerUnit = $barang->disc_amt ?: 0;
                    $transaksi->discount_amount = $discountPerUnit * $transaksi->qty;

                    // Calculate subtotal after discount
                    $transaksi->subtotal_after_discount = max(0, $transaksi->subtotal - $transaksi->discount_amount);

                    // Calculate PPN (only for keluar transactions)
                    if ($transaksi->jenis_transaksi === 'keluar') {
                        $transaksi->ppn_amount = $transaksi->subtotal_after_discount * 0.11;
                    } else {
                        $transaksi->ppn_amount = 0;
                    }

                    // Calculate final total
                    $transaksi->total_harga = $transaksi->subtotal_after_discount + $transaksi->ppn_amount;
                }
            }
        });
    }
}