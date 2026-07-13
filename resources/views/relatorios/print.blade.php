<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} — AgencyOS</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #111; padding: 24px; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .meta { color: #666; font-size: 12px; margin-bottom: 16px; }
        table { border-collapse: collapse; width: 100%; font-size: 13px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        @media print { body { padding: 0; } }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="meta">Gerado em {{ $generatedAt }} — AgencyOS</div>

    <table>
        @foreach($rows as $index => $line)
            <tr>
                @foreach($line as $cell)
                    @if($index === 0)
                        <th>{{ $cell }}</th>
                    @else
                        <td>{{ $cell }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </table>

    <script>window.onload = function () { window.print(); };</script>
</body>
</html>
