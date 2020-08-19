<?php
session_start();


$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1', 
        'username' => 'root',
        'password' => '',
        'db' => 'trading'
        
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => '604800',
    ),
    'session' => array(
        'session_name' => 'user',
        'session_username' => 'user_name',
        'session_email' => 'email',
        'session_portfolio' => 'portfolio',
        'token_name' => 'token'
    )
    
);


//autoloader (if include this file into differents directory helps to find the classes)
spl_autoload_register(function($class) {
    if (file_exists('classes/' . $class . '.php')) {
       require_once 'classes/' . $class . '.php';
    }elseif (file_exists('../classes/' . $class . '.php')) {
       require_once '../classes/' . $class . '.php';
    }
    elseif (file_exists('../../classes/' . $class . '.php')) {
       require_once '../../classes/' . $class . '.php';
    }
    elseif (file_exists('../../../classes/' . $class . '.php')) {
       require_once '../../../classes/' . $class . '.php';
    }
});

require_once 'functions/sanitize.php';

//here we check if cookie exist/ if yes it match to te DB cookie
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashChek = DB::getInstance()->get('users_session' ,array('hash' , '=', $hash));
    
    if($hashChek->count()){
       $user = new User($hashChek->first()->user_id);
       $user->login();    
    }
}

 if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
        Redirect::to('main_one/mainpage-traderecord.php');
            die();
    }else {
     if($_SERVER['REQUEST_METHOD']=='POST' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ){
         Redirect::to('main_one/mainpage-traderecord.php');
            die();
     }
 }
