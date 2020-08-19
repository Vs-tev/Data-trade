<?php
require_once '../../init.php';
$id = $_SESSION['user'];

$table = new View();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["saving"])) {
        if($_POST["saving"] == "loading") {
            $sql = "SELECT a,b,d, c/d * 100 as inpercent FROM
                    (select entry_rules.entry_rule as a, entry_rules.id as b, COUNT(CASE WHEN trade_record.profit_loss_currency > 0 then 1 ELSE NULL END)as c FROM entry_rules LEFT JOIN recordet_trade_entry_rules on entry_rules.id = recordet_trade_entry_rules.recordet_trade_rule LEFT JOIN trade_record on recordet_trade_entry_rules.trade_id = trade_record.id
                    WHERE entry_rules.user_id = '$id' GROUP BY a)a JOIN
                    (SELECT entry_rules.entry_rule as e, COUNT(trade_record.profit_loss_currency )as d FROM entry_rules LEFT JOIN recordet_trade_entry_rules on entry_rules.id = recordet_trade_entry_rules.recordet_trade_rule LEFT JOIN trade_record on recordet_trade_entry_rules.trade_id = trade_record.id
                    WHERE entry_rules.user_id = '$id' GROUP BY e)e
                    on a = e";
                    $id_table = 'entryrule-table';
                    $edit = 'edit_entryRule';
                    $delete = 'delete_entryRule';
                    $th = '<tr>
                    <th class="sort_col">Entry Rules Name<i class="material-icons sort_icon">unfold_more</i></th>
                    <th class="sort_col">Used<i class="material-icons sort_icon">unfold_more</i></th> 
                    <th class="sort_col">Succeed on Trade %<i class="material-icons sort_icon">unfold_more</i></th>
                    <th class="">Edit</th>
                    </tr>';
                    $array = [$id, $sql, $id_table, $edit, $delete ,$th];
                    $table->showUser1($array);     
        }

 //Create new Records
if($_POST["saving"] == "Create Rule") {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
        'entry_rule' => array(
            'required' => true,
            'min' => 1,
            'max' => 40,
            'unique_item' => 'entry_rules'
        )
        ));
    if($validation->passed()){
        $user = DB::getInstance()->insert('entry_rules', array(
            'user_id' => $id, 
            'entry_rule' => $_POST['entry_rule']
        ));
    }else {
        foreach($validation->errors() as $error) {
            exit($error);
        }
    } 
}
    
 //This Code is for fetch single customer data for display on Modal
if($_POST["saving"] == "selectt") {
    $item_id = $_POST["id"];
    $sql="SELECT id, entry_rule FROM entry_rules WHERE id = ? AND user_id = '$id' LIMIT 1"; 
    $table->data($item_id, $sql);
}

if($_POST["saving"] == "Update") { 
$validate = new Validate();
    $validation = $validate->check($_POST, array(
        'entry_rule' => array(
            'required' => true,
            'min' => 1,
            'max' => 40,
            'unique_item_edit' => 'entry_rules'
        )
        ));
    if($validation->passed()) {
        $user = DB::getInstance()->update('entry_rules', $_POST['id'], array(
            'entry_rule' => $_POST['entry_rule']
            ), 'AND user_id = '.$id.'');
    } else {
        foreach($validation->errors() as $error) {
            exit($error);
        }
    }  
}
if($_POST["saving"] == "Delete") {
    $user = DB::getInstance()->delete('entry_rules', array('id', '=', $_POST['id']), 'AND user_id = '.$id.'');
}
}
}
?>

