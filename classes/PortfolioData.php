<?php

class PortfolioData{
    private $_db,
            $_data;
    
    public function __construct($id) {
        $this->_db = DB::getInstance();
                if($this->getPortfolioData($id)) {
                    return true;
                }
    }
    
    protected function getPortfolioData($id){
        $user = $this->_db->query("SELECT id, currency, recording_account_name, total, equity, start_date,  IFNULL((winning_trades/(total_recorded_trades)*100),0)as win_rate,((total - equity) / equity)*100 as RCI FROM
                (SELECT recording_account.id as id, recording_account.recording_account_name, recording_account.equity, recording_account.currency, balance.recording_account_id, 
                (SELECT action_date FROM balance WHERE action_type = 'new_portfolio' AND recording_account_id = recording_account.id)as start_date,
                SUM(tradeDepositValue) AS total,
                (SELECT SUM(CASE WHEN profit_loss_currency >= 0 THEN 1 else NULL END) FROM trade_record WHERE user_id = '$id' AND trade_record.recording_account_id = recording_account.id)winning_trades,
                (SELECT COUNT(id) FROM trade_record WHERE user_id = '$id' AND trade_record.recording_account_id = recording_account.id)total_recorded_trades
                FROM recording_account
                INNER JOIN balance ON recording_account.id = balance.recording_account_id WHERE user_id = '$id'
                GROUP BY recording_account_name)a");
        
        if($user->count()) {
                $this->_data = $user->results();
                return true;
             }
         return false;
    }
    
    public function data() {
        return $this->_data;
    }
     public function first() {
        return $this->data()[0];
    }
    
}