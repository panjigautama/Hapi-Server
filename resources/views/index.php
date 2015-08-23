<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hapi</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <h1>Hapi</h1>

    <div>Harga Sapi</div>
    <div id="chart"></div>
    <ul id="series" style="list-style: none">
        <li><input type="checkbox" name="series" value="1" checked="true"/> DKI Jakarta</li>
        <li><input type="checkbox" name="series" value="2" checked="true"/> Bandung</li>
        <li><input type="checkbox" name="series" value="3" checked="true"/> Yogya</li>
        <li><input type="checkbox" name="series" value="4" checked="true"/> Semarang</li>
        <li><input type="checkbox" name="series" value="5" checked="true"/> Surabaya</li>
    </ul>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'Item');
        data.addColumn('number', 'DKI Jakarta');
        data.addColumn('number', 'Bandung');
        data.addColumn('number', 'Yogya');
        data.addColumn('number', 'Semarang');
        data.addColumn('number', 'Surabaya');

        data.addRows([
            [0, 2, 1, 5, 4, 5],
            [1, 3, 7, 2, 3, 6],
            [2, 5, 4, 7, 5, 6],
            [3, 4, 6, 2, 6, 5],
            [4, 1, 5, 8, 4, 6]
        ]);

        var view = new google.visualization.DataView(data);
        var option = {
            height: 500,
            strictFirstColumnType: true,
            legend: 'none',
            lineWidth: 4,
            series: {
                0: {color: '#179647'},
                1: {color: '#1b8441'},
                2: {color: '#3bb9c8'},
                3: {color: '#3ca0a0'},
                4: {color: '#ed212e'},
                5: {color: '#c3283c'},
            },
            hAxis: {baselineColor: '#FFFFFF'},
            backgroundColor: '#EfEfEf',
            'chartArea': {'width': '100%', 'height': '80%'},
            // 'legend': {'position': 'bottom'}
        }
        var chart = new google.visualization.LineChart($('#chart')[0]);
        chart.draw(view, option);

        $('#series').find(':checkbox').change(function () {
            var cols = [0];
            $('#series').find(':checkbox:checked').each(function () {
                cols.push(parseInt($(this).attr('value')));
            });
            view.setColumns(cols);
            chart.draw(view, option);
        });
    }

</script>

</body>
</html>

