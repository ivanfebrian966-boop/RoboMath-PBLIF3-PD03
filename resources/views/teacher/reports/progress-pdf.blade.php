<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Progres Siswa - RoboMath</title>
    <style>
        @page {
            margin: 28px;
        }

        body {
            color: #1e293b;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
        }

        h1 {
            color: #065f46;
            font-size: 18px;
            margin: 0 0 6px;
        }

        p {
            color: #64748b;
            margin: 0 0 14px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background: #047857;
            color: #fff;
            font-weight: bold;
            text-align: left;
        }

        th,
        td {
            border: 1px solid #dbe4e0;
            padding: 7px 6px;
        }

        tr:nth-child(even) td {
            background: #f0fdf4;
        }

        .empty {
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Progres Siswa</h1>
    <p>
        RoboMath
        &bull; {{ $kelasFilter === 'all' ? 'Semua kelas' : "Kelas {$kelasFilter} SD" }}
        &bull; Dibuat {{ $generatedAt->format('d-m-Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    @foreach($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ count($headers) }}">Belum ada data siswa untuk kriteria ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
