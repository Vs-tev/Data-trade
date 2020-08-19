 <?php
require_once '../../init.php';

$id = $_SESSION['user'];

$portfolio = $_SESSION['portfolio'];

$data = DB::getInstance()->query("SELECT AVG(return_trade)as av_return FROM current_trade_performance WHERE portfolio_id = '$portfolio' and user_id = '$id' ");
    foreach($data->results() as $row) {
        $av_return = $row->av_return;
  }

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['save'])){
       
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'symbol' => array(
                'min' => 1,
                'max' => 16,  
                'check_symbol' => true,
            ),
            'quantity' => array(
                'min' => 1,
                'max' => 16,
                'numeric' => true,
            ),
            'type_side' => array(
                'check_type_side' => true,
            ),
            'time_frame' => array(
                'check_time_frame' => true,
            ),
            'entry_price' =>array(
                'min' => 1,
                'max' => 14,
                'numeric' => true,
                'required' =>true,
            ),
            'tp_price' =>array(
                'min' => 1,
                'max' => 14,
                'numeric' => true,
                'required' =>true,
            ),
            'sl_price' =>array(
                'min' => 1,
                'max' => 14,
                'numeric' => true,
                'required' =>true,
            ),
            'commentar' =>array(
                'max' => 600,
            ),
            'entry_date' =>array(
                'required' =>true,
                'valide_date' => true, 
                'check_date' => $portfolio
            ),
            'exit_date' =>array(
                'required' =>true,
                'valide_date' => true,
                'check_date' => $portfolio
            ),
            'profit_loss_currency' =>array(
                'required' => true,
                'min' => 1,
                'max' => 14,
                'numeric' => true,
            ),
            'profit_loss_pips' =>array(
                'required' => true,
                'min' => 1,
                'max' => 9,
                'numeric' => true,
            ),
        ));
        
        $a = new Element($portfolio, $id);
        $returnpertrade =  ($_POST["profit_loss_currency"] / $a->data()->total) * 100;
        
        if($validation->passed()) {
        $entry_rules = !empty($_POST['entry_rules']) ? $_POST['entry_rules'] : NULL;
        $entry_price = $_POST['entry_price']; 
        $tp_price = $_POST['tp_price'];
        $sl_price = $_POST['sl_price'];    
        $risk_reward_ratio = ($entry_price - $tp_price)/($sl_price - $entry_price );    
        $pow = POW(($av_return - $returnpertrade),2);
        
       
     
      $trade = DB::getInstance()->insert('trade_record', array(
        'user_id' => $id, 
        'recording_account_id' => $portfolio, 
        'strategy_id' => !empty($_POST['strategy']) ? $_POST['strategy'] : NULL,
        'exit_reason_id' => !empty($_POST['tp_rule']) ? $_POST['tp_rule'] : NULL,
        'symbol' => $_POST['symbol'],
        'type_side' => strip_tags($_POST['type_side']), 
        'quantity' => $_POST['quantity'],  
        'entry_price' => $_POST['entry_price'], 
        'tp_price' => $_POST['tp_price'], 
        'sl_price' => $_POST['sl_price'],
        'time_frame' => $_POST['time_frame'],
        'trade_commentar' => $_POST['commentar'],
        'entry_date' => $_POST['entry_date'],
        'exit_date' => $_POST['exit_date'],
        'profit_loss_currency' => $_POST['profit_loss_currency'], 
        'profit_loss_pips' => $_POST['profit_loss_pips'], 
    ));

    $balance = DB::getInstance()->query("INSERT INTO balance(recording_account_id, trade_id, tradeDepositValue, action_date, action_type) 
        VALUES ( '$portfolio', 
        (SELECT id FROM trade_record WHERE user_id = '$id' ORDER BY id DESC LIMIT 1), 
        '".$_POST['profit_loss_currency']."','".$_POST['exit_date']."', 'trade')");
 
  
    $current_trade_performance = DB::getInstance()->query("INSERT INTO current_trade_performance(user_id, portfolio_id, trade_id, return_trade, risk_reward_ratio, action_date)
        VALUES ('$id', '$portfolio', 
        (SELECT id FROM trade_record WHERE user_id = '$id' ORDER BY id DESC LIMIT 1), '$returnpertrade', '$risk_reward_ratio' , '".$_POST['exit_date']."')");
    
    foreach((array)$entry_rules as $val){
        $sql = "INSERT INTO recordet_trade_entry_rules(recordet_trade_rule, trade_id) 
        VALUES ('$val', 
        (SELECT id FROM trade_record WHERE user_id = '$id' ORDER BY id DESC LIMIT 1))";  
        $entryRules = DB::getInstance()->query($sql);
    }        
            
            
    $st_dev = DB::getInstance()->query("INSERT INTO `standart_dev` (`user_id`, `trade_id`, `portfolio_id`, `pow_2`) VALUES ('$id', (SELECT id FROM trade_record WHERE user_id = '$id' ORDER BY id DESC LIMIT 1), '$portfolio', '$pow');");

    }else {
           foreach($validation->errors() as $error) {
                exit($error);
            }  
        }
    }
}

?>