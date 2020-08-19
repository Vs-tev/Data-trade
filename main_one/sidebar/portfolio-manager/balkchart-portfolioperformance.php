<?php
require_once '../init.php';
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];
    
$a = new PortfolioData($id);
 
?>
<script type="text/javascript">
    
google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawEquity);
         
    $(window).resize(function() {
      drawEquity()  
    })
      function drawEquity() {
        var data = google.visualization.arrayToDataTable([
          ['Portfolio Name', 'Equity', 'RCI %'],
          <?php
            if($a->data()){
            foreach($a->data() as $row){?>
            <?php echo "['".$row->recording_account_name."',".number_format($row->total,2, '.','').", ".number_format($row->RCI,2, '.','')."],"; ?>

            <?php   }      } ?>  
        ]);
      
        var options = {
          chart: {
            title: 'Portfolio Performance',
            subtitle: 'Compare portfolio balance and return of capital investment',
          },
              bars: 'vertical',
          vAxis: {format: 'decimal'},
          
          colors: ['rgb(41, 152, 234)', '#1b9e77'],
            bar: {
                groupWidth: "40%"
            },
              yAxis: {
          title: 'Portfolio Performance',
          scaleType: 'log',
      
        },
            series: {
            0: { axis: 'Equity' }, 
            1: { axis: 'RCI' } 
          },
        axes: {
            y: {
              Equity: {label: 'Equity'}, 
              RCI: {side: 'right', label: '(RCI) Return Capital Investment %'}
            
            }
          }
        };
        var chart = new google.charts.Bar(document.getElementById('chart_div'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
       
    }
    
</script>