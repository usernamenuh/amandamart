<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Pareto ABC - {{ $periodeInfo ? $periodeInfo['nama_bulan'] : 'Semua Periode' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .first-page-header {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #653361, #8e4585);
            color: white;
            border-radius: 8px;
            page-break-inside: avoid;
        }
        
        .first-page-header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 1.5px;
        }
        
        .first-page-header .subtitle {
            font-size: 14px;
            opacity: 0.95;
            margin-bottom: 5px;
        }
        
        .first-page-header .export-info {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 8px;
        }
        
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 5px solid #653361;
            page-break-inside: avoid;
        }
        
        .info-item {
            text-align: center;
            flex: 1;
        }
        
        .info-item .label {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
            font-weight: 500;
        }
        
        .info-item .value {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 12px;
            page-break-inside: avoid;
        }
        
        .summary-card {
            flex: 1;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            border: 2px solid;
        }
        
        .summary-card.category-a {
            background: #ffebee;
            border-color: #f44336;
            color: #c62828;
        }
        
        .summary-card.category-b {
            background: #fff8e1;
            border-color: #ff9800;
            color: #e65100;
        }
        
        .summary-card.category-c {
            background: #e8f5e8;
            border-color: #4caf50;
            color: #2e7d32;
        }
        
        .summary-card .category-label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .summary-card .category-count {
            font-size: 11px;
            margin-bottom: 4px;
        }
        
        .summary-card .category-value {
            font-size: 10px;
            margin-bottom: 4px;
        }
        
        .summary-card .category-contribution {
            font-size: 12px;
            font-weight: bold;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        .data-table th {
            background: #653361;
            color: white;
            padding: 10px 5px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        .data-table td {
            padding: 7px 5px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }
        
        .data-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .category-a-row {
            background: #ffebee !important;
        }
        
        .category-b-row {
            background: #fff8e1 !important;
        }
        
        .category-c-row {
            background: #e8f5e8 !important;
        }
        
        .category-badge {
            padding: 3px 7px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 8px;
            display: inline-block;
        }
        
        .category-badge.A {
            background: #f44336;
            color: white;
        }
        
        .category-badge.B {
            background: #ff9800;
            color: white;
        }
        
        .category-badge.C {
            background: #4caf50;
            color: white;
        }
        
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .footer {
            margin-top: 20px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 2px solid #653361;
            page-break-inside: avoid;
        }
        
        .progress-bar {
            width: 100%;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin: 2px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: #653361;
            border-radius: 2px;
        }
        
        /* Page break settings */
        .page-break-before {
            page-break-before: always;
        }
        
        .page-break-after {
            page-break-after: always;
        }
        
        .no-page-break {
            page-break-inside: avoid;
        }
        
        @media print {
            body { 
                font-size: 9px; 
            }
            .first-page-header h1 { 
                font-size: 18px; 
            }
            .no-print { 
                display: none; 
            }
        }
        
        @page {
            margin: 0.5in;
            size: A4 landscape;
        }
    </style>
</head>
<body>
    <!-- Header hanya di halaman pertama -->
    <div class="first-page-header no-page-break">
        <h1>ANALISIS PARETO ABC</h1>
        <div class="subtitle">
            {{ $periodeInfo ? strtoupper($periodeInfo['nama_bulan']) : 'SEMUA PERIODE' }} - 
            BASIS: {{ $sortBy === 'quantity' ? 'KUANTITAS STOK' : 'NILAI INVENTORI' }}
        </div>
        <div class="export-info">
            Digenerate pada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- Info Section -->
    <div class="info-section no-page-break">
        <div class="info-item">
            <div class="label">Total Barang</div>
            <div class="value">{{ number_format($stats['total_barang'], 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Total Qty</div>
            <div class="value">{{ number_format($stats['total_qty_inventori'], 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Total Nilai</div>
            <div class="value">Rp {{ number_format($stats['total_nilai_inventori'], 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Periode</div>
            <div class="value">{{ $periodeInfo ? $periodeInfo['nama_bulan'] : 'Semua Periode' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Basis Analisis</div>
            <div class="value">{{ $sortBy === 'quantity' ? 'Kuantitas' : 'Nilai' }}</div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards no-page-break">
        <div class="summary-card category-a">
            <div class="category-label">KATEGORI A</div>
            <div class="category-count">{{ number_format($stats['kategori_a_count'], 0, ',', '.') }} Item</div>
            <div class="category-value">Rp {{ number_format($stats['nilai_kategori_a'], 0, ',', '.') }}</div>
            <div class="category-contribution">{{ $stats['kontribusi_a'] }}% Kontribusi</div>
        </div>
        <div class="summary-card category-b">
            <div class="category-label">KATEGORI B</div>
            <div class="category-count">{{ number_format($stats['kategori_b_count'], 0, ',', '.') }} Item</div>
            <div class="category-value">Rp {{ number_format($stats['nilai_kategori_b'], 0, ',', '.') }}</div>
            <div class="category-contribution">{{ $stats['kontribusi_b'] }}% Kontribusi</div>
        </div>
        <div class="summary-card category-c">
            <div class="category-label">KATEGORI C</div>
            <div class="category-count">{{ number_format($stats['kategori_c_count'], 0, ',', '.') }} Item</div>
            <div class="category-value">Rp {{ number_format($stats['nilai_kategori_c'], 0, ',', '.') }}</div>
            <div class="category-contribution">{{ $stats['kontribusi_c'] }}% Kontribusi</div>
        </div>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 28%;">Nama Barang</th>
                <th style="width: 8%;">Periode</th>
                <th style="width: 15%;">Vendor</th>
                <th style="width: 8%;">Qty</th>
                <th style="width: 12%;">Harga Satuan</th>
                <th style="width: 12%;">Total Nilai</th>
                <th style="width: 6%;">%</th>
                <th style="width: 7%;">Akum.</th>
                <th style="width: 5%;">Kat.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($analisis as $index => $item)
                <tr class="category-{{ strtolower($item->kategori) }}-row">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $item->nama_barang }}</strong>
                        @if($item->no_barang)
                            <br><small style="color: #666;">{{ $item->no_barang }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->periode_name ?? '-' }}</td>
                    <td class="text-left">{{ Str::limit($item->vendor ?? '-', 20) }}</td>
                    <td class="text-right">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</strong></td>
                    <td class="text-center">
                        {{ $item->persentase }}%
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min($item->persentase * 5, 100) }}%;"></div>
                        </div>
                    </td>
                    <td class="text-center">{{ $item->akumulasi_persentase }}%</td>
                    <td class="text-center">
                        <span class="category-badge {{ $item->kategori }}">{{ $item->kategori }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer no-page-break">
        <strong>RINGKASAN ANALISIS ABC PARETO</strong><br>
        Kategori A: {{ $stats['kategori_a_count'] }} item ({{ $stats['kontribusi_a'] }}%) | 
        Kategori B: {{ $stats['kategori_b_count'] }} item ({{ $stats['kontribusi_b'] }}%) | 
        Kategori C: {{ $stats['kategori_c_count'] }} item ({{ $stats['kontribusi_c'] }}%)<br>
        <em>Basis Analisis: {{ $sortBy === 'quantity' ? 'Kuantitas Stok' : 'Nilai Inventori' }} | 
        Periode: {{ $periodeInfo ? $periodeInfo['nama_bulan'] : 'Semua Periode' }} | 
        Total Item: {{ number_format($stats['total_barang'], 0, ',', '.') }}</em>
    </div>
</body>
</html>