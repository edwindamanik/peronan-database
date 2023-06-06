<!DOCTYPE html>
<html>
<head>
    <title>Laporan Setor</title>
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
    <h1>Laporan Penyetoran</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pasar</th>
                <th>Petugas</th>
                <th>Jumlah Setoran</th>
                <th>Penyetoran Melalui</th>
                <th>Tanggal Penyetoran</th>
                <th>Tanggal Disetor</th>
                <!-- Tambahkan kolom lainnya -->
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_pasar }}</td>
                <td>{{ $item->nama }}</td>
                <td>Rp {{ number_format($item->jumlah_setoran, 0, ',', '.') }},-</td>
                <td>{{ $item->penyetoran_melalui }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_penyetoran)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_disetor)->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
