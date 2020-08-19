<?php
require_once 'init.php';

//google api
require_once('api/config.php');

$user = new User();
$user->logout();

unset($_SESSION["access_token"]);

Redirect::to('login.php');