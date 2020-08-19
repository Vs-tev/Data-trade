<?php
require_once '../../init.php';
$id = $_SESSION['user'];

$table = new View;

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST["save"])) {
        if($_POST["save"] == "load") {
            $sql="SELECT a,b,d, c/d*100 as inpercent FROM
                (SELECT strategy_name as a, strategy.id as b, COUNT(CASE WHEN trade_record.profit_loss_currency > 0 then 1 ELSE NULL END)as c FROM strategy LEFT JOIN trade_record ON strategy.id = trade_record.strategy_id WHERE strategy.user_id = '$id' GROUP by strategy_name)a JOIN
                (SELECT strategy_name as e, strategy.id, COUNT(trade_record.strategy_id)as d FROM strategy LEFT JOIN trade_record ON strategy.id = trade_record.strategy_id WHERE strategy.user_id = '$id' GROUP by id)b
                on a = e"; 
                $id_table = 'strategy-table';
                $edit = 'strategy_edit';
                $delete = 'delete_strategy';
                $th = '<tr>
                <th class="sort_col">Strategy Name<i class="material-icons sort_icon">unfold_more</i></th>
                <th class="sort_col">Total Trades<i class="material-icons sort_icon">unfold_more</i></th> 
                <th class="sort_col">Success %</th>  
                <th>Edit</th>
                </tr>';
                 $array = [$id, $sql, $id_table, $edit, $delete ,$th];
                $table->showUser1($array);     
            }

 // Create new Records
 if($_POST["save"] == "Create Strategy"){
    $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'strategy_name' => array(
                'required' => true,
                'min' => 1,
                'max' => 40,
                'unique_item' => 'strategy'
            ),
            'description' => array(
                'max' => 600,
            )
        ));
      
        if($validation->passed()){
            $user = DB::getInstance()->insert('strategy', array(
                'user_id' => $id, 
                'strategy_name' => $_POST['strategy_name'], 
                'description' => $_POST['description'],  
            ));
        } else {
            foreach($validation->errors() as $error) {
                exit($error);
            }
        }   
}
 //fetch single data for display on Modal
if($_POST["save"] == "Selectt"){
    $item_id = $_POST["id"];
    $sql="SELECT id, strategy_name as name, description FROM strategy WHERE  user_id = '".$id."' AND id = ? LIMIT 1"; 
    $table->data($item_id, $sql);
}
 
if($_POST["save"] == "Save"){
    $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'strategy_name' => array(
                'required' => true,
                'min' => 1,
                'max' => 40,
                'unique_item_edit' => 'strategy'
            ),
            'description' => array(
                'max' => 600,
            )
        ));
        if($validation->passed()) {
             $user = DB::getInstance()->update('strategy', $_POST['id'], array(
                 'strategy_name' => $_POST['strategy_name'],
                 'description' => $_POST['description'],
                ), 'AND user_id = '.$id.'');
        } else {
            foreach($validation->errors() as $error) {
                exit($error);
            }
        }
}
if($_POST["save"] == "Delete") {
    $user = DB::getInstance()->delete('strategy', array('id', '=', $_POST['id']), 'AND user_id = '.$id.'');  
}
};
};

?>

