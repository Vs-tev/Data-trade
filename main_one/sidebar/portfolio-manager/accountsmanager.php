<html>

<head>
    <link href="sidebar/portfolio-manager/PortfolioManager.css" rel="stylesheet">

    <!--Load the LineChart-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

    <!---CREATE NEW ACCOUNT(PORTFOLIO) ---->
    <form class="side container-fluid" method="post" action="sidebar/portfolio-manager/accounts-manager-index.php">
        <div class="row border-bottom ">
            <div class="col-12 fix">
                <div class="row portfolio-manager-header">
                    <label>Portfolio Manager</label>
                 <button type="button" class="close ml-auto closeaccountmanager" onclick="closeNav()">&times;</button>
                </div>
            </div>
        </div>

        <div class="portfoliocontent">
            
            <div class="row NewPortfolioInsputs ">
                
                <div class="col-lg-3">
                    <label class="input-label">Portfolio Name</label>
                    <input type="text" name="account_name" id="account_name" class="form-control mt-0">
                    <div style="height:20px" class=""><span class="error" id="error_accountname"></span></div>
                </div>
                <div class="col-lg-3">
                    <label class="input-label" id="asc_label">Amount Start Capital</label>
                    <input type="text" name="equity" id="equity" class="form-control mt-0" placeholder="">
                    <div style="height:10px" class=""><span class="error" id="error_equity"></span></div>

                </div>
                <div class="col-lg-3">
                    <label class="input-label">Start date</label>
                    <input type="date" class="form-control form-control-sm " id="portfolio_date">
                    <div style="height:10px" class=""><span class="error" id="error_portfolio_date"></span></div>
                </div>
                <div class="col-lg-3">
                    <label class="input-label">Currency</label>
                    <select type="text" class="custom-select mb-2 mt-0" name="currency" id="currency">
                        <option value="EUR" selected>EUR</option>
                        <option value="USD">USD</option>
                        <option value="CHF">CHF</option>
                        <option value="AUD">AUD</option>
                        <option value="CAD">CAD</option>
                    </select>
                </div>

                <div class="col-3 ml-0">
                    <button type="button" value="record" name="action" id="action" class="btn btn-primary ">Create</button>
                    <input type="hidden" name="customer_id" id="customer_id" />
                </div>
                
            </div>
            <div class="row mt-4">
                <div id="result" class="col-12 m-0 p-0 PortfolioTable">
                </div>
              <button type="button" id="transactions" class="btn btn-link mr-3 border transactions" data-toggle="modal" data-target="#transactions_modal">Transactions deteils</button>

            </div>
            <div class="chart">
                <div id="chart_div" class="col-12"></div>
                <?php include'sidebar/portfolio-manager/balkchart-portfolioperformance.php';?>

            </div>
        </div>

        <!-- Update Records Modal!-->

        <div id="Portfolio_Modal" class="modal fade" data-backdrop="false">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content container-fluid">
                    <div class="row">
                        <div class="modal-header border-bottom col-12">
                            <label class="modal-title"></label>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <label>Portfolio name</label>
                        <input type="text" name="account_name_modal" id="account_name_modal" class="form-control mt-0">
                        <div style="height:10px" class=""><span class="error" id="modal_error_accountname"></span></div>
                        <br />
                        <div class="row">
                            <div class="col-6">
                                <label class="portfolio_date_modal"></label>
                            </div>
                        </div>
                        <br />
                        <label>Currency</label>
                        <select type="text" class="custom-select mb-2 mt-0" name="currency_modal" id="currency_modal">
                            <option value="EUR" selected>EUR</option>
                            <option value="USD">USD</option>
                            <option value="CHF">CHF</option>
                            <option value="AUD">AUD</option>
                            <option value="CAD">CAD</option>
                        </select>

                    </div>
                    <div class="row modal-footer">
                        <input type="hidden" name="customer_id_modal" id="portfolio_id_modal" />
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="button" name="action_modal" id="action_modal" class="btn btn-primary mr-3">Save</button>
                    </div>
                </div>

            </div>
        </div>







    </form>

