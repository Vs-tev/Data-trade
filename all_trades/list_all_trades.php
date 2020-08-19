<?php
include '../init.php';

$title = "All Trades";
$style = "all_trades_style.css";

$user = new User();

$user = new User();
if(!$user->isLoggedIn() && (!isset($_SESSION["access_token"]))) {
    Redirect::to('../login.php');
}else{
$id = $_SESSION['user'];
$name = $_SESSION['user_name'];
$portfolio = $_SESSION['portfolio'];    
}
//print_r($_SESSION);
$analysis_alltrades = 'Trade Analysis';
$link = '/loginregister.oop/trade_analysis/trade_analisys.php';
    
$getPortfolio = new PortfolioData($id);   

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="/loginregister.oop/main_one/css-variable.css">
    <link rel="stylesheet" href="/loginregister.oop/navbar/navbar_top.css">
    <link rel="stylesheet" href="/loginregister.oop/transactions/transactionsstyle.css">

    <?php include '../head.php'?>
    <script src="/loginregister.oop/jquery/StyleSelectPlugin.js"></script>
    <script src="/loginregister.oop/jquery/main_one_jquery.js"></script>

   
</head>

<body>
    <?php include'../navbar/navbar_content.php' ?>

    <div class="container-fluid primary-container py-4">
        <div class="col-xl-10 col-md-12  m-auto">
            <h3 class="font-weight-400 mb-3">Trade History Dteils</h3>
            <!--Filter =================================-->
            <div class="row">
                <div class="col mb-2">
                    <form id="filterTransactions" method="POST">
                        <!--Date range--==========================-->
                        <div class="form-row">

                            <div class="col-lg-3  col-sm-12 col-md-12  form-group dateRangeContainer dropdiv">
                                <input id="sort_portfolio" data-toggle="dropdown" data-value="<?php echo $getPortfolio->first()->id; ?>" class="form-control styleSelect-search" type="text" placeholder="Choose Portfolio" value="<?php echo $getPortfolio->first()->recording_account_name; ?>">
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

                            <div class="col-lg-2 col-sm-12 col-md-4 mr-md-2  ml-lg-3 form-group dateRangeContainer dropdiv">
                                <input id="sort_by_profit" data-toggle="dropdown" class="form-control styleSelect-search" type="text" placeholder="Sort by:" value="All Trades">
                                <span class="icon-inside">
                                    <i class="material-icons">keyboard_arrow_down</i>
                                </span>
                                <div class="dropdown-menu shadow-sm rounded text-4">
                                    <ul>
                                        <li class="li-content"><span class="element-li">All Trades</span></li>
                                        <li class="li-content"><span class="element-li">Win trades</span></li>
                                        <li class="li-content"><span class="element-li">Losing Trades</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-12 col-md-3 ml-lg-3 form-group dateRangeContainer dropdiv">
                                <input id="sort_by" data-toggle="dropdown" class="form-control styleSelect-search" type="text" placeholder="Sort by:" value="">
                                <span class="icon-inside">
                                    <i class="material-icons">sort</i>
                                </span>
                                <div class="dropdown-menu shadow-sm rounded text-4">
                                    <ul>
                                        <li class="li-content"><span class="element-li">Date descending</span></li>
                                        <li class="li-content"><span class="element-li">Date ascending</span></li>
                                        <li class="li-content"><span class="element-li">Profit descending</span></li>
                                        <li class="li-content"><span class="element-li">Profit ascending</span></li>
                                        <li class="li-content"><span class="element-li">Return descending</span></li>
                                        <li class="li-content"><span class="element-li">Return ascending</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-auto d-flex mr-auto align-items-center form-group" data-toggle="collapse">
                                <a class="btn-link" data-toggle="collapse" href="#allFilters" aria-controls="allFilters">
                                    All Filters
                                    <i class="material-icons ml-2">tune</i>
                                </a>
                            </div>
                            <div class="col-auto d-flex ml-auto align-items-center form-group" data-toggle="collapse">
                                <button type="button" id="clearFilter" class="btn btn-link border font-weight-400 clearFilter_btn">
                                    Clear All Filters
                                    <i class="material-icons ml-1 align-middle clearFilter">close</i>
                                </button>
                            </div>


                            <!--Collapse  =================================-->


                            <div id="allFilters" class="col-12 pl-0 mt-4 collapse">
                                <div class="row p-0 m-0 ">
                                    <!--date input  =================================-->

                                    <div class="col-xl-2 col-lg-2  col-sm-4 col-md-3 pl-0 pr-2 ml-0 mr-3 form-group dateRangeContainer datediv">
                                        <input id="from-date" class="form-control date-allTrades " type="date" placeholder="date">
                                        <span class="icon-inside">
                                            <i class="material-icons">date_range</i>
                                        </span>
                                    </div>

                                    <div class="col-xl-2 col-lg-2 col-sm-4 col-md-3 pl-0 pr-2 ml-0 mr-3 form-group dateRangeContainer datediv">
                                        <input id="to-date" class="form-control date-allTrades " type="date">
                                        <span class="icon-inside">
                                            <i class="material-icons">date_range</i>
                                        </span>
                                    </div>

                                    <a href="#" id="load-date-btn" class="btn-link load_date_btn align-items-center">
                                        Load date
                                        <i class="material-icons">loop</i>
                                    </a>

                                    <!--result per page =================================-->



                                    <div class="custom-control custom-radio custom-control-inline my-auto">
                                        <input type="radio" class="custom-control-input ca" id="buy" name="typeside" value="buy">
                                        <label class="custom-control-label" for="buy">Buy</label>
                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline my-auto">
                                        <input type="radio" class="custom-control-input ca" id="sell" name="typeside" value="sell">
                                        <label class="custom-control-label" for="sell">Sell</label>
                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline my-auto">
                                        <input type="radio" class="custom-control-input ca" id="all" name="typeside" value="all" checked>
                                        <label class="custom-control-label" for="all">All</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Table trades =================================-->
        <!--Table header =================================-->
        <div class="col-xl-10 col-md-12 col-12 m-auto">
            <div class=" secondary-container py-4 shadow-sm rounded">
                <div class="row pb-1 mx-3">

                    <p class="result_per_page_p text-7 pl-1">Trades Per Pge</p>
                    <div class="col-lg-1 col-sm-3 col-md-2 mr-3 form-group dateRangeContainer dropdiv">
                        <input id="result_per_page" data-toggle="dropdown" class="form-control styleSelect-search" type="text" placeholder="Per page" value="10">
                        <span class="icon-inside">
                            <i class="material-icons">keyboard_arrow_down</i>
                        </span>
                        <div class="dropdown-menu shadow-sm rounded text-4">
                            <ul>
                                <li class="li-content"><span class="element-li">10</span></li>
                                <li class="li-content"><span class="element-li">25</span></li>
                                <li class="li-content"><span class="element-li">50</span></li>
                                <li class="li-content"><span class="element-li">100</span></li>
                                <li class="li-content"><span class="element-li">200</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 ml-auto mr-2  form-group dateRangeContainer search_symbol">
                        <input id="search" class="form-control" type="text" placeholder="Search symbol">
                        <span class="icon-inside">
                            <i class="material-icons">search</i>
                        </span>
                    </div>
                </div>

                <div class="table_container" id="table_container">
                    <!--Table body =================================-->
                </div>
            </div>
        </div>

    </div>
    <!--Modal Edit =================================-->


    <div class="modal fade " role="dialog" id="editTrade" data-backdrop="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-body p-0">
                    <div class="row no-gutters">
                        <div class="col-sm-3 d-flex justify-content-center rounded-left py-4 bg-color-blue">
                            <div class="my-auto text-center">
                                <div class="text-white my-2"><i class="material-icons text-8">monetization_on</i></div>
                                <h6 class="text-white font-weight-400 my-2">Profit <span class="currency_text"></span></h6>
                                <div class="text-4 font-weight-500 text-white mt-4 "><span class="profit_text"></span> <small>(<small class="return_text"></small>)</small></div>
                                <div class="text-4 font-weight-500 text-white pb-2"><span class="pips_text"></span> Pips</div>
                                <div class="text-7 font-weight-500 text-white pb-2">Close date <span class="exit_date_span"></span></div>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <h5 class="text-5 font-weight-400 m-3">Edit Trade, <span class="symbol_edit font-weight-500"></span>
                                <button type="button" class="close font-weight-400" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                            </h5>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">

                                    <div class="px-3">
                                          <p class="text-center font-weight-500 mb-1">Price</p>
                                        <ul class="list-unstyled pt-3">
                                            <li class="mb-5 d-flex align-items-center">Entry<input type="text" id="entry_price_edit" class="ml-auto form-control-sm w-50" value="568.5"></li>
                                            <li class="mb-5 d-flex align-items-center">Take profit<input type="text" id="tp_price_edit" class="ml-auto form-control-sm w-50" value="568.5"></li>
                                            <li class="mb-5 d-flex align-items-center">Stop loss<input type="text" id="sl_price_edit" class="ml-auto form-control-sm w-50" value="568.5"></li>
                                        </ul>
                                         
                                        <ul class="list-unstyled border-top pt-4">
                                            <li class="mb-5 d-flex align-items-center">Quantity<input type="text" id="quanity_edit" class="ml-auto form-control-sm w-50"></li>
                                            <li class="mb-5 d-flex align-items-center">Type Side
                                                <select class="custom-select" id="type_side">
                                                    <option value="buy">buy</option>
                                                    <option value="sell">sell</option>
                                                </select>
                                            </li>
                                        </ul>
                                       
                                      
                                        
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 pl-0">
                                    <p class="text-center font-weight-500 mb-1">Date</p>
                                        <ul class="list-unstyled pr-3 pt-3">
                                            <li class="mb-5 d-flex align-items-center pl-3">Entry
                                                <input type="date" id="entry_date_edit" class="form-control-sm">
                                            </li>
                                            <li class="mb-5 d-flex align-items-center pl-3">Exit
                                                <input type="date" id="exit_date_edit" class="form-control-sm"></li>
                                        </ul>
                                </div>
                                  
                            
                                 <div class="col-12 div_bottom">
                                        <button type="button" id="save_edit" class="btn btn-primary btn-sm">Save changes</button>
                                    </div>
                                
                                </div>
                          </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Delete =================================-->



    <div class="modal fade " id="deleteTrade" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content container-fluid">
                <div class="row">
                    <div class="modal-header border-bottom col-12">
                        <label>Delete Trade</label>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <div class="modal-body">
                    <p class="text-2 font-weight-500 pt-2"> Are you sure you want to delete this Trade?</p>
                    <p class="text-2 font-weight-400 "> You can't undo this action</p>
                </div>
                <div class="row p-4 modal-footer">
                    <input type="hidden" id="row_id">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary mr-3" id="delete_trade_btn" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {

        $('#table_container').html(make_skeleton());
        setTimeout(function() {
            load_data();
        }, 1000);

        function make_skeleton() {
            var output = '';
            for (var count = 0; count < 6; count++) {
                output += '<div class="ph-col-12" style="margin:auto">';
                output += '<div class="ph-item ph-row" style="margin:auto; border:none">';
                output += '<div class="ph-col-12 big"> </div>';
                output += '<div class="ph-col-12"></div>';
                output += '<div class="ph-col-12"></div>';
                output += '</div>';
                output += '<div>';
            }
            return output;
        }

        load_data();

        function load_data(page, query = '') {
            var load_trades = "load_trades";
            var sort_by = $("#sort_by").val();
            var pl = $("#sort_by_profit").val();
            var pageresult = $("#result_per_page").val();
            var idPortfolio = $("#sort_portfolio").data('value');
            var from_date = $("#from-date").val();
            var to_date = $("#to-date").val();
            var load_date = $("#load-date-btn").val();
            var type_side = $('input[type="radio"]:checked').val();
            $.ajax({
                url: "table-trades-index.php",
                method: "POST",
                data: {
                    load_trades: load_trades,
                    page: page,
                    query: query,
                    pl: pl,
                    idPortfolio: idPortfolio,
                    pageresult: pageresult,
                    sort_by: sort_by,
                    type_side: type_side,
                    from_date: from_date,
                    to_date: to_date,
                    load_date: load_date
                },
                success: function(data) {

                    $('#table_container').html(data);
                    $(".pl_pips").each(function() {
                        $(".pl_currency:contains('-')").css('color', 'var(--negative-red-color)');
                        $(this).html() < 0 ? $(this).css('color', 'var(--negative-red-color)') : null;
                    });
                    $(".return_trade").each(function() {
                        $(this).html() < 0 ? $(this).css('color', 'var(--negative-red-color)').append(" %") : $(this).append(" %");
                    });
                    var myGroup = $('.collapse_tr'); // close last collapsed tr
                    myGroup.on('show.bs.collapse', '.collapse', function() {
                        myGroup.find('.collapse.show').collapse('hide');
                    
                    });
                    $(".type_side:contains('buy')").css('color', 'var(--blue-text)').find('.type_side_arrow').text('keyboard_arrow_up').css('color', '#5f5fff');
                    $(".type_side:contains('sell')").css('color', 'var(--negative-red-color)').find('.type_side_arrow').text('keyboard_arrow_down').css('color', 'var(--negative-red-color)');
                }
            });
        }
        $('input[type="radio"]').click(function() {
            load_data();
        })
        $(document).on('click', '.li-content , #load-date-btn , #delete_trade_btn', function() {
            load_data();
        })

        $(document).on('click', '.page-link', function() {
            var page = $(this).data('page_number');
            var query = $('#search').val();
            load_data(page, query);

        });

        $('#search').keyup(function() {
            var query = $('#search').val();
            load_data(1, query);
        });

        $("#clearFilter").on('click', function() {
            $('#sort_by').val(null).trigger("change");
            $('#from-date').val('').trigger("change");
            $('#to-date').val('').trigger("change");
            $('#sort_by_profit').val('All Trades').trigger("change");
            $('#all').prop('checked', true);
            load_data();
        })

         $(document).on('click', '.save_commentar', function() {
             var id = $(this).attr("id");
             var save = "commentar";
             var commentar = $(".commentar_p[id="+id+"]").val();
            
             $.ajax({
                url: "table-trades-index.php",
                  method: "POST",
                data: {
                    id: id,
                    commentar: commentar,
                    save: save
                },
                  success: function(data) {
                      console.log(data)
                      
                  }
             })
             
         });
        /*Edit Trade**************************************/
        $(document).on('click', '.editTrade', function() {
            $('#editTrade').modal('show');
            var id = $(this).attr("id");
            var idd = $("#editTrade").val(id);
            var save = 'edit_item';
            $.ajax({
                url: "table-trades-index.php",
                method: "POST",
                data: {
                    id: id,
                    save: save
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $.each(response, function(index, element) {
                        $('.profit_text').text(element.profit_loss_currency);
                        $('.pips_text').text(element.profit_loss_pips);
                        $('.return_text').text(element.return_trade);
                        $('.currency_text').text(element.currency);
                        $('.exit_date_span').text(element.exit_date);
                        $('.symbol_edit').text(element.symbol);
                        $('#quanity_edit').val(element.quantity);
                        $('#entry_price_edit').val(element.entry_price);
                        $('#tp_price_edit').val(element.tp_price);
                        $('#sl_price_edit').val(element.sl_price);
                        $('#entry_date_edit').val(element.entry_date);
                        $('#exit_date_edit').val(element.exit_date);
                     
                    });
                    $(".profit_text, .pips_text").each(function() {
                        $(this).html() < 0 ? $(this).css('color', 'var(--negative-red-color2)') : $(this).css('color', 'white');
                    });
                    $(".return_text").each(function() {
                        $(this).html() < 0 ? $(this).css('color', 'var(--negative-red-color2)').append(" %") : $(this).append(" %").css('color', 'white');
                    });
                }
            });
        });
        
        
        $(document).on('click', '#save_edit', function() {
            
            $('.scrolableDiv li label').children('input[type=checkbox]').prop('checked', false);
            var id = $('#editTrade').val();
            var portfolio = $("#sort_portfolio").data('value');
            var save = 'save_edit_item';
            var type_side = $('#type_side').val();
            var quantity = $('#quanity_edit').val();
            var entry_price = $('#entry_price_edit').val();
            var tp_price = $('#tp_price_edit').val();
            var sl_price = $('#sl_price_edit').val();
            var entry_date = $('#entry_date_edit').val();
            var exit_date = $('#exit_date_edit').val();
            var strategy = $('#strategy option:selected').val();
            var exit_reason = $('#exit_reason_modal option:selected').val();
            $.ajax({
                url: "table-trades-index.php",
                method: "POST",
                data: {
                    id: id,
                    portfolio: portfolio,
                    save: save,
                    quantity: quantity,
                    entry_price: entry_price,
                    tp_price: tp_price,
                    sl_price: sl_price,
                    entry_date: entry_date,
                    exit_date: exit_date,
                    strategy: strategy,
                    type_side: type_side,
                    strategy: strategy,
                    exit_reason: exit_reason,
                  
                },
                success: function(data) {
                    console.log(data);
                    load_data();
                    $('#editTrade').modal('hide');

                }
            })
        });


        /*Delete Trade**************************************/

        $(document).on('click', '.deleteTrade', function() {
            var id = (this.id);
            var idd = $("#deleteTrade").val(id);
            var portfolio = $("#sort_portfolio").data('value');
            $('#deleteTrade').modal('show');
            $("#delete_trade_btn").click(function() {
                var id = $('#deleteTrade').val();
                var load_trades = $('#delete_trade_btn').text();
                $.ajax({
                    url: "table-trades-index.php",
                    method: "POST",
                    data: {
                        id: id,
                        portfolio: portfolio,
                        load_trades: load_trades,
                    },
                    success: function(data) {
                        console.log(data);
                        $('#deleteTrade').modal('hide');
                    }
                })
            })
        });





    });
</script>