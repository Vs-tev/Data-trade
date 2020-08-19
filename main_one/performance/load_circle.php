<script src="/loginregister.oop/jquery/Circle.js"></script>
<?php
require_once '../../init.php';
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];

$data = new Element($portfolio, $id);

 //dounat chart
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
         
    if(isset($_POST["send_data"])) {  
        if($_POST["send_data"] == "Loadcircle") {
            if(!empty($data->data())) {
                $output = '<div class="circle_result" id="circle" data-percent="'.number_format($data->data()->win_rate,2, '.',' ').'">
                <label class="win_rate_lable_circle">Win Rate</label>
                </div>';        
            } else {
                $output = '<div class="circle_result" id="circle" data-percent=" ">
                <label class="win_rate_lable_circle">Win Rate</label>
                </div>';
            }
        }echo $output; 
    } 
}