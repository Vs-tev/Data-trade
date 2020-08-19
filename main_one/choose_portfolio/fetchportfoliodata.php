<?php
require_once '../../init.php';
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];


    
$data = new Element($portfolio, $id);
    if(isset($_POST["ids"])){
        foreach($data as $row){
            //echo $row->PortfolioPerformance($portfolio, $id); 
        }  
    }   
    echo $data->jsonReturn();

















