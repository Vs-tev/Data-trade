<?php
include '../init.php';
//google api
require_once('../api/config.php');


$title = "Trade record";
$style = "mainpage-traderecord-style.CSS";

$user = new User();
if(!$user->isLoggedIn() && (!isset($_SESSION["access_token"]))) {
    Redirect::to('../login.php');
    $id = $_SESSION['user'];
}

//print_r($_SESSION['username']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../head.php'?>
    <link href="choose_portfolio/chose_portfolio.css" rel="stylesheet">
    <link href="css-variable.css" rel="stylesheet">
    <script src="/loginregister.oop/jquery/StyleSelectPlugin.js"></script>
    <script src="/loginregister.oop/jquery/main_one_jquery.js"></script>
    
        <!-- easySelect -->
    <script src="/loginregister.oop/Plugin/easySelect/easySelect.js"></script>
    <link href="/loginregister.oop/Plugin/easySelect/easySelectStyle.css" rel="stylesheet">
</head>

<body>
    <input type="hidden" id="token" name="token" value="<?php echo Token::generate(); ?>">
    <div id="cover"></div>
    <!-- sidebar-->
    <?php include('sidebar/sidebar.php'); ?>


    <!--page content-->
    <div class="main mr-1">
        <!---Container 1 ---->

        <div class="row m-0 pl-0 HauptRow">
            <div class="left tab-content tabs">
                <div class="col-12 accountInfoContainer tab-pane active" id="trade-left" role="tabpanel">

                    <div class="row" id="line_chart"></div>
                    <?php include'sparkline/sparkline.php';?>

                    <div class="row portfolio-name-row">
                        <span id="portfolio_name" class="portfolio_name"></span>
                    </div>
                    <div class="row  currency-balance">
                        <div class="balance_colum ">
                            <div class="balance_colum_inner">
                                <span id="total_equity" data-equity=""></span>
                                <span id="currency1" class=""> </span><br>
                               
                            </div>
                        </div>
                    </div>


                    <div class="container-2 row">
                        <ul class="col-12 nav nav-tabs pl-2 px-3" id="tab-button-Traderecord-Performance" role="tablist">
                            <li role="presentation" class="active"><a href="#Open" role="tab" data-toggle="tab">Recording Trade</a></li>
                            <li role="presentation" class="tab-li"><a href="#Close" role="tab" data-toggle="tab">Performance</a></li>
                        </ul>

                        <div class="col-12 tab-content tabs p-0 tab-div">
                            <div role="tabpanel" class="tab-pane active" id="Open">
                                <?php include ('trade-record/trade-record.php'); ?>

                            </div>
                            <div role="tabpanel" class="tab-pane" id="Close">

                                <?php include ('performance/performance_content.php'); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="chose_portfolio_tab tab-pane">
                    <?php include ('choose_portfolio/choose_portfolio.php'); ?>
                </div>


            </div>

            <!---Container 2 ---->
            <div class="col right container-fluid ml-1" id="right">


                <div class="row rowchart panel-top" id="rowchart">

                    <div class="col-12 chartContainer">
                        <div id="chart-panel"  style="display:">
                            <div class="dropdown dropdiv"> 
                                <input type="text" name="symbol" id="symbol" placeholder="Symbol" class="form-control styleSelect-search form-control-sm dropdown-toggle mt-1" data-toggle="dropdown" autocomplete="off">
                                <div class="dropdown-menu styleSelect col-12">
                                    <div class="styleSelect-list shadow-sm rounded">
                                        <ul class="styleSelect-list-ul">
                                          
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display:none">
                            <?php 
                           
                            
                        $data = new Element($portfolio, $id);
                                
                         echo $data->data()->max_win;  
                        
                        $a = new PortfolioData($id);
                         foreach($a->data() as $user) {
                                 echo  $user->RCI, '</br>';
                            }
                       
                        ?>
                        </div>
                    </div>
                    <div class="container" id="TradeInfoMessageContainer">
                    </div>
                    <div>
                    </div>
                </div>
                <div class="row rowtab pt-1" id="rowtab">
                    <div class="col-12 tradeContainer">
                        <div class="row nav-menu">
                            <ul class="col-12 nav hero_tabs nav-tabs p-0" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#StrategyOptions" data-toggle="tab" aria-controls="home" role="tab">My Strategy</a>
                                </li>
                                <li role="presentation">
                                    <a href="#TradingSettings" data-toggle="tab" aria-controls="home" role="tab">My Trading Rules</a>
                                </li>
                                <li role="presentation">
                                    <a href="#TradeHistory" data-toggle="tab" aria-controls="home" role="tab">Trade History</a>
                                </li>
                                <li class="li-min-max">
                                    <button type="button" class="btn btn-light btn-sm min-max-btn">
                                        <i class="material-icons min-max-icon">remove</i>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="row tab-content Mega-tabs">

                            <!--MY STRATEGY TAB--->

                            <div id="StrategyOptions" class="container-fluid tab-pane active">
                                <div class="row">
                                    <?php include 'my-strategy/mystrategy.php'?>
                                </div>
                            </div>


                            <!--MY TRADING RULES TAB-->

                            <div id="TradingSettings" class="container-fluid tab-pane fade">
                                <div class="row tab p-0" role="tabpanel">
                                    <ul class="col-12 nav nav-tabs p-0 ml-0 border-bottom" role="tablist">
                                        <li role="" class="active"><a href="#Entry-Rules" aria-controls="" role="tab" data-toggle="tab">ENTRY RULES</a></li>
                                        <li role=""><a href="#TP-Rules" aria-controls="" role="tab" data-toggle="tab">EXIT REASONS</a></li>
                                    </ul>
                                </div>
                                <div class="row trading-settings-tab">
                                    <div class="container-fluid tab-content tabs">


                                        <!--ENTRY Rules tab--->

                                        <div role=" tabpanel" class="row tab-pane active" id="Entry-Rules">
                                            <button type="button" id="new_rule_button" class="btn btn-link border new-rule-btn">Create Entry Rule</button>
                                            <?php include ('MyTradingRules/entryrules.php') ?>
                                        </div>
                                        <!--TAKE PROFIT Rules tab-->

                                        <div role="tabpanel" class="row tab-pane" id="TP-Rules">
                                            <button type="button" id="new_tp_button" class="btn btn-link border new-rule-btn">Create Exit Reason</button>
                                            <?php include ('MyTradingRules/tp-rules.php') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!---- TRADE HISTORY --->
                            <div id="TradeHistory" class="container-fluid tab-pane fade">
                                <div class="row">
                                    <?php include ('TradeHistory/trade_history_list.php') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
