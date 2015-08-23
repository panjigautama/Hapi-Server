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
    <div style="float:left"><img src="<?php echo $app->make('url')->to('/');?>/img/logo.png"></div>
    <div style="float:left; padding: 30px;font-weight: 500;font-size: xx-large;">Analitycs</div>
    <div style="clear:both"></div>
    <div style="float:left;padding:0px 100px 0px 0px"><div>Jenis Daging</div><div style="font-weight: 500;font-size: xx-large;">< Daging Sapi ></div></div>
    <div style="float:left;padding:0px 100px 0px 0px"><div>Daerah Penjual</div><div style="font-weight: 500;font-size: xx-large;">DKI Jakarta</div><div>Daerah Bandung dan 3 lokasi lain</div></div>
    <div style="float:right;"><div>Durasi</div>
        <button class="btn  <?php echo (isset($_GET['tgl1']) && $_GET['tgl1'] == date('Y-m-d',strtotime('-29days')))? 'btn-success': 'btn-default';?>" style="width: 150px; border: 2px solid #5cb85c" onclick="location.href='<?php echo $app->make('url')->to('/');?>/chart?tgl1=<?php echo date('Y-m-d',strtotime('-29days'))?>&tgl2=<?php echo date('Y-m-d')?>'">30 Hari</button>
        <button class="btn <?php echo (isset($_GET['tgl1']) && $_GET['tgl1'] == date('Y-m-d',strtotime('-6days')))? 'btn-success': 'btn-default';?>" style="width: 150px; border: 2px solid #5cb85c"  onclick="location.href='<?php echo $app->make('url')->to('/');?>/chart?tgl1=<?php echo date('Y-m-d',strtotime('-6days'))?>&tgl2=<?php echo date('Y-m-d')?>'">7 Hari</button>
        <button class="btn  <?php echo (isset($_GET['tgl1']) && $_GET['tgl1'] == date('Y-m-d'))? 'btn-success': 'btn-default';?>" style="width: 150px; border: 2px solid #5cb85c" onclick="location.href='<?php echo $app->make('url')->to('/');?>/chart?tgl1=<?php echo date('Y-m-d')?>&tgl2=<?php echo date('Y-m-d')?>'">Hari Ini</button></div>
    <div style="clear:both"></div>
    <div id="chart"></div>
        <ul id="series" style="list-style: none">
            <?php 
            $i = 0;$date = '';
            foreach($data As $dat){
            
            if($date=='' || $date==date('Y-m-d',strtotime($dat->created_at))){
                $date = date('Y-m-d',strtotime($dat->created_at));
                echo '<li style="float:left; padding: 10px 40px 10px 40px;"><input type="checkbox" name="series" value="'. $i++ .'" checked="true" /> '.$dat->location->name.'</li>';
            }
            }?>
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
    <?php 
        
        $date = '';
        foreach($data As $dat){
            if($date=='' || $date==date('Y-m-d',strtotime($dat->created_at))){
                $date = date('Y-m-d',strtotime($dat->created_at));
                echo "data.addColumn('number', '".$dat->location->name."');";
            }
        }
    ?>
    
    data.addRows([[
    <?php 
        $i=0;$date = '1';
        foreach($data As $dat){
            if ($date !=date('Y-m-d',strtotime($dat->created_at))){
            if ($date == '1'){
            echo $i;
            }else
            echo '],['.$i++;}
            $date = date('Y-m-d',strtotime($dat->created_at));
            echo ','.$dat->price.'';

        }
    ?>
    ]]);
    
    var view = new google.visualization.DataView(data);
    var option =  {
            height: 500,
            strictFirstColumnType: true,
            legend: 'none',
            lineWidth: 4,
            series: {
                0: { color: '#179647' },
                1: { color: '#1b8441' },
                2: { color: '#3bb9c8' },
                3: { color: '#3ca0a0' },
                4: { color: '#ed212e' },
                5: { color: '#c3283c' },
            },
            hAxis: {baselineColor: '#FFFFFF'},
            backgroundColor: '#EfEfEf',
            'chartArea': {'width': '100%', 'height': '80%'},
            vAxis: { 
                    viewWindowMode:'explicit',
                    viewWindow: {
                        min:100000
                    }
                }
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
        chart.draw(view,option);
    });
}
    
    </script>
    
  </body>
</html>