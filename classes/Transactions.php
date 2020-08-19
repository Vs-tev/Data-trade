<?php
class Transactions{
   private $_db,
            $_data;
    
    public function __construct($id) {
        $this->_db = DB::getInstance();
            if($this->getTransactions($id)) {
                return true;
            }
    }
    
    protected function getTransactions($id) {
        $transaction = $this->_db->query("SELECT recording_account.id, recording_account.recording_account_name, recording_account.currency, 
            balance.recording_account_id, balance.action_date, balance.tradeDepositValue, balance.id AS balance_id
            FROM recording_account
            INNER JOIN balance ON recording_account.id = balance.recording_account_id WHERE user_id = '$id' AND action_type = 'deposit-withdraw' 
            ORDER BY action_date DESC");
        
        if($transaction->count()) {
                $this->_data = $transaction->results();
                return true;
             }
         return false;
    }
    
     public function data() {
        return $this->_data;
    }
}
