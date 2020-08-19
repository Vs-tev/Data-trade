<?php
class View extends Table{
    
    public function showUser1($array) {
        $result = $this->getUser($array);
    }
    
    public function data($item_id, $sql) {
        $result = $this->getData($item_id, $sql);
    }
    
    public function lineChart($protfolio, $sort){
        $result = $this->getLineChart($protfolio, $sort);
    }

}

