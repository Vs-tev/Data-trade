<?php
require_once '../../init.php';
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];

$sort = '';
$table = new View();
$table->lineChart($portfolio, $sort);  
