<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1; // Baris pertama berisi header
    }

    public function model(array $row)
    {
        // Jika kolom penting kosong, abaikan
        if (empty($row['site']) || empty($row['no']) || empty($row['itemid'])) {
            return null;
        }

        // Cek apakah data sudah ada
        $exists = Barang::where('no', $row['no'])
            ->where('itemid', $row['itemid'])
            ->exists();

        if ($exists) {
            return null; // Jangan insert kalau duplikat
        }

        return new Barang([
            'periode' => $row['periode'] ?? null,
            'site' => $row['site'] ?? null,
            'description' => $row['description'] ?? null,
            'no' => $row['no'] ?? null,
            'itemid' => $row['itemid'] ?? null,
            'barcode' => $row['barcode'] ?? null,
            'nama_item' => $row['nama_item'] ?? null,
            'vendor' => $row['vendor'] ?? null,
            'vendor_id' => $row['vendorid'] ?? null,
            'dept_id' => $row['dept_id'] ?? null,
            'vend_name' => $row['vend_name'] ?? null,
            'ctgry_id' => $row['ctgry_id'] ?? null,
            'dept_description' => $row['dept_description'] ?? null,
            'qty' => $row['qty'] ?? null,
            'unitid' => $row['unitid'] ?? null,
            'cost_price' => $row['cost_price'] ?? null,
            'total_cost' => $row['total_cost_price'] ?? null,
            'total_inc_ppn' => $row['total_inc_ppn'] ?? null,
            'unit_price' => $row['unitprice'] ?? null,
            'gross_amt' => $row['grossamt'] ?? null,
            'disc_amt' => $row['discamt'] ?? null,
            'sales_after_discount' => $row['sales_after_dis'] ?? null,
            'sales_vat' => $row['sales_vat'] ?? null,
            'net_sales_bef_tax' => $row['net_sales_bef_tax'] ?? null,
            'margin' => $row['margin'] ?? null,
            'margin_percent' => $row['margin_percent'] ?? null,
            'date' => $row['date'] ?? null,
            'time' => $row['time'] ?? null,
            'consignment' => $row['consignment'] ?? null,
        ]);
    }
}
