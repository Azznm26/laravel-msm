<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jawaban User</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f3f4f6; }
        .user-header { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Jawaban User</h2>

    @foreach($results as $userName => $answers)
        <div class="user-header">{{ $userName }}</div>
        <table>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Soal</th>
                    <th>Jawaban</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $answer)
                <tr>
                    <td>{{ $answer->task }}</td>
                    <td>{{ $answer->soal }}</td>
                    <td>{{ $answer->jawaban ?? '-' }}</td>
                    <td>{{ $answer->nilai ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>