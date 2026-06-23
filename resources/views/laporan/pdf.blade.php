<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 1.5cm;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #0ea5e9;
            padding: 20px 15px;
            background-color: #e0f2fe;
        }
        .header h1 {
            margin: 0;
            font-size: 20pt;
            color: #0c4a6e;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header p {
            margin: 8px 0;
            font-size: 11pt;
            color: #075985;
        }
        .section {
            margin-bottom: 20px;
            background-color: white;
            padding: 15px;
            border: 1px solid #bae6fd;
            page-break-before: always;
        }
        .section-first {
            margin-bottom: 20px;
            background-color: white;
            padding: 15px;
            border: 1px solid #bae6fd;
            page-break-before: avoid;
        }
        .section h2 {
            font-size: 14pt;
            margin: 0 0 10px 0;
            padding-bottom: 8px;
            color: #0c4a6e;
            font-weight: bold;
            border-bottom: 2px solid #0ea5e9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }
        th {
            background-color: #0ea5e9;
            color: white;
            padding: 10px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
        }
        td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
            background-color: #fff;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            background-color: #f0f9ff;
            border: 2px solid #0ea5e9;
            padding: 15px;
            margin-top: 20px;
        }
        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #0c4a6e;
            font-size: 14pt;
            font-weight: bold;
            border-bottom: 1px solid #0ea5e9;
            padding-bottom: 5px;
        }
        .summary-item {
            overflow: hidden;
            margin-bottom: 8px;
            padding: 6px 0;
            border-bottom: 1px dashed #7dd3fc;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .summary-label {
            font-weight: bold;
            float: left;
            color: #075985;
        }
        .summary-value {
            font-weight: bold;
            float: right;
            color: #0c4a6e;
        }
        .page-break {
            page-break-after: always;
        }
        .badge {
            padding: 4px 10px;
            font-weight: bold;
            font-size: 9pt;
        }
        .badge-pemasukan {
            background-color: #bbf7d0;
            color: #15803d;
            border: 1px solid #4ade80;
        }
        .badge-pengeluaran {
            background-color: #fecaca;
            color: #be1235;
            border: 1px solid #fb7185;
        }
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border: none;
        }
        .signature-cell {
            width: 50%;
            text-align: center;
            border: none;
        }
        .signature-line {
            border-top: 2px solid #333;
            width: 200px;
            margin: 40px auto 10px;
        }
        .signature-name {
            font-weight: bold;
            color: #0c4a6e;
            font-size: 11pt;
        }
        .signature-title {
            color: #475569;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN PEMUDA</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($tanggal_selesai)->format('d F Y') }}</p>
        <p>Kas: {{ ucfirst($kas) }}</p>
    </div>

    <div class="summary-box">
        <h3>Ringkasan</h3>
        <div class="summary-item">
            <span class="summary-label">Total Saldo Awal:</span>
            <span>Rp {{ number_format($summary->saldo_awal ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Pemasukan:</span>
            <span>Rp {{ number_format($summary->total_pemasukan ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Pengeluaran:</span>
            <span>Rp {{ number_format($summary->total_pengeluaran ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Kas di Tangan:</span>
            <span>Rp {{ number_format($summary->kas_sekarang ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="section-first">
        <h2>Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th style="width: auto;">Tanggal</th>
                    <th style="width: auto;">Deskripsi</th>
                    <th style="width: auto;">Akun</th>
                    <th style="width: auto;">Penanggung Jawab</th>
                    <th class="text-center" style="width: 15%;">Tipe</th>
                    <th class="text-right" style="width: auto;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}</td>
                    <td>{{ $t->deskripsi ?? '-' }}</td>
                    <td>{{ $t->akun->nama_akun ?? '-' }}</td>
                    <td>{{ $t->penanggungJawab->nama ?? '-' }}</td>
                    <td class="text-center">
                        @if(strtolower($t->jenis_transaksi) == 'pemasukan')
                            <span class="badge badge-pemasukan">{{ ucfirst($t->jenis_transaksi) }}</span>
                        @else
                            <span class="badge badge-pengeluaran">{{ ucfirst($t->jenis_transaksi) }}</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Mutasi Rekening</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th style="width: auto;">Tanggal</th>
                    <th style="width: auto;">Akun Debit</th>
                    <th style="width: auto;">Akun Kredit</th>
                    <th style="width: auto;">Keterangan</th>
                    <th class="text-right" style="width: auto;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mutasi as $index => $m)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($m->date)->format('d/m/Y') }}</td>
                    <td>{{ $m->akunDebit->nama_akun ?? '-' }}</td>
                    <td>{{ $m->akunKredit->nama_akun ?? '-' }}</td>
                    <td>{{ $m->keterangan ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($m->jumlah, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data mutasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Posisi Keuangan</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th style="width: auto;">Nama Akun</th>
                    <th class="text-right" style="width: auto;">Saldo Awal</th>
                    <th class="text-right" style="width: auto;">Pemasukan</th>
                    <th class="text-right" style="width: auto;">Pengeluaran</th>
                    <th class="text-right" style="width: auto;">Total</th>
                    <th class="text-right" style="width: auto;">Riil</th>
                    <th class="text-right" style="width: auto;">Selisih</th>
                    <th style="width: auto;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posisiKeuangan as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $p->nama_akun }}</td>
                    <td class="text-right">Rp {{ number_format($p->saldo_awal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->pemasukan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->pengeluaran, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->riil, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($p->selisih, 0, ',', '.') }}</td>
                    <td>{{ $p->keterangan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data posisi keuangan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <table class="signature-table">
        <tr>
            <td class="signature-cell">
                <div class="signature-line"></div>
                <div class="signature-name">Naafi'ah Lifani Salsabila</div>
                <div class="signature-title">Ketua Pemuda</div>
            </td>
            <td class="signature-cell">
                <div class="signature-line"></div>
                <div class="signature-name">Fauzan Trisuladana</div>
                <div class="signature-title">Bendahara</div>
            </td>
        </tr>
    </table>
</body>
</html>
