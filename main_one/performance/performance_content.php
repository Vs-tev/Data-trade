<html>

<head>
    <link href="/loginregister.oop/main_one/performance/perfomance.css" rel="stylesheet">
</head>

<body>

    <div class="m-auto table-responsive performance-container">
        <table class="col-12 mega-tabls-table-contro table-performance">
            <tr>
                <td>Win Rate:</td>
                <td id="performance_winrate"></td>
            </tr>

            <tr>
                <td>Rate of Return %</td>
                <td id="performance_return"></td>
            </tr>

            <tr>
                <td>Total Net Profit:</td>
                <td class="performance_net_profit"></td>
            </tr>

            <tr>
                <td>Recorded Trades:</td>
                <td id="performance_recorded_trades"></td>
                <td></td>
            </tr>

            <tr>
                <td>Winning Trades:</td>
                <td id="performance_win_trades"></td>
            </tr>

            <tr class="group-for-border-bottom">
                <td >Profit Average:</td>
                <td id="performance_win_av"></td>
                <td id="performance_win_av_percent"></td>
            </tr>
            <tr>
                <td>Losing Trades:</td>
                <td id="performance_losing_trades"></td>
            </tr>

            <tr class="">
                <td>Losing Average:</td>
                <td id="performance_loss_av"></td>
                <td id="performance_loss_av_percent"></td>
            </tr>

            <tr>
                <td class="">Expected Return</td>
                <td id="performance_expected_return_currency"></td>
                <td class="performance_expected_return"> </td>
            </tr>

            <tr>
                <td>Profit Faktor:</td>
                <td id="performance_profit_faktor"></td>
            </tr>

            <tr>
                <td>Risk Reward Ratio Av.</td>
                <td class="performance_rrr_av"></td>
            </tr>

            <tr class="group-for-border-bottom">
                <td >Portfolio Standard deviation</td>
                <td id="performance_standard_dev"></td>
                <td></td>
            </tr>
        </table>


        <div class="col-12 performance-bottom">
            <a href="/loginregister.oop/trade_analysis/trade_analisys.php"  id="more" class="btn btn-link a-btn border float-left" > <i class="material-icons">poll</i>More Deteils</a>
        </div>

        <div  id="circle_container_result" class="col-12">
        </div>
    </div>
</body>

</html>
