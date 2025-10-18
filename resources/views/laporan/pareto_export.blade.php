<table>
    {{-- Header Utama --}}
    <tr>
        <td colspan="9" style="font-size:14px; font-weight:600; text-align:center; background-color:#2c3e50; color:white; padding:10px;">
            ANALISIS PARETO ABC - {{ $periodeInfo ? strtoupper($periodeInfo['nama_bulan']) : 'SEMUA PERIODE' }}
        </td>
    </tr>
    
    {{-- Info Periode dan Basis --}}
    <tr>
        <td colspan="9" style="font-size:11px; font-weight:500; text-align:center; background-color:#E8F4FD; padding:8px;">
            Basis: {{ $sortBy === 'quantity' ? 'Kuantitas Stok' : 'Nilai Inventori' }} | 
            Total Item: {{ count($analisis) }} | 
            Tanggal: {{ $exportDate }}
        </td>
    </tr>
    
    {{-- Baris Kosong --}}
    <tr><td colspan="9" style="height:3px;"></td></tr>
    
    {{-- Header Tabel --}}
    <tr>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">No</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">Nama Barang</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">Qty</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">Nilai (Rp)</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">%</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">Akum %</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">Kat</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">Vendor</th>
        <th style="background:#2c3e50; color:white; font-weight:600; text-align:center; padding:8px; border:1px solid #ddd; font-size:10px;">Harga</th>
    </tr>

    {{-- Data Rows --}}
    @foreach($analisis as $index => $item)
        @php
            $bgColor = '#ffffff';
            if($item->kategori === 'A') $bgColor = '#ffebee';
            elseif($item->kategori === 'B') $bgColor = '#fff8e1';
            elseif($item->kategori === 'C') $bgColor = '#e8f5e8';
        @endphp
        <tr style="background-color:{{ $bgColor }};">
            <td style="text-align:center; padding:6px; border:1px solid #eee; font-size:9px;">{{ $index + 1 }}</td>
            <td style="padding:6px; border:1px solid #eee; font-size:9px;">{{ $item->nama_barang }}</td>
            <td style="text-align:center; padding:6px; border:1px solid #eee; font-size:9px;">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
            <td style="text-align:right; padding:6px; border:1px solid #eee; font-size:9px;">{{ number_format($item->total_nilai, 0, ',', '.') }}</td>
            <td style="text-align:center; padding:6px; border:1px solid #eee; font-size:9px;">{{ $item->persentase }}%</td>
            <td style="text-align:center; padding:6px; border:1px solid #eee; font-size:9px;">{{ $item->akumulasi_persentase }}%</td>
            <td style="text-align:center; padding:6px; border:1px solid #eee; font-size:9px; font-weight:bold;">{{ $item->kategori }}</td>
            <td style="padding:6px; border:1px solid #eee; font-size:9px;">{{ Str::limit($item->vendor ?? '-', 15) }}</td>
            <td style="text-align:right; padding:6px; border:1px solid #eee; font-size:9px;">{{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    
    {{-- Summary Footer --}}
    <tr><td colspan="9" style="height:5px;"></td></tr>
    <tr>
        <td colspan="9" style="font-size:10px; font-weight:600; text-align:center; background-color:#f5f5f5; padding:8px; border:1px solid #2c3e50;">
            RINGKASAN: A: {{ $stats['kategori_a_count'] ?? 0 }} item ({{ $stats['kontribusi_a'] ?? 0 }}%) | 
            B: {{ $stats['kategori_b_count'] ?? 0 }} item ({{ $stats['kontribusi_b'] ?? 0 }}%) | 
            C: {{ $stats['kategori_c_count'] ?? 0 }} item ({{ $stats['kontribusi_c'] ?? 0 }}%)
        </td>
    </tr>
</table>
