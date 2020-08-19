<html>

<head>
    <link href="sidebar/deposit-withdraw/deposit_withdraw.css" rel="stylesheet">
</head>

<body>



    <!--- Deposit/Withdraw modal table--->
    <div class="modal fade" id="transactions_modal" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content container-fluid">
                <div class="row">
                    <div class="modal-header border-bottom col-12">
                        <label class="">Transaction Deteils</label>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="group_input_search">

                        <i class="material-icons search_transaction_deteils_i">search</i>

                        <input type="search" class="search" placeholder="Search...">

                    </div>
                    <div id="result_transactions_deteils">
                    </div>
                </div>
                <div class="row modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>


    <!--  modal if more than 2 recording accounts -->
    <div class="modal fade" id="if_more_than_2" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content container-fluid">
                <div class="row">
                    <div class="modal-header border-0 col-12">
                        <label>Get Premium Plan</label>
                    </div>
                </div>
                <div>
                    <div class="modal-body max_2_portfolio_modal_body">
                        <p>This is the maximum number Recording portfolios of Free plan.
                            Choose Premium plan and unlock up to 5 Recording portfolios and different measure tools.
                        </p>
                    </div>

                </div>
                <div class="row modal-footer border-0">
                    <button type="button" class="btn btn-primary">Test Free Trail</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function() {

        // Fetch transactions table    
        fetchtransactions();

        function fetchtransactions() {
            var load_transactions = "transactions"
            $.ajax({
                url: "sidebar/deposit-withdraw/transactionsdeteils.php",
                method: "POST",
                data: {
                    load_transactions: load_transactions
                },
                success: function(data) {
                    $('#result_transactions_deteils').html(data); //Make color red wehn "Capital Amount is negativ"
                    $(".total").each(function() {
                        $(this).html() < 0 ? $(this).css('color', 'rgb(255, 93, 93)') : null;
                    });
                },
            });
        }

        //delete transctions from transactions table  
        $(document).on('click', '.dell', function() {
            var id = $(this).attr("id");
            var load_transactions = "delete";
            $.ajax({
                url: "sidebar/deposit-withdraw/transactionsdeteils.php",
                method: "POST",
                data: {
                    id: id,
                    load_transactions: load_transactions
                },
                success: function(data) {
                    fetchtransactions();
                }
            })
        });

        $(document).on('click', '#save_deposit', function() { // on save deposit/withdraw fetch data
            fetchtransactions();
        });
    });
</script>