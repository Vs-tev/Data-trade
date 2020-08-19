<script src="/loginregister.oop/jquery/Circle.js"></script>
<?php
require_once '../../init.php';

$id = $_SESSION['user'];     

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST["idPortfolio"]) {  
        $portfolio = $_POST["idPortfolio"];
            $data = new Element($portfolio, $id);
            if(!empty($data->data())) {
                $output = '<div class="circle_result" id="circle" data-percent="'.number_format($data->data()->win_rate,2, '.',' ').'">
                <label class="win_rate_lable_circle">Win Rate</label>
                </div>';        
            } else {
                $output = '<div class="circle_result" id="circle" data-percent="0">
                <label class="win_rate_lable_circle">Win Rate</label>
                </div>';
            }echo $output; 
    }
}
    



 
   
    
