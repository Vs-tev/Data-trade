<?php
class Trades{
    private $_db,
            $_data,
            $_num;
    
    public function __construct($array) {
        $this->_db = DB::getInstance();
            if($this->getTrades($array)) {
                return true;
            }
    }
    
    protected function getTrades($array) {
        $trades = $this->_db->query("SELECT id as trade_ids, symbol, (SELECT recording_account_name FROM recording_account WHERE id = recording_account_id)as portfolio, 
        quantity, type_side, profit_loss_currency, profit_loss_pips, entry_price,
        tp_price,
        (SELECT exit_reason FROM exit_reason WHERE id = exit_reason_id)as tprule ,sl_price,
        (SELECT GROUP_CONCAT((SELECT entry_rule FROM entry_rules WHERE entry_rules.id = recordet_trade_rule) SEPARATOR ', ')as used
        FROM recordet_trade_entry_rules WHERE recordet_trade_entry_rules.trade_id = trade_ids GROUP BY trade_ids)as entryrule,
        (SELECT strategy_name FROM strategy WHERE id = strategy_id)as strategy, entry_date, exit_date, 
        (SELECT return_trade FROM current_trade_performance WHERE user_id = '$array[0]' AND portfolio_id = '$array[1]'   AND trade_id = trade_record.id)as return_trade,
        (SELECT risk_reward_ratio FROM current_trade_performance WHERE user_id = '$array[0]' AND portfolio_id = '$array[1]'   AND trade_id = trade_record.id)as rr,
        (SELECT currency FROM recording_account WHERE user_id = '$array[0]' AND id = '$array[1]')as currency,
        DATEDIFF(exit_date, entry_date) AS duration,
        trade_commentar
        FROM 
        trade_record WHERE user_id = '$array[0]' AND recording_account_id = '$array[1]' $array[2]");
        
        if($trades->count()) {
                $this->_data = $trades->results();
                $this->_num = $trades->count(); 
                return true;
             }
         return false;
    }
    
    
    public function data() {
        return $this->_data;
    }
    
    public function count_trades() {
        return $this->_num;
    }

}

?>