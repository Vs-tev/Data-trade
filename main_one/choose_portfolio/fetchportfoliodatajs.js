$(document).ready(function () {

    portfolio_data();

    function portfolio_data() {
        var ids = $('#hidden_portfolio').val();
        $.ajax({
            url: "choose_portfolio/fetchportfoliodata.php",
            method: "POST",
            data: {
                ids: ids
            },
            dataType: "JSON",
            success: function (data) {
                // tb color depend on value    
                $(".table-performance td:contains('-')").css('color', 'var(--negative-red-color)');
                $(".table-performance td:not(:contains('-'))").css('color', 'var(--main-text-color)');
                $(".performance_net_profit:contains('-')").css('color', 'var(--negative-red-color)');
                $(".performance_net_profit:not(:contains('-'))").css('color', 'rgb(128, 128, 128)');
                $('#total_equity').text(data.total_equity);
                $('#total_equity').data("equity", data.data_total_equity);
                $('#start_capital').text(data.start_capital);
                $('#currency1').text(data.currency);
                $('#currency2').text(data.currency);
                $('.portfolio_name').text(data.portfolio_name);
                $("#TradeRecord input").attr("disabled", false); // enable inputs 
                //Performance portfolio data
                $('#performance_return').text(data.return_capital_investmen);
                $('.performance_net_profit').text(data.total_net_profit);
                $('#performance_recorded_trades').text(data.total_recorded_trades);
                $('#performance_win_trades').text(data.winning_trades);
                $('#performance_win_av').text(data.winning_average);
                $('#performance_win_av_percent').text(data.average_win_percent);
                $('#performance_losing_trades').text(data.losing_trades);
                $('#performance_loss_av').text(data.losing_average);
                $('#performance_loss_av_percent').text(data.average_losing_percent);
                $('.performance_expected_return').text(data.expected_return);
                $('#performance_expected_return_currency').text(data.expected_return_currency);
                $('#performance_winrate').text(data.win_rate);
                $('#performance_profit_faktor').text(data.profit_factor);
                $('.performance_rrr_av').text(data.average_rr_ratio);
                $('#performance_standard_dev').text(data.standard_deviation);
            }
        })
    }

    fetchUserr();

    function fetchUserr() {
        var send = "Loadportfolio";
        $.ajax({
            url: "choose_portfolio/switching_portfolio.php",
            method: "POST",
            data: {
                send: send
            },
            success: function (data) {
                $('#portfolioResult').html(data);
            }
        });
    }

    $(document).on('click', '#card', function () {
        var id = $(this).find("#hidden_portfolio").val();
        var send = "sess_id";
        $.ajax({
            url: "choose_portfolio/switching_portfolio.php",
            method: "POST",
            data: {
                id: id,
                send: send
            },
            success: function (data) {
                portfolio_data();
                toggleChosePortfolio();
                drawChart();
                fetchCircle();
            }
        })
    });

    fetchCircle();

    function fetchCircle() {
        var send_data = "Loadcircle";
        $.ajax({
            url: "/loginregister.oop/main_one/performance/load_circle.php",
            method: "POST",
            data: {
                send_data: send_data
            },
            success: function (data) {

                $('#circle_container_result').html(data);
            }
        });
    }


    //on click event trigger fetch functions
    $(document).on('click', '#butsave , #save_deposit , .dell , #delete_trade_btn, #action', function () {
        //on trade record, deposit/withdraw or delete transaction fetch portfolio data    
        portfolio_data();
        drawChart();
        fetchUserr();
        fetchCircle();
    });
});