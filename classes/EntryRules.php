<?php

class EntryRules{
    private $_db,
            $_data;
    
    public function __construct($id) {
        $this->_db = DB::getInstance();
                if($this->getEntryRules($id)) {
                    return true;
                }
    }
    
    protected function getEntryRules($id){
        $user = $this->_db->query("SELECT * FROM entry_rules WHERE user_id = '$id' ");
        
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