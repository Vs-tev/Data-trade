
<?php
require_once '../../../init.php';
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST["action"] == "save_deposit") {
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'depositInput' => array(
                'required' =>true,
                'min' => 1,
                'max' => 14,
                'numeric' => true,
            ),
            'action_date' => array(
                'required' =>true,
                'required' =>true,
                'valide_date' => true, 
                'check_date' => $_POST['id']
            ),
            ));
        if($validation->passed()) {
            $transaction = DB::getInstance()->insert('balance', array(
                'recording_account_id' => $_POST['id'],
                'tradeDepositValue' => $_POST['depositInput'],
                'action_date' => $_POST['action_date'],
                'action_type' => 'deposit-withdraw'
            ));
        } else {
            foreach($validation->errors() as $error) {
                exit($error);
            }
        }
    }
}

