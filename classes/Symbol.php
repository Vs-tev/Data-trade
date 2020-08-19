<?php
class Symbol{
    private $_db,
            $_data;
    
    public function __construct($filter) {
        $this->_db = DB::getInstance();
                if($this->getSymbol($filter)) {
                    return true;
                }
    }
    
    protected function getSymbol($filter) {
        
        $symbols = $this->_db->query("SELECT * FROM symbol WHERE symbol_name != '' $filter ");
        if($symbols->count()) {
                $this->_data = $symbols->results();
                return true;
             }
         return false;
    }
  
    public function data() {
        return $this->_data;
    }
}

