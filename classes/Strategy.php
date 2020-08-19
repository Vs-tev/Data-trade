<?php

class Strategy{
    private $_db,
            $_data;
    
    public function __construct($id) {
        $this->_db = DB::getInstance();
                if($this->getStrategy($id)) {
                    return true;
                }
    }
    
    protected function getStrategy($id){
        $user = $this->_db->query("SELECT * FROM strategy WHERE user_id = '$id' ");
        
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