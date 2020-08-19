
<script type="text/javascript">
 
    google.charts.load('current', {'packages':['corechart']});
      
    google.charts.setOnLoadCallback(drawChart);
  
    function drawChart() {
      var jsonData = $.ajax({
          url: "sparkline/sparkline-portfolio.php",
          dataType: "json",
          async: false
          }).responseText;
          
    
      var data = new google.visualization.DataTable(jsonData);
        var options = {
            width: 364,
                  height: 153.5,
            colors: ['rgb(158, 213, 255)'],
            'chartArea': {
                'width': '100%',
                'height': '100%'
            },
                series: {
                // Gives each series an axis name that matches the Y-axis below.
                0: {
                    targetAxisIndex: 1,
                    color: 'rgb(255, 168, 158)'
                },
                1: {
                    targetAxisIndex: 0,
                    color: 'rgb(158, 213, 255)',
                }
            },
            'backgroundColor': 'white',

            legend: {
                position: 'none'
            },
            hAxis: {
                textStyle: {
                    color: '#f5f5f5',
                },
                gridlines: {
                    color: 'transparent'
                },
             
                title: ''
            },
            vAxis: {
                
                gridlines: {
                    color: 'transparent'
                },
                baselineColor: 'none',
              
               
            }
        };
      
      var chart = new google.visualization.AreaChart(document.getElementById('line_chart'));
      chart.draw(data, options);

    }
     $(document).on('click', '#butsave', function () {
        //on trade record, deposit/withdraw or delete transaction fetch portfolio data    
        drawChart();    
    });

 
</script>