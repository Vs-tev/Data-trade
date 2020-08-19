 <?php
require_once '../../../init.php';
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];

$getTransactions = new Transactions($id);
if(isset($_POST["load_transactions"])) {
  $output = '
   <table class="table table-borderless table-sm transactions_table" id="transactions_table">
     <thead class="">
        <tr>
        <th class="sort_col">Action Date<i class="material-icons sort_icon">unfold_more</i></th>
        <th class="sort_col">Account<i class="material-icons sort_icon">unfold_more</i></th>
        <th class="sort_col">Transfer<i class="material-icons sort_icon">unfold_more</i></th>
        <th class="">Currency</th>
        <th class="">Delete</th>
        </tr>
        </thead>
        <tbody>';
    if($getTransactions->data()) {
        foreach($getTransactions->data() as $row){  
    $output .= '
    <tr class="">
     <td class=" ">'.$row->action_date.' </td>
     <td class=" ">'.$row->recording_account_name.'</td>
     <td class=" total">'.number_format($row->tradeDepositValue,2, '.','').'</td>
     <td class=" ">'.$row->currency.'</td>
     <td >
     <a href="#" id="'.$row->balance_id.'" class="dell"><i class="material-icons btn-i">delete</i></a></td>
    </tr>';   
        }
    } else {
        $output .= '
        <tr>
        <td align="center">No Transactions</td>
        </tr>';
    }
    $output .= '</tbody></table>';
    echo $output;
       
    if($_POST["load_transactions"] == "delete") {
        $user = DB::getInstance()->delete('balance', array('id', '=', $_POST['id']), 'AND recording_account_id = '.$portfolio.'');
    } 
}
?>

<h3 class="no-results text-center text-muted" style="display:none">No Results Found!</h3>
<script>
    
    //Live Search input with "No result " Text
    
var $block = $('.no-results');
$(".search").keyup(function() {
    var val = $(this).val();
    var isMatch = false;
    $("#transactions_table tbody tr").each(function(i) {
        var content = $(this).html();
        if(content.toLowerCase().indexOf(val) == -1) {
           $(this).hide();           
        } else {
            isMatch = true;
            $(this).show(); 
        }
    });
    
    $block.toggle(!isMatch);
});
</script>
 

