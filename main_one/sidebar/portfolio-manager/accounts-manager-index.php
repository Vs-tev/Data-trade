<?php
// create account
require_once '../../../init.php';

$user = new User();
$id = $user->data()->id;
$portfolio = $_SESSION['portfolio'];

$getPortfolio = new PortfolioData($id);
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["action"])) {
        if($_POST["action"] == "Load") {        
            $output = '';
            $output .= '
            <table class="table table-sm table-borderless portfolio-table" id="portfolio-table">
                <thead class="">
                    <tr>
                        <th class="">Portfolio Name</span></th>
                        <th class="">Start Capital</th>
                        <th class="">Actual Balance</th>
                        <th class="">Return capital %</th>
                        <th class="">Start date</th>
                        <th class="">Edit</th>
                    </tr>
                </thead>
            <tbody>';
            if($getPortfolio->data()){
                foreach($getPortfolio->data() as $row) {
                    $output .= '
                    <tr class="">
                        <td class=""><a href="#" id="'.$row->id.'" class="delete"  data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="material-icons deleteportfolio">close</i></a><a href="#" class="update" id="'.$row->id.'">'.$row->recording_account_name.'</a></td>
                        <td class="equity">'.number_format($row->equity,2, '.',' ').' '.$row->currency.'</td>
                        <td class="">'.number_format($row->total,2, '.',' ').' '.$row->currency.'</td>
                        <td class="">'.number_format($row->RCI,2, '.',' ').' %</td>
                        <td class="">'.$row->start_date.'</td>
                        <td class="">
                        <a href="#" id="'.$row->id.'" class="update"><i class="material-icons btn-i">&#xe254;</i></a>
                        <a href="#" id="'.$row->id.'" class="depositwithdraw"  data-toggle="tooltip" data-placement="bottom" title="Deposit Withdraw" id="'.$row->id.'" ><i class="fas fa-exchange-alt mr-2" style="font-size:14px"></i></a>
                        </td>
                    </tr>';
                }
            } else {
                $output .= '
                <tr>
                <td align="center">Create Your First Portfolio</td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;

        }
        if($_POST["action"] == "Select" || $_POST["action"] == "deposit") {
            $table = new View;  
            $item_id = $_POST["id"];
            $sql = "SELECT * FROM recording_account, (SELECT action_date FROM balance WHERE recording_account_id = '".$_POST["id"]."' AND action_type = 'new_portfolio')as date,
                (SELECT SUM(tradeDepositValue)as total FROM balance WHERE recording_account_id = '".$_POST["id"]."')AS total
             WHERE user_id = '$id' AND id = ? LIMIT 1"; 
            $table->data($item_id, $sql);       
        } 
    
        if($_POST["action"] == "Save") {             
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
            'recording_account_name' => array(
            'required' => true,
                'min' => 1,
                'max' => 40,
                'unique_item_edit' => 'recording_account'
            ),
            'currency' => array(
                'required' => true,
                'chek_currency' => true
            )        
            ));
        if($validation->passed()) {
            $user = DB::getInstance()->update('recording_account', $_POST['id'], array(
                'recording_account_name' => $_POST['recording_account_name'],
                'currency' => $_POST['currency']
            ), 'AND user_id = '.$id.'');
        } else {
            foreach($validation->errors() as $error) {
                exit($error);
            }
        }
        }   
        if($_POST["action"] == "Delete") {  
            $user = DB::getInstance()->delete('recording_account', array('id', '=', $_POST['id']), 'AND user_id = '.$id.'');
            $session_portfolio = new User();
            Session::put($session_portfolio->_sessionPortfolio, NULL);
        }
    } 
    if(isset($_POST["action_create"])) {  
          $validate = new Validate();
            $validation = $validate->check($_POST, array(
            'recording_account_name' => array(
            'required' => true,
                'min' => 1,
                'max' => 40,
                'unique_item' => 'recording_account',
                'max_number_portfolios' => 2
            ),
            'currency' => array(
                'required' => true,
                'chek_currency' => true
            ),
            'equity' => array(
                'required' => true,
                'min' => 1,
                'max' => 16,
                'numeric' => true,
            ),
            'portfolio_date' => array(
                'required' => true,
                'valide_date' => true,
            ),    
            ));
        
            if($validation->passed()) {
                $portfolio = DB::getInstance()->insert('recording_account', array(
                    'recording_account_name' => $_POST['recording_account_name'], 
                    'equity' => $_POST['equity'],
                    'currency' => $_POST['currency'],
                    'user_id' => $id   
        ));
            $balance = DB::getInstance()->query("INSERT INTO balance (recording_account_id, trade_id, tradeDepositValue, action_date, action_type)
                VALUES ((SELECT id FROM recording_account WHERE recording_account_name = '".$_POST['recording_account_name']."'  AND user_id = '$id'), NULL,
                '".$_POST['equity']."', '".$_POST['portfolio_date']."', 'new_portfolio')"); 
                
                //put new portfolio in session
                $id_portfolio = DB::getInstance()->query("SELECT id FROM recording_account WHERE recording_account_name = '".$_POST['recording_account_name']."' ");
                    foreach($id_portfolio->results() as $row){
                        Session::put($user->_sessionPortfolio, $row->id);
                    }
        } else {
            foreach($validation->errors() as $error) {
                exit($error);
            }
        }
    } 
}
 
