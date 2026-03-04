<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #667eea;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .header {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP ABSENSI</h2>
        <p><strong>Tanggal Export:</strong> {{ date('d/m/Y H:i:s') }}</p>
        <p><strong>Total Data:</strong> {{ $absensis->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Waktu Scan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensis as $index => $absensi)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ ucfirst($absensi->participant_type) }}</td>
                <td>
                    @if($absensi->participant_type == 'mahasiswa')
                        {{ $absensi->participant->nim }}
                    @else
                        {{ $absensi->participant->nip }}
                    @endif
                </td>
                <td>{{ $absensi->participant->nama }}</td>
                <td>{{ $absensi->scan_time->format('d/m/Y H:i:s') }}</td>
                <td>{{ $absensi->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
