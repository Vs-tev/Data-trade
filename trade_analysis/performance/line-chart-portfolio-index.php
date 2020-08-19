<?php
require_once '../../init.php';
$id = $_SESSION['user'];

$table = new View();

$portfolio = $_POST['idPortfolio'];
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

if($_POST['to_date'] != '' || $_POST['from_date'] != '') {
  $sort = 'WHERE action_date BETWEEN "'.$from_date.'" AND "'.$to_date.'"  ';
}else {
    $sort = 'GROUP by action_date ORDER by action_date asc';
}


$table->lineChart($portfolio, $sort);  