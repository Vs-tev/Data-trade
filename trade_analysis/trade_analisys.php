<?php
include '../init.php';

$title = "Trade Deteils";
$style = "";
$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('../login.php');
}else{
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];    
}

$analysis_alltrades = 'All Trades';
$link = '/loginregister.oop/all_trades/list_all_trades.php';

$getPortfolio = new PortfolioData($id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/loginregister.oop/main_one/css-variable.css">
    <link rel="stylesheet" href="/loginregister.oop/trade_analysis/subnavbar-blue.css">

    <?php include '../head.php'?>
    <script src="/loginregister.oop/jquery/StyleSelectPlugin.js"></script>
    <script src="/loginregister.oop/jquery/main_one_jquery.js"></script>
</head>

<body>
    <?php include '../navbar/navbar_content.php' ?>
    <?php include 'subnavbar_blue.php' ?>

    <div class="tab-content container-fluid">

        <div role="tabpanel" class="tab-pane active col-12 " id="tab-performance">
            <?php include 'performance/analysis-performance.php' ?>
        </div>

        <!--Section-->

        <div role="tabpanel" class="tab-pane " id="tab-rules">
            Rules
        </div>

        <div role="tabpanel" class="tab-pane " id="strategy">
            Strategy
        </div>
    </div>
</body>

</html>

<script>
    $('.nabvar_blue .nav_blue a').on('click', function() {
        $('.nabvar_blue .nav_blue').find('li.active').removeClass('active');
        $(this).parent('li').addClass('active');
    });
</script>