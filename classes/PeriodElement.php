<?php
class PeriodElement{
    private $_db,
            $_data;
    
    public function __construct($portfolio, $id, $period) {
        $this->_db = DB::getInstance();
                if($this->Period($portfolio, $id, $period)) {
                    return true;
                }
    }
    
    protected function Period($portfolio, $id, $period) {
         
        $period = $this->_db->query("SELECT IFNULL(net_profit_period, 0.00)AS net_profit_period, IFNULL(net_pips_period, 0.0)AS net_pips_period, id, IFNULL(percent, 0)as percent, IFNULL(winners_trade, 0)as winners_trade, IFNULL(losers_trade, 0)as losers_trade,  IFNULL(recorded_trades, 0)AS recorded_trades, currency
        FROM(SELECT id, currency,
        (SELECT COUNT(id) FROM trade_record WHERE profit_loss_currency >= 0 and recording_account_id = '$portfolio' and exit_date BETWEEN '$period' AND CURDATE())as winners_trade,
        (SELECT COUNT(id) FROM trade_record WHERE profit_loss_currency < 0 and recording_account_id = '$portfolio' and exit_date BETWEEN '$period' AND CURDATE())as losers_trade,
        (SELECT SUM(profit_loss_currency) FROM trade_record WHERE user_id = '$id' and recording_account_id = '$portfolio' and exit_date BETWEEN '$period' AND CURDATE())as net_profit_period,
        (SELECT SUM(profit_loss_pips) FROM trade_record WHERE user_id = '$id' and recording_account_id = '$portfolio' and exit_date BETWEEN '$period' AND CURDATE())as net_pips_period,
        (SELECT SUM(return_trade) FROM current_trade_performance WHERE user_id = '$id' AND portfolio_id = '$portfolio' and action_date BETWEEN '$period' AND CURDATE()) as percent,
        (SELECT COUNT(*) FROM trade_record WHERE user_id = '$id' and recording_account_id = '$portfolio' and exit_date BETWEEN '$period' AND CURDATE())as recorded_trades
        FROM recording_account WHERE id = '$portfolio' and user_id = '$id' LIMIT 1)a");
     
    
     if($period->count()) {
                $this->_data = $period->first();
                return true;
             }
         return false;
    }
    
    public function data() {
        return $this->_data;
    }
    
    
}