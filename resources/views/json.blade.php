<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <table>
            @foreach($a as $aa)
            <tr>
                <th>地區</th>
                <th>汙染</th>
                <th>AQI</th>
            </tr>
            <tr>
                <td>{{$aa->Area}}</td>
                <td>{{$aa->MajorPollutant}}</td>
                <td>{{$aa->AQI}}</td>
            </tr>
            @endforeach
        </tabel>
    </body>
</html>