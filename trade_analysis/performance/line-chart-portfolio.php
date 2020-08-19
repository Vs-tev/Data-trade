<script type="text/javascript">
    google.charts.load('current', {
        packages: ['corechart', 'line']
    });
    google.charts.setOnLoadCallback(portfolioLineChart);

    $(window).resize(function() {
        portfolioLineChart()
    })


    function portfolioLineChart() {
        var idPortfolio = $("#portfolios").data('value');
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var jsonData = $.ajax({
            url: "performance/line-chart-portfolio-index.php",
            async: false,
            dataType: "json",
            method: 'POST',
            data: {
                idPortfolio: idPortfolio,
                from_date: from_date,
                to_date: to_date
            },
            async: false,
        }).responseText;
        //console.log(jsonData);

        var data = new google.visualization.DataTable(jsonData);
        var options = {
            pointSize: 2,
            curveType: 'function',
            titlePosition: "in",
            legend: 'none',
            crosshair: {
                trigger: 'both',
                focused: {
                    color: '#9a9a9a'
                }
            },

            animation: {
                startup: true,
                duration: 500,
                easing: 'out',
            },

            title: 'Performance',
            titleTextStyle: {
                color: '#8e6767',
                bold: false,
                fontName: 'Roboto',
            },
            'chartArea': {
                'width': '88%',
                'height': '88%',
                //'right': 10,
                //'left': 80,
            },
            series: {
                // Gives each series an axis name that matches the Y-axis below.
                0: {
                    targetAxisIndex: 1,
                    color: 'rgb(255, 93, 93)'
                },
                1: {
                    targetAxisIndex: 0,
                    color: 'rgb(39, 163, 255)',
                }
            },
            vAxes: {
                // Adds titles to each axis.
                0: {
                    title: 'Balance',
                    titleTextStyle: {
                        fontSize: 13,
                        fontName: 'Roboto',
                        italic: false,
                        bold: true,
                        color: '#8e6767',
                    }
                },
                1: {
                    title: 'Return Growth %',
                    titleTextStyle: {
                        fontSize: 13,
                        fontName: 'Roboto',
                        italic: false,
                        bold: true,
                        color: '#8e6767',
                    }
                },
            },
            hAxis: {
                baselineColor: '#9a9a9a',
                textStyle: {
                    color: '#8e6767',
                    fontSize: 13,
                    fontName: 'Roboto',
                },

                //title: 'Date', titleTextStyle: {color: '#8e6767', fontSize: 12, fontName: 'Roboto', italic: false,}
            },
            vAxis: {
                baselineColor: '#9a9a9a',
                format: 'short',
                textStyle: {
                    color: '#8e6767',
                    fontSize: 13,
                    fontName: 'Roboto',

                },
                gridlines: {
                    color: '#f5f5f5',
                },
                //title: 'Balance',titleTextStyle: {color: '#8e6767', fontSize: 12, fontName: 'Roboto', italic: false,}
            },
            explorer: {
                actions: ['dragToZoom', 'rightClickToReset'],
                axis: 'horizontal',
                keepInBounds: true,
                maxZoomIn: 10.0
            },
        };
        if (data.getNumberOfRows() == null) { // if you have no data, add a data point and make the series transparent
            data.addRow([new Date(), null])
        }


        var hideSal = document.getElementById("hideReturn");
        hideSal.onclick = function returnLineToggle() {
            if (hideSal.checked == false) {
                view = new google.visualization.DataView(data);
                options.series[0].lineWidth = 0;
                options.series[0].pointSize = 0;
                options.series[0].enableInteractivity = 'false';
                chart.draw(view, options);
            } else {
                view = new google.visualization.DataView(data);
                options.series[0].lineWidth = 2;
                options.series[0].pointSize = 2;
                options.series[0].enableInteractivity = 'true';
                chart.draw(view, options);
            }
        }
        var chart = new google.visualization.LineChart(document.getElementById('line-chart'));
        chart.draw(data, options);
    }

    $(document).on('click', '.li-content', function() {
        $("#from_date").val('');
        $("#to_date").val('');
        portfolioLineChart()
    })


    $('#to_date').on('change', function() {
        var to_date = $(this).val();
        portfolioLineChart()
    });
    $('#from_date').on('change', function() {
        var from_date = $(this).val();
        if (to_date == " ") {
            return false;
        } else {
            portfolioLineChart()
        }
    });
</script>