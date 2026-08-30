<!DOCTYPE html>
<html>

<head>
    <title>Laporan Task Monitoring</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Laporan Task Monitoring</h2>
    <p>Tanggal: {{ date('d M Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Departemen</th>
                <th>Jabatan</th>
                <th>Task</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Nilai (%)</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($taskData as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>{{ $row['user']->name ?? '-' }}</td>

                <td>{{ $row['user']->department->nama_department ?? '-' }}</td>

                <td>{{ $row['user']->jabatan->nama_jabatan ?? '-' }}</td>

                <td>{{ $row['task']->judul ?? '-' }}</td>

                <td>{{ $row['task']->jenis_task ?? '-' }}</td>

                <td>{{ $row['status'] ?? '-' }}</td>

                <td>{{ $row['score_or_note'] ?? '-' }}</td>

                <td>{{ $row['submitted_at'] ? \Carbon\Carbon::parse($row['submitted_at'])->format('d M Y H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>