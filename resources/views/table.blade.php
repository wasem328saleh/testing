<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="description" content="Webpage description goes here" />
    <meta charset="utf-8">
    <title>Change_me</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <link rel="stylesheet" href="css/style.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body>

<div class="container">
    <table>
        <thead>
        <tr>
            <th>Dag</th>
            <th>Tijd</th>
            <th>Uren</th>
            <th>Locatie</th>
            <th>Kenmerk</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($dw as $row)
            <tr>
                @if (preg_match('/^Week\s\d+$/', $row))
                    <td colspan="5" class="week-header">{{ $row }}</td>
                @elseif (preg_match('/^\d+\s+tot\s+\d+:\d+\s+P\s\d+$/', $row))
                    <td>{{ substr($row, 0, strpos($row, ' ')) }}</td>
                    <td>{{ substr($row, strpos($row, ' ') + 1, strpos($row, ' P') - strpos($row, ' ') - 1) }}</td>
                    <td>{{ substr($row, strpos($row, ' P') + 1) }}</td>
                    <td></td>
                    <td></td>
                @elseif (preg_match('/^\d+\s+uren$/', $row))
                    <td colspan="4"></td>
                    <td>{{ $row }}</td>
                @else
                    <td>{{ $row }}</td>
                    <td colspan="4"></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
</script>

</body>
</html>
