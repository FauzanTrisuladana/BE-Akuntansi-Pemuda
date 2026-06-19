<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
        }
        .header p {
            margin: 5px 0;
            font-size: 10pt;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 14pt;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 20px;
        }
        .summary-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .summary-label {
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
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
                    <th class="text-center">No</th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Akun</th>
                    <th>Penanggung Jawab</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-right">Jumlah</th>
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
                    <td class="text-center">{{ ucfirst($t->jenis_transaksi) }}</td>
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
                    <th class="text-center">No</th>
                    <th>Tanggal</th>
                    <th>Akun Debit</th>
                    <th>Akun Kredit</th>
                    <th>Keterangan</th>
                    <th class="text-right">Jumlah</th>
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
                    <th class="text-center">No</th>
                    <th>Nama Akun</th>
                    <th class="text-right">Saldo Awal</th>
                    <th class="text-right">Pemasukan</th>
                    <th class="text-right">Pengeluaran</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Riil</th>
                    <th class="text-right">Selisih</th>
                    <th>Keterangan</th>
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
</body>
</html>
