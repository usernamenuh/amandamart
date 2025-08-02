<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode',
        'site',
        'description',
        'no',
        'itemid',
        'barcode',
        'nama_item',
        'vendor',
        'vendor_id',
        'dept_id',
        'vend_name',
        'ctgry_id',
        'dept_description',
        'qty',
        'unitid',
        'cost_price',
        'total_cost',
        'total_inc_ppn',
        'unit_price',
        'gross_amt',
        'disc_amt',
        'sales_after_discount',
        'sales_vat',
        'net_sales_bef_tax',
        'margin',
        'margin_percent',
        'date',
        'time',
        'consignment',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_inc_ppn' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'gross_amt' => 'decimal:2',
        'disc_amt' => 'decimal:2',
        'sales_after_discount' => 'decimal:2',
        'sales_vat' => 'decimal:2',
        'net_sales_bef_tax' => 'decimal:2',
        'margin' => 'decimal:2',
        'margin_percent' => 'decimal:2',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Transaksi
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Get transaksi masuk
     */
    public function transaksiMasuk()
    {
        return $this->hasMany(Transaksi::class)->where('jenis_transaksi', 'masuk');
    }

    /**
     * Get transaksi keluar
     */
    public function transaksiKeluar()
    {
        return $this->hasMany(Transaksi::class)->where('jenis_transaksi', 'keluar');
    }

    /**
     * Calculate total stock from transactions
     */
    public function getTotalStockAttribute()
    {
        $masuk = $this->transaksiMasuk()->sum('qty');
        $keluar = $this->transaksiKeluar()->sum('qty');
        return $masuk - $keluar;
    }
}
