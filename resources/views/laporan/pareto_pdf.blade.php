<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Analisis ABC Pareto</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.1;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding: 8px;
            background: #2c3e50;
            color: white;
        }
        
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        
        .header .subtitle {
            margin: 2px 0 0 0;
            font-size: 10px;
        }
        
        .info-row {
            display: flex;
            gap: 5px;
            margin-bottom: 8px;
            background: #f8f9fa;
            padding: 5px;
        }
        
        .info-item {
            flex: 1;
            text-align: center;
            font-size: 9px;
        }
        
        .info-item .label {
            font-size: 8px;
            color: #666;
        }
        
        .info-item .value {
            font-weight: bold;
        }
        
        .categories {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
        }
        
        .category {
            flex: 1;
            padding: 5px;
            text-align: center;
            border-radius: 3px;
            border: 1px solid;
            font-size: 9px;
        }
        
        .category-a { background: #ffebee; border-color: #f44336; }
        .category-b { background: #fff8e1; border-color: #ff9800; }
        .category-c { background: #e8f5e8; border-color: #4caf50; }
        
        .category h3 {
            margin: 0 0 3px 0;
            font-size: 11px;
            font-weight: bold;
        }
        
        .category .percentage {
            font-size: 12px;
            font-weight: bold;
            display: block;
            margin: 2px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 8px;
        }
        
        th {
            background: #2c3e50;
            color: white;
            padding: 4px 2px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        
        td {
            padding: 3px 2px;
            border: 1px solid #ddd;
            text-align: center;
        }
        
        tr:nth-child(even) { background: #f9f9f9; }
        
        .cat-a { background: #ffebee !important; }
        .cat-b { background: #fff8e1 !important; }
        .cat-c { background: #e8f5e8 !important; }
        
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        
        .footer {
            margin-top: 8px;
            padding: 6px;
            background: #f8f9fa;
            text-align: center;
            font-size: 8px;
            border-top: 1px solid #2c3e50;
        }
        
        @page {
            margin: 0.3in;
            size: A4 landscape;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ANALISIS ABC PARETO</h1>
        <div class="subtitle">
            {{ $periodeInfo ? strtoupper($periodeInfo['nama_bulan']) : 'SEMUA PERIODE' }} - 
            {{ $sortBy === 'quantity' ? 'KUANTITAS' : 'NILAI' }} - 
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- Info Summary -->
    <div class="info-row">
        <div class="info-item">
            <div class="label">Total Barang</div>
            <div class="value">{{ number_format($stats['total_barang'] ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Total Qty</div>
            <div class="value">{{ number_format($stats['total_qty_inventori'] ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Total Nilai</div>
            <div class="value">Rp {{ number_format($stats['total_nilai_inventori'] ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="label">Periode</div>
            <div class="value">{{ $periodeInfo ? $periodeInfo['nama_bulan'] : 'Semua' }}</div>
        </div>
    </div>

    <!-- Categories -->
    <div class="categories">
        <div class="category category-a">
            <h3>KATEGORI A</h3>
            <span class="percentage">{{ $stats['kontribusi_a'] ?? 0 }}%</span>
            {{ number_format($stats['kategori_a_count'] ?? 0, 0, ',', '.') }} Item
        </div>
        <div class="category category-b">
            <h3>KATEGORI B</h3>
            <span class="percentage">{{ $stats['kontribusi_b'] ?? 0 }}%</span>
            {{ number_format($stats['kategori_b_count'] ?? 0, 0, ',', '.') }} Item
        </div>
        <div class="category category-c">
            <h3>KATEGORI C</h3>
            <span class="percentage">{{ $stats['kontribusi_c'] ?? 0 }}%</span>
            {{ number_format($stats['kategori_c_count'] ?? 0, 0, ',', '.') }} Item
        </div>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="22%">Nama Barang</th>
                <th width="8%">Qty</th>
                <th width="12%">Nilai</th>
                <th width="6%">%</th>
                <th width="6%">Akum</th>
                <th width="5%">Kat</th>
                <th width="12%">Vendor</th>
                <th width="10%">Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($analisis as $index => $item)
                <tr class="cat-{{ strtolower($item->kategori ?? 'c') }}">
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left"><strong>{{ $item->nama_barang ?? '-' }}</strong></td>
                    <td class="text-right">{{ number_format($item->total_qty ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total_nilai ?? 0, 0, ',', '.') }}</td>
                    <td><strong>{{ $item->persentase ?? 0 }}%</strong></td>
                    <td><strong>{{ $item->akumulasi_persentase ?? 0 }}%</strong></td>
                    <td><strong>{{ $item->kategori ?? 'C' }}</strong></td>
                    <td class="text-left">{{ Str::limit($item->vendor ?? '-', 12) }}</td>
                    <td class="text-right">{{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 15px;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <strong>RINGKASAN:</strong> A: {{ $stats['kategori_a_count'] ?? 0 }} ({{ $stats['kontribusi_a'] ?? 0 }}%) | 
        B: {{ $stats['kategori_b_count'] ?? 0 }} ({{ $stats['kontribusi_b'] ?? 0 }}%) | 
        C: {{ $stats['kategori_c_count'] ?? 0 }} ({{ $stats['kontribusi_c'] ?? 0 }}%)
    </div>
</body>
</html>
