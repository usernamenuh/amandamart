<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SPK - Sistem Pendukung Keputusan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 30px;
            color: #2c3e50;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
            color: #1a252f;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 4px 0;
            font-size: 13px;
            color: #7f8c8d;
        }
        .header p:first-of-type {
            font-size: 14px;
            color: #34495e;
            font-weight: 500;
        }
        /* Improved summary box styling with better colors and layout */
        .summary {
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        .summary-box {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 2px solid #e8eef5;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        .summary-box:hover {
            border-color: #2c3e50;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }
        .summary-box h3 {
            margin: 0 0 12px 0;
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-box .value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
        }
        /* Improved table styling with better header and borders */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 6px;
            overflow: hidden;
        }
        /* Removed thead styling and added header-row styling for first row */
        table .header-row {
            background-color: #2c3e50;
            color: #ffffff;
        }
        table .header-row td {
            background-color: #2c3e50;
            color: #ffffff;
            border: 1px solid #1a252f;
            padding: 12px 8px;
            text-align: left;
            font-weight: 700;
            letter-spacing: 0.3px;
            font-size: 12px;
        }
        table td {
            border-bottom: 1px solid #ecf0f1;
            padding: 8px 8px;
        }
        table tbody tr {
            transition: background-color 0.2s ease;
        }
        table tbody tr:hover {
            background-color: #f8f9fa;
        }
        table tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }
        /* Enhanced category styling with better colors */
        .kategori-a {
            background-color: #fadbd8;
            color: #c0392b;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            min-width: 30px;
            text-align: center;
        }
        .kategori-b {
            background-color: #fef5e7;
            color: #d68910;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            min-width: 30px;
            text-align: center;
        }
        .kategori-c {
            background-color: #d5f4e6;
            color: #27ae60;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            min-width: 30px;
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
            color: #95a5a6;
            border-top: 1px solid #ecf0f1;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistem Pendukung Keputusan (SPK)</h1>
        <p>dan Rekomendasi Pengadaan Barang Amandamart</p>
        @if($periode)
            <p>Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('F Y') }}</p>
        @else
            <p>Periode: Semua Periode</p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>Total Item</h3>
            <div class="value">{{ $summary['total_items'] }}</div>
        </div>
        <div class="summary-box">
            <h3>Kategori A</h3>
            <div class="value">{{ $summary['kategori_a'] }}</div>
        </div>
        <div class="summary-box">
            <h3>Kategori B</h3>
            <div class="value">{{ $summary['kategori_b'] }}</div>
        </div>
        <div class="summary-box">
            <h3>Kategori C</h3>
            <div class="value">{{ $summary['kategori_c'] }}</div>
        </div>
    </div>

    <table>
        <tbody>
            <tr class="header-row">
                <td>NO</td>
                <td>KODE</td>
                <td>Nama Barang</td>
                <td>Kategori</td>
                <td>Stok</td>
                <td>Rekomendasi</td>
            </tr>
            @forelse($analisisData as $index => $item)
                @php
                    $rec = $recommendations[$item->barang_id] ?? [];
                    $kategoriClass = 'kategori-' . strtolower($item->kategori);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td><span class="{{ $kategoriClass }}">{{ $item->kategori }}</span></td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $rec['rekomendasi'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
