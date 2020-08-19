<?php


?>
<html lang="en">

<head>
    <link rel="stylesheet" href="/loginregister.oop/trade_analysis/performance/trade-analysis-performance.css">

    <!--Load the LineChart-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>

    <div class="container-fluid primary-container py-4 ">
        <div class="col-xl-10 col-md-12 m-auto pb-3">
            <h3 class="font-weight-400 mb-3">Performance Chart</h3>
            <div class="row">
                <div class="col mb-2 ml-1">
                    <form id="filterTransactions" method="POST">
                        <!--dropdowns row   --==========================-->
                        <div class="form-row">
                            <div class="col-lg-3 col-sm-12 col-md-12 col-12 form-group dateRangeContainer dropdiv">
                                <input id="portfolios" data-toggle="dropdown" class="form-control styleSelect-search" type="text" placeholder="Choose Portfolio" data-value="<?php echo $getPortfolio->first()->id; ?>" value="<?php echo $getPortfolio->first()->recording_account_name; ?>">
                                <span class="icon-inside">
                                    <i class="material-icons">account_balance_wallet</i>
                                </span>
                                <div class="dropdown-menu shadow-sm rounded text-4">
                                    <ul>
                                        <?php
                                            foreach($getPortfolio->data() as $row){
                                                echo '<li class="li-content" data-value="'.$row->id.'"><span class="element-li">'.$row->recording_account_name.'<span></option>'; 
                                                    }
                                                    ?>
                                    </ul>
                                </div>
                            </div>
                            <!--Date inputs--==============================-->
                            <p class="text-7 font-weight-500 from-to-p">From</p>
                            <div class="col-xl-2 col-lg-2 col-sm-4 col-md-3 col-12 mr-md-0 form-group dateRangeContainer datediv">
                                <input id="from_date" class="form-control" type="date" placeholder="date">
                                <span class="icon-inside">
                                    <i class="material-icons">date_range</i>
                                </span>
                            </div>
                            <p class="text-7 from-to-p">To</p>
                            <div class="col-xl-2 col-lg-2 col-sm-4 col-md-3 col-12 mr-md-3 form-group dateRangeContainer datediv">
                                <input id="to_date" class="form-control" type="date">
                                <span class="icon-inside">
                                    <i class="material-icons">date_range</i>
                                </span>
                            </div>

                            <div class="col custom-control custom-checkbox ml-md-5">
                                <input type="checkbox" class="custom-control-input" id="hideReturn" checked>
                                <label class="custom-control-label" for="hideReturn">Return %</label>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-10 col-md-12 m-auto">
            <div class="portfolio-performance-chartcontainer p-sm-4 shadow-sm rounded">


                <div class="row col-12 portfolio_info_row ml-3 mb-3">
                    <div class="info_boxes ">
                        <label class="text-6 font-weight-400">Start Capital</label><br>
                        <label class="output_label start_capital">0</label><span class="currency"></span>
                    </div>
                    <div class="info_boxes ">
                        <label class="text-6 font-weight-400">Account Balance</label><br>
                        <label class="output_label account_balance">0</label><span class="currency"></span>
                    </div>
                    <div class="info_boxes ">
                        <label class="text-6 font-weight-400">Total Net Profit</label><br>
                        <label class="output_label total_net_profit">0</label><span class="currency"></span>
                    </div>
                    <div class="info_boxes ">
                        <label class="text-6 font-weight-400">Rate of Return</label><br>
                        <label class="output_label performance_return">0%</label><span class="blue">%</span>
                    </div>
                    <div class="info_boxes ">
                        <label class="text-6 font-weight-400">Gained Pips</label><br>
                        <label class="output_label gained_pips">0</label><span class="blue">Pips</span>
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-9 col-md-12 ">

                        <div class="line-chart  mb-3" id="line-chart" style="height:500px"></div>
                        <?php include 'line-chart-portfolio.php'?>
                    </div>

                    <div class="right-container col-lg-3 col-md-12 ">
                        <div id="winrate_container" class=""></div>
                        <table class="table table-sm table-borderless table-performance ">
                            <tbody>
                                <tr>
                                    <td>&#9679 Losing Rate</td>
                                    <td class="losing_rate">0</td>
                                </tr>
                                <tr>
                                    <td>&#9679 Profit/Loss Ratio</td>
                                    <td class="PL_ratio"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid primary-container py-4">
        <div class="col-xl-10 col-md-12 m-auto pb-3">
            <h3 class="font-weight-400 mb-3">Performance Data</h3>
            <div class="row p-0 mt-2">
                <div class="col-xl-6 container col-md-12 ">
                    <div class="col- smTableContainer mb-4 height shadow-sm rounded">
                        <table class="table table-borderless table-sm table-performance ">
                            <thead>
                                <tr class="header">
                                    <th></th>
                                    <th>All Trades</th>
                                    <th class="">Long</th>
                                    <th class="">Short</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="">
                                    <td>&#9679 Total Recorded Trades</td>
                                    <td class="performance_recorded_trades ">0</td>
                                    <td class="total_buy_trades"></td>
                                    <td class="total_sell_trades"></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Winning Trades</td>
                                    <td class="performance_win_trades">0</td>
                                    <td class="buy_winning_trades">0</td>
                                    <td class="sell_winning_trades">0</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>&#9679 Losing Trades</td>
                                    <td class="performance_losing_trades">0</td>
                                    <td class="buy_losing_trades ">0</td>
                                    <td class="sell_losing_trades ">0</td>
                                </tr>
                                <tr>
                                    <td>&#9679 Win Rate</td>
                                    <td class="win_rate"></td>
                                    <td class="buy_win_rate"></td>
                                    <td class="sell_win_rate"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Trade Net Profit</td>
                                    <td class="trade_net_profit"></td>
                                    <td class="trade_net_profit_buy"></td>
                                    <td class="trade_net_profit_sell"></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Gross Profit</td>
                                    <td class="Gross_profit"></td>
                                    <td class="buy_Gross_profit"></td>
                                    <td class="sell_Gross_profit"></td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>&#9679 Gross Loss</td>
                                    <td><span class="Gross_loss"></span></td>
                                    <td><span class="buy_Gross_loss"></span></td>
                                    <td><span class="sell_Gross_loss"></span></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Profit Faktor</td>
                                    <td class="performance_profit_faktor">0</td>
                                    <td class="buy_porfit_factor"></td>
                                    <td class="sell_porfit_factor"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Av. Trade Net Profit</td>
                                    <td class="av_trade_net_profit"></td>
                                    <td class="av_net_profit_buy"></td>
                                    <td class="av_net_profit_sell"></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Winning Average</td>
                                    <td>
                                        <span class="performance_win_av"></span>
                                        <span>(<span class="performance_win_av_percent"></span>)</span>
                                        <span>(<span class="av_win_pips"></span>)</span>
                                    </td>
                                    <td class="buy_winnning_av"></td>
                                    <td class="sell_winnning_av">0</td>
                                </tr>
                                <tr class="">
                                    <td>&#9679 Losing Average</td>
                                    <td>
                                        <span class="performance_loss_av"></span>
                                        <span>(<span class="performance_loss_av_percent"></span>)</span>
                                        <span>(<span class="av_loss_pips"></span>)</span>
                                    </td>
                                    <td><span class="buy_losing_av">0</span></td>
                                    <td><span class="sell_losing_av">0</span></td>
                                </tr>
                                <tr class="">
                                    <td>&#9679 Largest Winning Trade</td>
                                    <td>
                                        <span class="max_win"></span>
                                        <span>(<span class="max_return"></span>)</span>
                                    </td>
                                    <td class="buy_max_win"></td>
                                    <td class="sell_max_win"></td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>&#9679 Largest Losing Trade</td>
                                    <td>
                                        <span class="max_lose"></span>
                                        <span>(<span class="max_lose_return"></span>)</span>
                                    </td>
                                    <td><span class="buy_max_lose">0</span></td>
                                    <td><span class="sell_max_lose"></span></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Best Winning Streak</td>
                                    <td class="win_streak">0</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Worst Losing Streak</td>
                                    <td class="lose_streak">0</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                                <tr>
                                    <td>&#9679 Duration Average</td>
                                    <td class="av_duration">0</td>
                                    <td class="buy_av_duration"></td>
                                    <td class="sell_av_duration"></td>
                                </tr>
                                <tr class="">
                                    <td>&#9679 Winnin Duration Average</td>
                                    <td class="av_duration_win"></td>
                                    <td class="buy_winning_duration_av">0</td>
                                    <td class="sell_winning_duration_av">0</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>&#9679 Losing Duration Average</td>
                                    <td class="av_duration_lose"></td>
                                    <td class="buy_losing_duration_av">0</td>
                                    <td class="sell_losing_duration_av">0</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Risk Reward Ratio Av.</td>
                                    <td class="performance_rrr_av">0</td>
                                    <td class="buy_av_RRR"></td>
                                    <td class="sell_av_RRR"></td>
                                </tr>
                                <tr>
                                    <td class="">&#9679 Trade Expected Return</td>
                                    <td class="performance_expected_return"></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&#9679 Portfolio Standard deviation</td>
                                    <td class="performance_standard_dev">0</td>
                                    <td></td>
                                    <td></td>

                                </tr>
                                <tr class="">
                                    <td>&#9679 Max. Drawdown</td>
                                    <td class="performance_drawdown">0</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-xl-6 right_performance_period ">


                    <div class="col-12 height smTableContainer shadow-sm rounded">
                        <div class="row row-tabs-datePerformance">
                            <ul class="nav nav-tabs date-performance-tabs" role="tablist">
                                <li class="nav-item active " id="today">
                                    <a href="#" id="today" class="btn btn-default period_date" data-toggle="tab">Today</a>
                                </li>
                                <li class="nav-item" id="week">
                                    <a href="#" id="" class="btn btn-default period_date" data-toggle="tab">Last Week</a>
                                </li>
                                <li class="nav-item " id="last_month">
                                    <a href="#" id="" class="btn btn-default period_date" data-toggle="tab">Last Month</a>
                                </li>
                                <li class="nav-item " id="tree_month">
                                    <a href="#" id="" class="btn btn-default period_date" data-toggle="tab">3 Months</a>
                                </li>
                                <li class="nav-item" id="sixt_months">
                                    <a href="#" id="" class="btn btn-default period_date" data-toggle="tab">6 Months</a>
                                </li>

                            </ul>
                        </div>

                        <div class="container_period_content" id="container_period">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {

        fetchCircle();
        portfolio_data();
        period();

        function fetchCircle() {
            var idPortfolio = $("#portfolios").data('value');
            $.ajax({
                url: "/loginregister.oop/trade_analysis/performance/circle.index.php",
                method: "POST",
                data: {
                    idPortfolio: idPortfolio
                },
                success: function(data) {
                    $('#winrate_container').html(data);
                }
            });
        }


        function period() {
            var idPortfolio = $("#portfolios").data('value');
            var date = $(".date-performance-tabs .active a").text();
            $.ajax({
                url: "/loginregister.oop/trade_analysis/performance/portfolioData.index.php",
                method: "POST",
                data: {
                    date: date,
                    idPortfolio: idPortfolio
                },
                success: function(response) {
                    $('#container_period').html(response);
                    $(".trdaes_today tr span:contains('sell')").css('color', 'var(--negative-red-color)');
                    $(".trdaes_today tr span:contains('buy')").css('color', 'var(--blue-text)');
                    $(".trdaes_today tr span:contains('-')").css('color', 'var(--negative-red-color)');
                }
            });
        }

        function portfolio_data() {
            var id = $("#portfolios").data('value');
            $.ajax({
                url: "/loginregister.oop/trade_analysis/performance/portfolioData.index.php",
                type: "POST",
                data: {
                    id: id
                },
                dataType: "JSON",
                success: function(data) {
                    $(".start_capital").text(data.start_capital);
                    $('.account_balance').text(data.total_equity);
                    $('.total_net_profit').text(data.total_net_profit);
                    $('.gained_pips').text(data.gained_pips);
                    $('.av_win_pips').text(data.av_win_pips);
                    $('.av_loss_pips').text(data.av_loss_pips);
                    $('.av_trade_net_profit').text(data.av_trade_net_profit);
                    $('.PL_ratio').text(data.PL_ratio);
                    $('.Gross_profit').text(data.Gross_profit);
                    $('.Gross_loss').text(data.Gross_loss);
                    $('.trade_net_profit').text(data.trade_net_profit);
                    $('.win_rate').text(data.win_rate);
                    $('.currency').text(data.currency);
                    //$('#currency2').text(data.currency);
                    $('.portfolio_name').text(data.portfolio_name);
                    //Performance portfolio data
                    $('.losing_rate').text(data.losing_rate);
                    $('.av_duration').text(data.av_duration);
                    $('.av_duration_win').text(data.av_duration_win);
                    $('.av_duration_lose').text(data.av_duration_lose);
                    $('.lose_streak').text(data.lose_streak);
                    $('.win_streak').text(data.win_streak);
                    $('.performance_return').text(data.return_capital_investmen);
                    $('.performance_net_profit').text(data.total_net_profit);
                    $('.performance_recorded_trades').text(data.total_recorded_trades);
                    $('.performance_win_trades').text(data.winning_trades);
                    $('.performance_win_av').text(data.winning_average);
                    $('.performance_win_av_percent').text(data.average_win_percent);
                    $('.performance_losing_trades').text(data.losing_trades);
                    $('.performance_loss_av').text(data.losing_average);
                    $('.performance_loss_av_percent').text(data.average_losing_percent);
                    $('.max_win').text(data.max_win);
                    $('.max_return').text(data.max_return);
                    $('.max_lose').text(data.max_lose);
                    $('.max_lose_return').text(data.max_lose_return);
                    $('.performance_expected_return').text(data.expected_return);
                    $('.performance_expected_return_currency').text(data.expected_return_currency);
                    $('.performance_winrate').text(data.win_rate);
                    $('.performance_profit_faktor').text(data.profit_factor);
                    $('.performance_rrr_av').text(data.average_rr_ratio);
                    $('.performance_standard_dev').text(data.standard_deviation);
                    $('.total_buy_trades').text(data.total_buy_trades);
                    $('.total_sell_trades').text(data.total_sell_trades);
                    $('.trade_net_profit_buy').text(data.trade_net_profit_buy);
                    $('.trade_net_profit_sell').text(data.trade_net_profit_sell);
                    /*buy sell*/
                    $('.buy_Gross_profit').text(data.buy_Gross_profit);
                    $('.sell_Gross_profit').text(data.sell_Gross_profit);
                    $('.buy_Gross_loss').text(data.buy_Gross_loss);
                    $('.sell_Gross_loss').text(data.sell_Gross_loss);
                    $('.av_net_profit_buy').text(data.av_net_profit_buy);
                    $('.av_net_profit_sell').text(data.av_net_profit_sell);
                    $('.buy_av_duration').text(data.buy_av_duration);
                    $('.sell_av_duration').text(data.sell_av_duration);
                    $('.buy_av_RRR').text(data.buy_av_RRR);
                    $('.sell_av_RRR').text(data.sell_av_RRR);
                    $('.buy_winning_trades').text(data.buy_winning_trades);
                    $('.sell_winning_trades').text(data.sell_winning_trades);
                    $('.buy_losing_trades').text(data.buy_losing_trades);
                    $('.sell_losing_trades').text(data.sell_losing_trades);
                    $('.buy_win_rate').text(data.buy_win_rate);
                    $('.sell_win_rate').text(data.sell_win_rate);
                    $('.buy_porfit_factor').text(data.buy_porfit_factor);
                    $('.sell_porfit_factor').text(data.sell_porfit_factor);
                    $('.buy_winnning_av').text(data.buy_winnning_av);
                    $('.sell_winnning_av').text(data.sell_winnning_av);
                    $('.buy_losing_av').text(data.buy_losing_av);
                    $('.sell_losing_av').text(data.sell_losing_av);
                    $('.buy_max_win').text(data.buy_max_win);
                    $('.sell_max_win').text(data.sell_max_win);
                    $('.buy_max_lose').text(data.buy_max_lose);
                    $('.sell_max_lose').text(data.sell_max_lose);
                    $('.buy_winning_duration_av').text(data.buy_winning_duration_av);
                    $('.buy_losing_duration_av').text(data.buy_losing_duration_av);
                    $('.sell_losing_duration_av').text(data.sell_losing_duration_av);
                    $(".table-performance tr td span:contains('-')").css('color', 'var(--negative-red-color)');
                }
            })
        }

        $(document).on('click', '.li-content', function() {
            //var idPortfolio = $("#portfolios").data('value');
            period();
            fetchCircle();
            portfolio_data()
        })

        $('.date-performance-tabs li').on('click', function() {
            period();
        });

        $('.row-tabs-datePerformance a').on('click', function() {
            $('.row-tabs-datePerformance').find('li.active').removeClass('active');
            $(this).parent('li').addClass('active');
        });
    });
</script>