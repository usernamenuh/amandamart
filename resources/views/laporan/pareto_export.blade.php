<table>
    {{-- Header Utama --}}
    <tr>
        <td colspan="10" style="font-size:16px; font-weight:600; text-align:center; background-color:#653361; color:white; padding:15px; letter-spacing:2px;">
            ANALISIS PARETO ABC - {{ $periodeInfo ? strtoupper($periodeInfo['nama_bulan']) : 'SEMUA PERIODE' }}
        </td>
    </tr>
    
    {{-- Info Periode dan Basis --}}
    <tr>
        <td colspan="10" style="font-size:12px; font-weight:500; text-align:center; background-color:#E8F4FD; padding:10px;">
            Basis: {{ $sortBy === 'quantity' ? 'Kuantitas Stok' : 'Nilai Inventori' }} | 
            Periode: {{ $periodeInfo ? $periodeInfo['nama_bulan'] . ' (' . $periodeInfo['periode'] . ')' : 'Semua Periode (1-12)' }} | 
            Total Item: {{ count($analisis) }} | 
            Tanggal Export: {{ $exportDate }}
        </td>
    </tr>
    
    {{-- Baris Kosong --}}
    <tr><td colspan="10" style="height:5px;"></td></tr>
    
    {{-- Header Tabel --}}
    <tr>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">No</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Nama Barang</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Total Qty</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Total Nilai (Rp)</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Persentase (%)</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Akumulasi (%)</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Kategori</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Periode</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Vendor</th>
        <th style="background:#f2f2f2; font-weight:600; text-align:center; padding:10px; border:1px solid #ddd;">Harga Satuan</th>
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
            <td style="text-align:center; padding:8px; border:1px solid #eee;">{{ $index + 1 }}</td>
            <td style="padding:8px; border:1px solid #eee;">{{ $item->nama_barang }}</td>
            <td style="text-align:center; padding:8px; border:1px solid #eee;">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
            <td style="text-align:right; padding:8px; border:1px solid #eee;">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
            <td style="text-align:center; padding:8px; border:1px solid #eee;">{{ $item->persentase }}%</td>
            <td style="text-align:center; padding:8px; border:1px solid #eee;">{{ $item->akumulasi_persentase }}%</td>
            <td style="text-align:center; padding:8px; border:1px solid #eee; font-weight:bold; 
                color:{{ $item->kategori === 'A' ? '#d32f2f' : ($item->kategori === 'B' ? '#f57c00' : '#388e3c') }};">
                {{ $item->kategori }}
            </td>
            <td style="text-align:center; padding:8px; border:1px solid #eee;">{{ $item->periode_name ?? '-' }}</td>
            <td style="padding:8px; border:1px solid #eee;">{{ $item->vendor ?? '-' }}</td>
            <td style="text-align:right; padding:8px; border:1px solid #eee;">Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    
    {{-- Summary Footer --}}
    <tr><td colspan="10" style="height:10px;"></td></tr>
    <tr>
        <td colspan="10" style="font-size:12px; font-weight:600; text-align:center; background-color:#f5f5f5; padding:10px; border:2px solid #653361;">
            RINGKASAN: 
            Kategori A: {{ $stats['kategori_a_count'] ?? 0 }} item ({{ $stats['kontribusi_a'] ?? 0 }}%) | 
            Kategori B: {{ $stats['kategori_b_count'] ?? 0 }} item ({{ $stats['kontribusi_b'] ?? 0 }}%) | 
            Kategori C: {{ $stats['kategori_c_count'] ?? 0 }} item ({{ $stats['kontribusi_c'] ?? 0 }}%)
        </td>
    </tr>
</table>