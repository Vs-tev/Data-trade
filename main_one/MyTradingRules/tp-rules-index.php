<?php
require_once '../../init.php';
$id = $_SESSION['user'];

$table = new View();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["save1"])) {
        if($_POST["save1"] == "loadingTp") {
            $sql = "SELECT a,b,d, c/d*100 as inpercent FROM 
                    (SELECT exit_reason as a, exit_reason.id as b, COUNT(CASE WHEN trade_record.profit_loss_currency > 0 then 1 ELSE NULL END)as c FROM exit_reason 
                    LEFT JOIN trade_record ON exit_reason.id = trade_record.exit_reason_id WHERE exit_reason.user_id = '$id' GROUP by exit_reason)a JOIN 
                    (SELECT exit_reason as e, exit_reason.id, COUNT(trade_record.exit_reason_id)as d FROM exit_reason 
                    LEFT JOIN trade_record ON exit_reason.id = trade_record.exit_reason_id WHERE exit_reason.user_id = '$id' GROUP by exit_reason)b
                    on a = e";
                    $id_table = 'takeprofit-table';
                    $edit = 'edit_exit_reason';
                    $delete = 'delete_exit_reason';
                    $th = '<tr>
                    <th class="sort_col">Exit Reason Name<i class="material-icons sort_icon">unfold_more</i></th>
                    <th class="sort_col">Used<i class="material-icons sort_icon">unfold_more</i></th> 
                    <th class="sort_col">In %<i class="material-icons sort_icon">unfold_more</i></th>
                    <th class="">Edit</th>
                    </tr>';
                    $array = [$id, $sql, $id_table, $edit, $delete ,$th];
                    $table->showUser1($array);     
        }

 //Create new Records
if($_POST["save1"] == "Create Rule"){
    $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'exit_reason' => array(
                'required' => true,
                'min' => 1,
                'max' => 40,
                'unique_item' => 'exit_reason'
            )
        ));
        if($validation->passed()){
            $user = DB::getInstance()->insert('exit_reason', array(
                'user_id' => $id, 
                'exit_reason' => $_POST['exit_reason']
            ));
        }else {
            foreach($validation->errors() as $error) {
                exit($error);
            }
        }  
}
  //fetch single customer data for display on Modal
if($_POST["save1"] == "select"){
    $item_id = $_POST["id"];
    $sql="SELECT id, exit_reason FROM exit_reason WHERE id = ? AND user_id = '$id' LIMIT 1"; 
    $table->data($item_id, $sql);
}

if($_POST["save1"] == "Update") {
$validate = new Validate();
    $validation = $validate->check($_POST, array(
        'exit_reason' => array(
            'required' => true,
            'min' => 1,
            'max' => 40,
            'unique_item_edit' => 'tp_rules'
        )
        ));
    if($validation->passed()) {
        $user = DB::getInstance()->update('exit_reason', $_POST['id'], array(
            'exit_reason' => $_POST['exit_reason']
            ), 'AND user_id = '.$id.'');
    } else {
        foreach($validation->errors() as $error) {
            exit($error);
        }
    }
}
if($_POST["save1"] == "Delete") {
    $user = DB::getInstance()->delete('exit_reason', array('id', '=', $_POST['id']), 'AND user_id = '.$id.'');
}
};
};
?>

