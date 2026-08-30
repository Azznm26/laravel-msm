<!DOCTYPE html>
<html>

<head>
    <title>Laporan Career Monitoring</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Laporan Career Monitoring</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>ID Badge</th>
                <th>Departemen</th>
                <th>Jabatan Utama</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monitoringData as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['name'] }}</td>
                <td>{{ $row['id_badge'] }}</td>
                <td>{{ $row['department_name'] }}</td>
                <td>{{ $row['jabatan_name'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>