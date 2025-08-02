<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class BarangImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $importedCount = 0;
    private $duplicateCount = 0;
    private $errorCount = 0;
    private $errors = [];

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Parse Excel date as INTEGER (raw Excel serial number)
     */
    private function parseDate($date)
    {
        if (empty($date) || $date === '' || $date === null) {
            return null;
        }

        try {
            // If it's numeric, extract the integer part (days)
            if (is_numeric($date)) {
                $serialNumber = (float) $date;
                $daysPart = (int) floor($serialNumber);
                
                // Validate reasonable range
                if ($daysPart > 0 && $daysPart < 100000) {
                    return $daysPart;
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::warning('Date parsing failed: ' . $e->getMessage(), ['date' => $date]);
            return null;
        }
    }

    /**
     * Parse Excel time as DECIMAL (raw Excel time fraction)
     */
    private function parseTime($time)
    {
        if (empty($time) || $time === '' || $time === null) {
            return null;
        }

        try {
            // If it's numeric, extract the decimal part (time fraction)
            if (is_numeric($time)) {
                $serialNumber = (float) $time;
                $timeFraction = $serialNumber - floor($serialNumber);
                
                // Validate time fraction is between 0 and 1
                if ($timeFraction >= 0 && $timeFraction < 1) {
                    // Return as decimal with high precision
                    return round($timeFraction, 10);
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::warning('Time parsing failed: ' . $e->getMessage(), ['time' => $time]);
            return null;
        }
    }

    /**
     * Clean and validate row data
     */
    private function cleanRowData($row)
    {
        return [
            'periode' => $this->formatPeriode($row['periode'] ?? null),
            'site' => $this->cleanString($row['site'] ?? null),
            'description' => $this->cleanString($row['description'] ?? null, 1000),
            'no' => $this->cleanString($row['no'] ?? null),
            'itemid' => $this->cleanString($row['itemid'] ?? null),
            'barcode' => $this->cleanString($row['barcode'] ?? null),
            'nama_item' => $this->cleanString($row['nama_item'] ?? null),
            'vendor' => $this->cleanString($row['vendor'] ?? null),
            'vendor_id' => $this->cleanString($row['vendorid'] ?? $row['vendor_id'] ?? null),
            'dept_id' => $this->cleanString($row['dept_id'] ?? null),
            'vend_name' => $this->cleanString($row['vend_name'] ?? null),
            'ctgry_id' => $this->cleanString($row['ctgry_id'] ?? null),
            'dept_description' => $this->cleanString($row['dept_description'] ?? null),
            'qty' => $this->parseNumeric($row['qty'] ?? 0),
            'unitid' => $this->cleanString($row['unitid'] ?? null),
            'cost_price' => $this->parseNumeric($row['cost_price'] ?? 0),
            'total_cost' => $this->parseNumeric($row['total_cost_price'] ?? $row['total_cost'] ?? 0),
            'total_inc_ppn' => $this->parseNumeric($row['total_inc_ppn'] ?? 0),
            'unit_price' => $this->parseNumeric($row['unitprice'] ?? $row['unit_price'] ?? 0),
            'gross_amt' => $this->parseNumeric($row['grossamt'] ?? $row['gross_amt'] ?? 0),
            'disc_amt' => $this->parseNumeric($row['discamt'] ?? $row['disc_amt'] ?? 0),
            'sales_after_discount' => $this->parseNumeric($row['sales_after_dis'] ?? $row['sales_after_discount'] ?? 0),
            'sales_vat' => $this->parseNumeric($row['sales_vat'] ?? 0),
            'net_sales_bef_tax' => $this->parseNumeric($row['net_sales_bef_tax'] ?? 0),
            'margin' => $this->parseNumeric($row['margin'] ?? 0),
            'margin_percent' => $this->parseNumeric($row['margin_percent'] ?? 0),
            'date' => $this->parseDate($row['date'] ?? null), // Store as INTEGER
            'time' => $this->parseTime($row['time'] ?? null), // Store as DECIMAL
            'consignment' => $this->cleanString($row['consignment'] ?? null),
        ];
    }

    /**
     * Clean string data
     */
    private function cleanString($value, $maxLength = 255)
    {
        if (empty($value) || $value === '' || $value === null) {
            return null;
        }
        
        $cleaned = trim((string) $value);
        
        if ($cleaned === '' || $cleaned === '0') {
            return null;
        }
        
        if ($maxLength && strlen($cleaned) > $maxLength) {
            $cleaned = substr($cleaned, 0, $maxLength);
        }
        
        return $cleaned;
    }

    /**
     * Parse numeric values safely
     */
    private function parseNumeric($value)
    {
        if (empty($value) || $value === '' || $value === null) {
            return 0;
        }
        
        // Handle numeric values
        if (is_numeric($value)) {
            return (float) $value;
        }
        
        // Clean string values
        $cleaned = preg_replace('/[^0-9.-]/', '', (string) $value);
        
        if (!is_numeric($cleaned) || $cleaned === '') {
            return 0;
        }
        
        return (float) $cleaned;
    }

    /**
     * Format periode
     */
    private function formatPeriode($periode)
    {
        if (empty($periode) || $periode === '' || $periode === null) {
            return null;
        }

        $periodeInt = (int) $periode;
        
        if ($periodeInt >= 1 && $periodeInt <= 12) {
            return $periodeInt;
        }

        return null;
    }

    public function model(array $row)
    {
        try {
            // Skip empty rows
            if (empty($row) || !is_array($row)) {
                return null;
            }
            
            // Skip if site is empty
            if (!isset($row['site']) || empty(trim($row['site']))) {
                return null;
            }

            // Clean and validate data
            $cleanedData = $this->cleanRowData($row);

            // Skip if essential data is missing
            if (empty($cleanedData['site']) || empty($cleanedData['nama_item'])) {
                return null;
            }

            // Check for duplicates
            $uniqueFields = [];
            
            if (!empty($cleanedData['no'])) {
                $uniqueFields['no'] = $cleanedData['no'];
            } elseif (!empty($cleanedData['itemid'])) {
                $uniqueFields['itemid'] = $cleanedData['itemid'];
            } else {
                $uniqueFields = [
                    'nama_item' => $cleanedData['nama_item'],
                    'site' => $cleanedData['site'],
                    'periode' => $cleanedData['periode'],
                ];
            }

            $existingBarang = Barang::where($uniqueFields)->first();
            
            if ($existingBarang) {
                $this->updateExistingBarang($existingBarang, $cleanedData);
                $this->duplicateCount++;
                return null;
            }

            $this->importedCount++;
            return new Barang($cleanedData);

        } catch (\Exception $e) {
            $this->errorCount++;
            $this->errors[] = [
                'row' => array_slice($row, 0, 3), // Only log first 3 fields
                'error' => $e->getMessage()
            ];
            
            Log::error('Error importing barang: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update existing barang
     */
    private function updateExistingBarang($existingBarang, $cleanedData)
    {
        try {
            $existingBarang->update($cleanedData);
        } catch (\Exception $e) {
            Log::error('Error updating existing barang: ' . $e->getMessage(), [
                'barang_id' => $existingBarang->id
            ]);
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getImportStats()
    {
        return [
            'imported' => $this->importedCount,
            'duplicates' => $this->duplicateCount,
            'errors' => $this->errorCount,
            'error_details' => $this->errors
        ];
    }
}