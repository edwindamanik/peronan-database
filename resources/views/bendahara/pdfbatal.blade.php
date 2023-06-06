<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembatalan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Laporan Setor</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Bukti Bayar</th>
                <th>Nama Pasar</th>
                <th>Petugas</th>
                <th>Tanggal</th>
                <th>Biaya Retribusi</th>
                <!-- Tambahkan kolom lainnya -->
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->no_bukti_pembayaran }}</td>
                <td>{{ $item->nama_pasar }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                <td>{{ $item->biaya_retribusi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
