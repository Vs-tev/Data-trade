
<?php
require_once '../../init.php';

$id = $_SESSION['user'];

$getPortfolio = new PortfolioData($id);
$user_name = new User();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["send"])) {  
        if($_POST["send"] == "Loadportfolio") {    
            $output = '<div class="container">';
            if($getPortfolio->data()){
                foreach($getPortfolio->data() as $row) {
                $output .= '
                    <div class="col-9 portfolio_cards" id="card">
                    <span class="badge badge-light avatar-letter">'.$row->recording_account_name[0].'</span>
                    <div class="row">
                    <div class="header_card col-12">
                    <label class="portfolo_name_label">'.$row->recording_account_name.'</label><input type="hidden" name="sess_id" id="hidden_portfolio" value="'.$row->id.'">
                    </div>
                    <div class="balance_currency col-12">
                    <label class="equity">Balance</label>
                    <div class="balance">'.number_format($row->total,2, '.',' ').' '.$row->currency.'</div> 
                    </div>
                    </div>
                    <div class="col-12 m-0 p-0">
                    <label class="win_rate_label">Win Rate '.number_format($row->win_rate,2, '.',' ').'%</label>
                    <div class="progress">
                    <div class="progress-bar" style="width:'.$row->win_rate.'%"></div>
                    </div>
                    </div>
                    </div>';   
                }   
            }else{
                  $output .= '
                    <div class="col-12 text-center p-3">
                    <a href="#" id="create_portfolio_btn" class="btn btn-link border" onclick="openNav();">Create Portfolio</a>
                    </div>';   
                }
                $output.='</div>';
                echo $output;     
        }
        // chek if portfolio exists
        if($_POST["send"] == "sess_id") {
            $user = DB::getInstance()->query("SELECT *, COUNT(*)AS total FROM recording_account WHERE user_id = '$id' and id = '".$_POST["id"]."'  ");
            if($user->count()){
                Session::put($user_name->_sessionPortfolio, $_POST["id"]);  
            }
        }     
    }
}

if(Session::get(Config::get('session/session_portfolio')) == '' || Session::get(Config::get('session/session_portfolio')) == NULL) {  
     if($getPortfolio->data() == NULL) {
         echo "<script> openNavIfNoPOrtfolio();</script>";
     }else {
          if($getPortfolio->data() !== NULL){
           echo "<script> toggleChosePortfolio();</script>";
         }
     }
}

   