</body>

</html>
<script>
   

    $(document).ready(function() {
        $("#error_accountname").hide();
        $("#error_equity").hide();
        $("#error_portfolio_date").hide();
        $("#modal_error_accountname").hide();

        var modal_error_accountname = false;
        var error_accountname = false;
        var error_equity = false;
        var date_error = false;      

        $("#account_name_modal").focusout(function() {
            check_account_name_modal();
        });

        $("#account_name").focusout(function() {
            check_accountname();
        });

        $("#equity").focusout(function() {
            check_equity();
        });
        
       $("#portfolio_date").focusout(function() {
          check_date();
        });

        function check_accountname() {
            var check_accountname = $("#account_name").val().length;
            var $regexname = /^[\w]+([-_\s]{1}[a-z0-9]+)*$/i;

            if ((check_accountname < 3) || (check_accountname > 40) || (!$("#account_name").val().match($regexname))) {
                $("#error_accountname").html("Min 3 alpha-number charachters");
                $("#error_accountname").show();
                $("#account_name").addClass('invalid');
                error_accountname = true;
            } else {
                error_accountname = false;
                $("#error_accountname").hide();
                $("#account_name").removeClass('invalid');
            }
        }
        
        function check_date() {
             var date_portfolio = $("#portfolio_date").val();
             if(date_portfolio == ""){
                $("#error_portfolio_date").html("Choose date");
                $("#error_portfolio_date").show();
                date_error = true;
             }else {
                  date_error = false;
                 $("#error_portfolio_date").hide();
                
             }
        }

        function check_account_name_modal() {
            var check_modal_accountname = $("#account_name_modal").val().length;
            var $regexname = /^[\w]+([-_\s]{1}[a-z0-9]+)*$/i;

            if ((check_modal_accountname < 3) || (check_modal_accountname > 40) || (!$("#account_name_modal").val().match($regexname))) {
                $("#modal_error_accountname").html("Min 3 alpha-number charachters");
                $("#modal_error_accountname").show();
                $("#account_name_modal").addClass('invalid');
                modal_error_accountname = true;
            } else {
                modal_error_accountname = false;
                $("#modal_error_accountname").hide();
                $("#account_name_modal").removeClass('invalid');
            }
        }

        

        function check_equity() {
            var myVal = $("#equity").val().length;
            var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
            if (myVal.length < 1 || myVal.length > 16 || (!$("#equity").val().match($regex))) {
                $("#error_equity").html("Only numbers allow");
                //$("#action").attr('disabled', true);
                $("#error_equity").show();
                $("#equity").addClass('invalid');
                error_equity = true;
            } else {
                error_equity = false;
                $("#error_equity").hide();
                $("#equity").removeClass('invalid');
                //$("#action").attr('disabled', false);  
            }
        }

        /////////////////////////////////   
        fetchPortfolio();
        function fetchPortfolio() {
            var action = "Load";
            $.ajax({
                url: "sidebar/portfolio-manager/accounts-manager-index.php", //Request send to "AccountsManager.index.php"
                method: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    $('#result').html(data);
                    $(".equity").each(function() {
                        $(this).html() < 0 ? $(this).css('color', 'rgb(255, 93, 93)') : null;
                    });
                }
            });
        }

        /////////////////////////////  

        //for create portfolio

        //Click on Modal action button for Create new records or Update .
        $('#action').click(function() {
            check_accountname();
            check_equity();
            check_date();
            var action_create = $('#action').text();
            var recording_account_name = $('#account_name').val();
            var equity = $('#equity').val();
            var currency = $('#currency option:selected').text();
            var portfolio_date = $("#portfolio_date").val();
            var id = $('#customer_id').val();
            if (account_name != '' && equity != '' && currency != '' && portfolio_date != '' && error_accountname == false  && error_equity == false && date_error == false) {
                $.ajax({
                    url: "sidebar/portfolio-manager/accounts-manager-index.php",
                    method: "POST",
                    data: {
                        recording_account_name: recording_account_name,
                        equity: equity,
                        currency: currency,
                        portfolio_date: portfolio_date,
                        id: id,
                        action_create: action_create
                    },
                    success: function(response) {
                        console.log(response);
                        if(response == 'maximal 2 portfolio'){
                            $('#account_name').val('');
                            $('#equity').val('');
                            $("#portfolio_date").val('');
                            $("#if_more_than_2").modal('show');
                        }else if(response == 'exists') {
                            $("#error_accountname").html("This account name already exist");
                            $("#error_accountname").show();
                            $("#account_name").addClass('invalid');
                        } else {
                            $('#account_name').val('');
                            $('#equity').val('');
                            $("#portfolio_date").val('');
                            fetchPortfolio(); 
                            $(".closeaccountmanager").show();
                        }
                    }
                });
            }
        });


        //Update customer data
        $(document).on('click', '.update', function() {
            var id = $(this).attr("id");
            var action = "Select";
           
            $.ajax({
                url: "sidebar/portfolio-manager/accounts-manager-index.php",
                method: "POST",
                data: {
                    id: id,
                    action: action,
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(index, element) {
                        $('#account_name_modal').val(element.recording_account_name);
                        $('#currency_modal').val(element.currency);
                    });
                    $('#Portfolio_Modal').modal('show'); //It will display            
                    $('#portfolio_id_modal').val(id);
                    $('#equity_modal').prop('disabled', true);
                    $('#action_modal').text("Save");
                    $('.modal-title').text("Edit Portfolio");
                }
            });
        });

        //For edit portfolio
        $('#action_modal').click(function() {
            var recording_account_name = $('#account_name_modal').val();
            var currency = $('#currency_modal option:selected').text();
            var id = $('#portfolio_id_modal').val();
            var action = $('#action_modal').text();
            if (recording_account_name != '' && currency != '') {
                $.ajax({
                    url: "sidebar/portfolio-manager/accounts-manager-index.php",
                    method: "POST",
                    data: {
                        recording_account_name: recording_account_name,
                        currency: currency,
                        id: id,
                        action: action,
                        
                    },
                    success: function(response) {
                        if (response == 'exists') {
                            $("#modal_error_accountname").show();
                            $("#modal_error_accountname").html("This Portfolio name already exsist");
                            $("input.invalid").focus();
                        } else {
                            $('#Portfolio_Modal').modal('hide');
                            fetchPortfolio();
                            location.reload(); 
                        }
                    }
                });
            }
        });

        //This JQuery code is for Delete portfolio
        $(document).on('click', '.delete', function() {
            var id = (this.id);
            var idd = $("#deleteModal").val(id);
            $.ajax({
                cache: false,
                url: "sidebar/portfolio-manager/accounts-manager-index.php",
                method: "POST",
                data: {
                    id: id,
                    
                },
                success: function(data) {
                    $('#deleteModal').modal('show');
                }
            });
            //Delete Portfolio
            $("#delete_portfolio").click(function() {
                var id = $('#deleteModal').val();
                var action = $('#delete_portfolio').text();
                $.ajax({
                    url: "sidebar/portfolio-manager/accounts-manager-index.php",
                    cache: false,
                    method: "POST",
                    data: {
                        id: id,
                        action: action,
                       
                    },
                    success: function(data) {
                        location.reload();
                        fetchPortfolio();
                        $('#deleteModal').modal('hide');
                    }
                })
            })
        });






        /*Deposit-withdraw*/

        // Check deposit/withdraw
        $("#depositInput").keyup(function() { //lenght of value on currency input
            myVal = $(this).val();
            var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
            if (myVal.length > 13 || (!$("#depositInput").val().match($regex))) {
                $(this).val(myVal.substring(0, 13));
                $("#save_deposit").attr('disabled', true);
                $("#depositInput").tooltip({
                    title: "Invalide Value"
                }).tooltip('show');
                $("#depositInput").css({
                    'border': '1px solid rgba(255, 37, 37, 0.68)'
                });
                return false;
            } else {
                $("#depositInput").css({
                    'border': ''
                });
                $("#save_deposit").attr('disabled', false);
            }
        });

        $(document).on('input', function() {
            $("input#depositInput").blur(function() {
                var num = parseFloat($(this).val());
                var cleanNum = num.toFixed(2);
                $(this).val(cleanNum);
                cleanNum = isNaN(cleanNum) ? '0.00' : cleanNum;
                $(this).val(cleanNum);
                $(this).css({'border': ''});
                $("#depositInput").tooltip({
                    title: "Invalide Value"
                }).tooltip('dispose');
            });
        });

            //deposit/withdraw
        $(document).on('click', '.depositwithdraw', function() {
            var id = $(this).attr("id");
            var action = "deposit";
            $.ajax({
                url: "sidebar/portfolio-manager/accounts-manager-index.php",
                method: "POST",
                data: {
                    id: id,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#modal_deposit').modal('show');
                    $("#error_execute_date").hide();
                    $('#depositInput').val('');
                    $('#date_action').val('');
                    $('#deposit_withdraw_id_modal').val(id);
                }
            });
        });
        //For edit portfolio
        $('#save_deposit').click(function() {
            var depositInput = $('#depositInput').val();
            var action_date = $('#date_action').val();
            var id = $('#deposit_withdraw_id_modal').val();
            var action = "save_deposit"
            if (depositInput != '' && action_date != '') {
                $.ajax({
                    url: "sidebar/deposit-withdraw/manage-funds-index.php",
                    method: "POST",
                    data: {
                        depositInput: depositInput,
                        action_date: action_date,
                        id: id,
                        action: action
                    },
                    success: function(response) {
                        if (response == 1) {
                            $("#error_execute_date").show();
                            $("#error_execute_date").html("Date cannot be smaller than portfolio start date");
                        } else {
                            $('#modal_deposit').modal('hide');
                            $("#error_execute_date").hide();
                            $('#depositInput').val('');
                            $('#date_action').val('');
                            fetchPortfolio();
                        }
                    }
                });
            }
        });

        $(document).on("click", "#butsave , .dell, #delete_trade_btn", function() {
            //on trade record portfolio data
            fetchPortfolio();
        });
    });
