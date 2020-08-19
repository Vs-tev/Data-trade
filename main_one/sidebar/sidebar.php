<html>
 <head>
  <link href="sidebar/sidebar_modal.css" rel="stylesheet">   
 </head>
 <body>

<div class="sidebar">
    <div class="dropdown dropright">
        
    <a href="#" class="btn btn-default dropdown-toggle sidebar_a" data-toggle="dropdown" ><i class="material-icons sidebar_a">dehaze</i></a> 
    <div class="dropdown-menu menu1">
    <a href="/loginregister.oop/update_profile/profileUpdate.php" class="dropdown-item">Profile <span class="badge badge-secondary avatar_letter"><?php echo $first_letter = escape($user->data()->username[0]); ?></span></a>    
    <a href="/loginregister.oop/trade_analysis/trade_analisys.php" class="dropdown-item analysis">Trade Analysis</a>    
    <a href="/loginregister.oop/all_trades/list_all_trades.php" class="dropdown-item">All Trades </a>
    <a href="#" id="choose_portfoliobtn" class="dropdown-item" onclick="openSwitchPortfolio()">Switch Portfolio</a>    
    <a href="/loginregister.oop/transactions/transactionsview.php" id="" class="dropdown-item" onclick="">Deposit and Withdrawal</a> 
    <div class="dropdown-divider"></div>    
    <a href="/loginregister.oop/logout.php" class="logout dropdown-item">Log out</a>
    </div>
  </div>
    
    <a href="#" id="rec_trade_panel_toggle" class="btn btn-default sidebar_a"  onclick="rec_trade_panel_toggle()"><i class="material-icons sidebar_a">developer_board</i> <span class="tooltiptext">Panel</span></a>
    
    <a href="#" id="create_portfolio_btn" class="btn btn-default sidebar_a" onclick="openNav()"><i class="material-icons">account_balance_wallet</i><span class="tooltiptext">Portfolio Manager</span></a>
    </div>      

    <?php include ('portfolio-manager/accountsmanager.php'); ?>
    <?php include ('sidebar/deposit-withdraw/deposit_withdraw.php'); ?>

</body>
</html>

