<?php


class Validate {
    private $_passed = false,
            $_errors = array(),
            $_db = null;
    private $errors = [];
    
    public function __construct() {
        $this->_db = DB::getInstance();
    }
    
    public function check($source, $items = array()) {
        foreach($items as $item => $rules) {
            foreach( $rules as $rule => $rule_value) {
                $value = trim($source[$item]);
                
                if($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if(!empty($value)){
                    switch($rule){
                        case 'min':
                          if(strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} hcaracters."); 
                          }
                        break;
                        case 'max':
                          if(strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} hcaracters."); 
                          }
                        break;
                        case 'valide_email':
                            if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$value} ist not correct email");
                            }
                        break;
                            
                        case 'letters_numbers':
                            if(!preg_match('/^[a-zA-Z0-9\s]*$/', $value)) {
                                $this->addError("Only letters and numbers allowed"); 
                          }
                        break;
                            
                        case 'numeric':
                            if(!is_numeric($value)) {
                                $this->addError("Only numbers allowed"); 
                            }
                        break; 
                            
                        case 'max_number_portfolios':
                            $chek = $this->_db->query("SELECT COUNT(*)as total FROM recording_account WHERE user_id = '".Session::get(Config::get('session/session_name'))."'");
                            foreach ($chek->results() as $data) {
                                    if($data->total >= $rule_value) {
                                        $this->addError("maximal 2 portfolio"); 
                                    }
                                }
                        break;
                            
                        case 'matches':
                            if($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}");
                            }
                        break;    
                           
                        case 'unique':
                            $chek = $this->_db->get($rule_value, array($item, '=', $value));
                            if($chek->count()) {
                                $this->addError("{$item} already exists.");
                            }
                            
                        break;
                            
                        case 'unique_user_update':
                            $chek = $this->_db->query("SELECT * FROM $rule_value WHERE $item = '$value' AND id != '".Session::get(Config::get('session/session_name'))."' ");
                            if($chek->count()) {
                                $this->addError("{$item} already exists.");
                            }    
                            
                        break; 
                            
                        case 'unique_item':
                            $chek = $this->_db->query("SELECT * FROM $rule_value WHERE $item = '$value' and user_id = '".Session::get(Config::get('session/session_name'))."'");
                            if($chek->count()) {
                                $this->addError("exists");
                            }
                        break;  
                        
                        case 'unique_item_edit':
                            $chek = $this->_db->query("SELECT * FROM $rule_value WHERE $item = '$value' AND id != '".$_POST['id']."' AND user_id = '".Session::get(Config::get('session/session_name'))."'");
                            if($chek->count()) {
                                $this->addError("exists");
                            }
                        break;
                            
                        case 'check_symbol':                       
                                $data = DB::getInstance()->get('symbol', array('symbol_name', '=', $value));
                                if(!$data->count()){
                                    $this->addError("Incorrect symbol");
                                }
                        break;
                            
                        case 'check_time_frame':
                            $time_frame = ['1 min', '5 min', '15 min', '30 min', '1 hour', '2 hours', '4 hours', '1 day', '1 week', '1 month'];
                            if(!in_array($value , $time_frame)) {
                                $this->addError("error time frame");
                            }
                        break;
                        
                        case 'check_type_side':
                            $type_side = ['buy', 'sell'];
                            if(!in_array($value , $type_side)) {
                                $this->addError("error Type side");
                            }
                        break;
                            
                        case 'chek_currency': 
                             $currency = ['EUR', 'USD', 'AUD', 'CAD', 'CHF'];
                                if(!in_array($value , $currency)) {
                                    $this->addError("error currency"); 
                                }
                        break;    
                            
                        case 'check_date': 
                            $chek = DB::getInstance()->query("SELECT action_date FROM balance WHERE recording_account_id = '$rule_value' and action_type = 'new_portfolio'");
                                foreach ($chek->results() as $date) {
                                    if($date->action_date > $value) {
                                        $this->addError("1"); 
                                    }
                                }
                        break;    
                        
                        case 'valide_date':    
                            if(DateTime::createFromFormat("Y-m-d", $value) == false) {
                              $this->addError("error date");  
                            }
                        break;
                            
                        case 'check_strategy_rule':    
                            $data = DB::getInstance()->get($item, array('id', '=', $rule_value),'AND user_id = '.Session::get(Config::get('session/session_name')).' ');
                                if(!$data->count()){
                                    $this->addError("Error item");
                                }
                        break;   
                          
                    }

                }
            }
        }
        if(empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }
    
    private function addError($error) {
        $this->_errors[] = $error;
        
    }
    
    
    public function errors() {
        return $this->_errors;
    }
    
    public function passed() {
        return $this->_passed;
    }
    
}