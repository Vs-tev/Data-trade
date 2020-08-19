<!---MODAL Create, Edit new Strategy WITH AJAX ---->
<html>

<head>
    <link href="TradeHistory/trade_history_tab.css" rel="stylesheet">
</head>

<body>
    <div class="table-row row col-12 drop_list_container">
        <div class="">
            <select id="pl" class="custom-select sort_trade_list_dropdown" style="width: 120px">
                        <option value="all" selected>All Trades</option>
                        <option value="win">Win trades</option>
                        <option value="loss">Losing Trades</option>
                    </select>
        </div>

        <div class="detailed_trade_statistic_div">
            <a href="/loginregister.oop/all_trades/list_all_trades.php" id="more_trade_deteils" class="btn btn-link a-btn border">Detailed Trade Statistics</a>
        </div>
    </div>



    <div class="modal fade " id="deleteTradeModal" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content container-fluid">
                <div class="row">
                    <div class="modal-header border-bottom col-12">
                        <label>Delete Trade<label class="symbol_trade"></label></label>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold text-center p-2"> Are you sure you want to delete this Trade</p>
                </div>
                <div class="row modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary mr-3" id="delete_trade_btn" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div id="trade_list_output" class="row table-responsive m-auto">
    </div>
</body>

</html>

<script>
    $(document).ready(function() {



        fetchTrades();

        function fetchTrades() {
            var trades = "load_trades";
            var pl = $("#pl").val();
            $.ajax({
                url: "TradeHistory/trade_history_list.index.php",
                method: "POST",
                data: {
                    trades: trades,
                    pl:pl
                },
                success: function(data) {
                    $('#trade_list_output').html(data);

                    $(".pl_pips, .pl_currency").each(function() {
                        $(this).html() < 0 ? $(this).css('color', 'var(--negative-red-color)') : null;
                    });

                    $(".return_trade").each(function() {
                        $(this).html() < 0 ? $(this).css('color', 'var(--negative-red-color)').append(" %") : $(this).append(" %");
                    });

                    $(".type_side:contains('buy')").css('color', '#5f5fff').find('.type_side_arrow').text('keyboard_arrow_up').css('color', '#5f5fff');
                    $(".type_side:contains('sell')").css('color', 'var(--negative-red-color)').find('.type_side_arrow').text('keyboard_arrow_down').css('color', 'var(--negative-red-color)');
                }
            });
        }
        $("#pl").on('change', function() { 
            fetchTrades()
        })    

        //Delete Trade
        $(document).on('click', '.deleteTrade', function() {
            var id = (this.id);
            var idd = $("#deleteTradeModal").val(id);
            $('#deleteTradeModal').modal('show');
            $("#delete_trade_btn").click(function() {
                var id = $('#deleteTradeModal').val();
                var trades = $('#delete_trade_btn').text();
                $.ajax({
                    url: "TradeHistory/trade_history_list.index.php",
                    method: "POST",
                    data: {
                        id: id,
                        trades: trades,
                    },
                    success: function(data) {
                        $('#deleteTradeModal').modal('hide');
                    }
                })
            })
        });

        $(document).on('click', '#butsave , #deleteTradeModal', function() { // on click Record trade, recordet trade in Trade History show 
            fetchTrades();
        })

        $(document).on('click', '#card', function() { //On choose portfolio show trades from selected portfolio
            var id = $(this).find("#hidden_portfolio").val();
            if (id != '') {
                fetchTrades();
            }
        });


        /*Drop down sort table*/
        $(".sort_trade_list_dropdown").on('change click', function() {
            optionval = $('.sort_trade_list_dropdown option:selected').val();
            //containtsminus = $("#trade_list_tab .tr_class:has(.pl_currency:contains('-'))");       
            //notcontaintsminus = $("#trade_list_tab .tr_class:has(.pl_currency:not(:contains('-')))");
            containtsminus = $("#trade_list_tab .tr_class").has(".pl_currency:contains('-')");
            notcontaintsminus = $("#trade_list_tab .tr_class").has(".pl_currency:not(:contains('-'))");
            alltrades = $("#trade_list_tab .tr_class:has(.pl_currency)");
            switch (optionval) {
                case "lose_trades":
                    containtsminus.show();
                    notcontaintsminus.hide();
                    break;

                case "win_trades":
                    containtsminus.hide();
                    notcontaintsminus.show();
                    break;

                case "all_trades":
                    alltrades.show();
                    break;

            }

        });

    });
</script>