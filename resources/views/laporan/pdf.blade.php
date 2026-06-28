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
            margin-bottom: 25px;
            padding: 25px 20px;
            background-color: #0c4a6e;
            color: white;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(12, 74, 110, 0.15);
        }
        .header h1 {
            margin: 0;
            font-size: 24pt;
            font-weight: bold;
            letter-spacing: 2px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .header p {
            margin: 10px 0;
            font-size: 12pt;
            opacity: 0.95;
        }
        .section {
            margin-bottom: 25px;
            background-color: white;
            padding: 20px;
            border: 1px solid #e2e8f0;
            page-break-before: always;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .section h2 {
            font-size: 16pt;
            margin: 0 0 12px 0;
            padding-bottom: 10px;
            color: #0c4a6e;
            font-weight: bold;
            border-bottom: 3px solid #0ea5e9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            border-radius: 6px;
            overflow: hidden;
        }
        th {
            background-color: #0ea5e9;
            color: white;
            padding: 12px 10px;
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            border: none;
        }
        td {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: left;
            background-color: #fff;
            transition: background-color 0.2s;
        }
        td:hover {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
            font-weight: 500;
        }
        .text-center {
            text-align: center;
            font-weight: 500;
        }
        .summary-box {
            background-color: #f0f9ff;
            border: 2px solid #0ea5e9;
            padding: 20px;
            margin-top: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(12, 74, 110, 0.1);
        }
        .summary-box h3 {
            margin: 0 0 12px 0;
            color: #0c4a6e;
            font-size: 16pt;
            font-weight: bold;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 8px;
        }
        .summary-item {
            overflow: hidden;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px dashed #7dd3fc;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .summary-label {
            font-weight: bold;
            float: left;
            color: #075985;
            font-size: 11pt;
        }
        .summary-value {
            font-weight: bold;
            float: right;
            color: #0c4a6e;
            font-size: 11pt;
        }
        .page-break {
            page-break-after: always;
        }
        .badge {
            padding: 4px 12px;
            font-weight: bold;
            font-size: 10pt;
            border-radius: 4px;
        }
        .badge-pemasukan {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #22c55e;
        }
        .badge-pengeluaran {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        .signature-table {
            width: 100%;
            margin-top: 60px;
            border: none;
        }
        .signature-cell {
            width: 50%;
            text-align: center;
            border: none;
        }
        .signature-line {
            border-top: 3px solid #0c4a6e;
            width: 250px;
            margin: 50px auto 15px;
        }
        .signature-name {
            font-weight: bold;
            color: #0c4a6e;
            font-size: 12pt;
        }
        .signature-title {
            color: #475569;
            font-size: 11pt;
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

    <div class="section">
        <h2>Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th style="width: auto;">Tanggal</th>
                    <th style="width: auto;">Deskripsi</th>
                    <th style="width: auto;">Akun</th>
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
                    <td class="text-right">Rp {{ number_format($p->saldo_akhir, 0, ',', '.') }}</td>
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