</script>

<!--- Modal Manage Funds Deposit/Withdraw----->
<div class="modal fade" id="modal_deposit" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid">
            <div class="row fas">
                <div class="modal-header border-bottom col-12">
                    <label class="" style="">Deposit / Withdraw</label>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-5 mt-2">
                        <span id="action_date"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 ">
                        <label>Deposit / Withdraw</label>
                        <input type="text" class="form-control mt-0 " id="depositInput" placeholder="0">
                        <div style="height:10px" class=""><span class="error" id="error_depositInput"></span></div>
                    </div>
                    <div class="col-6">
                        <label class="mb-0">Transaction Date</label>
                        <input type="date" id="date_action" class="form-control">
                        <div style="height:10px" class=""><span class="error" id="error_execute_date"></span></div>
                    </div>
                </div>

            </div>
            <div class="row modal-footer footer-depositwithdraw">
                <input type="hidden" name="deposit_withdraw_id_modal" id="deposit_withdraw_id_modal">
                <button type="button" id="save_deposit" name="save_deposit" class="save_deposit btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>


<!--- Delete Portfolio Modal----->

<div class="modal fade " id="deleteModal" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container-fluid">
            <div class="row">
                <div class="modal-header border-bottom col-12">
                    <label class="modal-title">Delete Portfolio</label>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold text-center p-2"> Are you sure you want to delete this portfolio?
                    This will delete all your recordet trades with this portfolio!
                </p>
            </div>
            <div class="row modal-footer">
                <button type="button" class="btn btn-light " data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary mr-3" id="delete_portfolio" type="button">Delete</button>
            </div>
        </div>
    </div>
</div>