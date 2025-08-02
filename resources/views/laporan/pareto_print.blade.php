<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Analisis ABC Pareto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        
        .no-print {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        
        .print-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
        }
        
        .btn-primary {
            background: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        @media print {
            .no-print { 
                display: none !important; 
            }
            body { 
                margin: 0; 
                padding: 10px;
                font-size: 10px;
            }
            @page { 
                size: A4 landscape; 
                margin: 0.5in; 
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <h4>Preview Cetak - Analisis ABC Pareto</h4>
        <p>{{ $periodeInfo ? 'Periode: ' . $periodeInfo['nama_bulan'] : 'Semua Periode' }} | 
           Basis: {{ $sortBy === 'quantity' ? 'Kuantitas Stok' : 'Nilai Inventori' }}</p>
        <div class="print-buttons">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Cetak Sekarang
            </button>
            <a href="{{ route('laporan.pareto') }}" class="btn btn-secondary">
                ← Kembali ke Analisis
            </a>
        </div>
    </div>

    <!-- Content untuk print - gunakan template PDF yang sama -->
    <div class="print-content">
        @include('laporan.pareto_pdf')
    </div>
</body>
</html>