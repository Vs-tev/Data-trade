<?php

class User {
    private $_db,
            $_data,
            $_sessionName,
            $_cookieName;
    
    public  $isLoggedIn,
            $_sessionPortfolio,
            $_sessionUsername;
        
           
    
    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        
        $this->_sessionName = Config::get('session/session_name');
        $this->_sessionPortfolio = Config::get('session/session_portfolio');
        $this->_sessionUsername = Config::get('session/session_username');
        $this->_cookieName = Config::get('remember/cookie_name');
        
        if(!$user) {
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if($this->find($user)) {
                    $this->isLoggedIn = true;
                }
            }
        } else {
            $this->find($user);
        }
    }
    
    public function update($fields = array(), $id = null) {
        
         if(!$id && $this->isLoggedIn()) {
             $id = $this->data()->id;
         }
        
        if(!$this->_db->update('user', $id, $fields)) {
            throw new Exception('There was a problem');
        }
    }
    
    public function create($fields = array()) {
        if(!$this->_db->insert('user', $fields)) {
            throw new Exception('There was a problem creating account.');
        }
    }
    
    public function find($user = NULL) {
         if($user) {
             $field = (is_numeric($user)) ? 'id' : 'email';
             $data = $this->_db->get('user', array($field, '=', $user));
             if($data->count()) {
                 $this->_data = $data->first();
                 return true;
             }
         }
         return false;  
     }
    
       public function findGoogle($user = NULL) {
         if($user) {
             $field = (is_numeric($user)) ? 'google_id' : 'email';
             $data = $this->_db->get('user', array($field, '=', $user));
             if($data->count()) {
                 $this->_data = $data->first();
                 return true;
             }
         }
         return false;  
     }
    
    
    public function login($username = null, $password = null, $remember = false) {
        if(!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
            //Session::put($this->_sessionPortfolio, $this->data()->email);
        } else {
             $user = $this->find($username);
             $portfolio = $this->find($username);
        if($user) {
            if(password_verify($password, $this->data()->password)){
               Session::put($this->_sessionName, $this->data()->id);
               Session::put($this->_sessionUsername, $this->data()->username);
               Session::put($this->_sessionPortfolio, NULL);    
               
                if($remember) {
                    $hash = Hash::unique();
                    $hashCheck = $this->_db->get('users_session', array('user_id', '=' , $this->data()->id));
                    if(!$hashCheck->count()) {
                        $this->_db->insert('users_session', array(
                        'user_id' => $this->data()->id,
                        'hash' => $hash    
                        ));
                    } else {
                        $hash = $hashCheck ->first()->hash;
                    }
                    Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                }
                return true;
            }
          }
        }    
        return false;
    }
    
    public function loginGoogle($username = null, $email = null, $id = null) {
        $user = $this->findGoogle($id);
        if($user){
            Session::put($this->_sessionName, $this->data()->id);
            Session::put($this->_sessionUsername, $this->data()->username);
            Session::put($this->_sessionPortfolio, NULL);    
        } else {
            if(!$user){
                $this->_db->insert('user', array(
                'username' => $username,
                'email' => $email,
                'google_id' => $id,
                'joined' => date('Y=m-d H:i:s'),    
                    ));
                $user = $this->findGoogle($id);
                Session::put($this->_sessionName, $this->data()->id);
                Session::put($this->_sessionUsername, $username);
                Session::put($this->_sessionPortfolio, NULL);   
            }
        }
    }
    
 
    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }
    
    public function logout() {
        $this->_db->delete('users_session', array('user_id', '=' , $this->data()->id));
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
        session_destroy();  
    }
    
    public function data() {
        return $this->_data;
    }
    
    public function isLoggedIn() {
        return $this->isLoggedIn;
    }
}