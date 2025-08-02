<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Analisis ABC Pareto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 11px;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background: #2c3e50;
            color: white;
            border-radius: 5px;
        }
        
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .header .subtitle {
            margin: 0;
            font-size: 12px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 3px;
        }
        
        .info-item {
            text-align: center;
            flex: 1;
        }
        
        .info-item .label {
            font-size: 9px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .info-item .value {
            font-size: 11px;
            font-weight: bold;
        }
        
        .categories {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }
        
        .category {
            flex: 1;
            padding: 8px;
            text-align: center;
            border-radius: 5px;
            border: 2px solid;
        }
        
        .category-a {
            background: #ffebee;
            border-color: #f44336;
            color: #c62828;
        }
        
        .category-b {
            background: #fff8e1;
            border-color: #ff9800;
            color: #e65100;
        }
        
        .category-c {
            background: #e8f5e8;
            border-color: #4caf50;
            color: #2e7d32;
        }
        
        .category h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
        }
        
        .category .stats {
            font-size: 10px;
        }
        
        .category .percentage {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin: 3px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }
        
        th {
            background: #2c3e50;
            color: white;
            padding: 6px 3px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            text-align: center;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .cat-a { background: #ffebee !important; }
        .cat-b { background: #fff8e1 !important; }
        .cat-c { background: #e8f5e8 !important; }
        
        .badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: bold;
            color: white;
        }
        
        .badge-a { background: #f44336; }
        .badge-b { background: #ff9800; }
        .badge-c { background: #4caf50; }
        
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        
        .footer {
            margin-top: 10px;
            padding: 8px;
            background: #f8f9fa;
            text-align: center;
            font-size: 9px;
            border-top: 2px solid #2c3e50;
        }
        
        @page {
            margin: 0.5in;
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
            {{ $sortBy === 'quantity' ? 'BASIS KUANTITAS' : 'BASIS NILAI' }} - 
            {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- Info Summary -->
    <div class="info-row">
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
            <div class="value">{{ $periodeInfo ? $periodeInfo['nama_bulan'] : 'Semua' }}</div>
        </div>
        <div class="info-item">
            <div class="label">Basis</div>
            <div class="value">{{ $sortBy === 'quantity' ? 'Kuantitas' : 'Nilai' }}</div>
        </div>
    </div>

    <!-- Categories -->
    <div class="categories">
        <div class="category category-a">
            <h3>KATEGORI A</h3>
            <div class="stats">
                <span class="percentage">{{ $stats['kontribusi_a'] }}%</span>
                {{ number_format($stats['kategori_a_count'], 0, ',', '.') }} Item<br>
                Rp {{ number_format($stats['nilai_kategori_a'], 0, ',', '.') }}
            </div>
        </div>
        <div class="category category-b">
            <h3>KATEGORI B</h3>
            <div class="stats">
                <span class="percentage">{{ $stats['kontribusi_b'] }}%</span>
                {{ number_format($stats['kategori_b_count'], 0, ',', '.') }} Item<br>
                Rp {{ number_format($stats['nilai_kategori_b'], 0, ',', '.') }}
            </div>
        </div>
        <div class="category category-c">
            <h3>KATEGORI C</h3>
            <div class="stats">
                <span class="percentage">{{ $stats['kontribusi_c'] }}%</span>
                {{ number_format($stats['kategori_c_count'], 0, ',', '.') }} Item<br>
                Rp {{ number_format($stats['nilai_kategori_c'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="25%">Nama Barang</th>
                <th width="7%">Periode</th>
                <th width="15%">Vendor</th>
                <th width="8%">Qty</th>
                <th width="12%">Harga</th>
                <th width="12%">Total Nilai</th>
                <th width="6%">%</th>
                <th width="7%">Akum</th>
                <th width="5%">Kat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($analisis as $index => $item)
                <tr class="cat-{{ strtolower($item->kategori) }}">
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $item->nama_barang }}</strong>
                        @if($item->no_barang)
                            <br><small>{{ $item->no_barang }}</small>
                        @endif
                    </td>
                    <td>{{ $item->periode_name ?? '-' }}</td>
                    <td class="text-left">{{ Str::limit($item->vendor ?? '-', 15) }}</td>
                    <td class="text-right">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>{{ number_format($item->total_nilai, 0, ',', '.') }}</strong></td>
                    <td><strong>{{ $item->persentase }}%</strong></td>
                    <td><strong>{{ $item->akumulasi_persentase }}%</strong></td>
                    <td>
                        <span class="badge badge-{{ strtolower($item->kategori) }}">{{ $item->kategori }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <strong>RINGKASAN:</strong> 
        Kategori A: {{ $stats['kategori_a_count'] }} item ({{ $stats['kontribusi_a'] }}%) | 
        Kategori B: {{ $stats['kategori_b_count'] }} item ({{ $stats['kontribusi_b'] }}%) | 
        Kategori C: {{ $stats['kategori_c_count'] }} item ({{ $stats['kontribusi_c'] }}%) | 
        Total: {{ number_format($stats['total_barang'], 0, ',', '.') }} item
    </div>
</body>
</html>