<?php
require_once '../../init.php';
//print_r($_SESSION['user']);
$id = $_SESSION['user'];
$portfolio = $_SESSION['portfolio'];



if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["trades"])) {
        if($_POST["trades"] == "load_trades") { 
            if($_POST['pl'] == 'win'){
                $sort = 'AND profit_loss_currency >= 0'; 
            } else{
                if($_POST['pl'] == 'loss'){
                    $sort = 'AND profit_loss_currency < 0'; 
                }else{
                    if($_POST['pl'] == 'all'){
                        $sort = ''; 
                    }
                }
            }
            $array = [$id, $portfolio, $sort];
            $trade = new Trades($array);
            $output = '<table class="table table-sm table-borderless mega-tabls-table-control" id="trade_list_tab"> 
                <thead class="">
                <tr>
                <th class="sort_col">Symbol<i class="material-icons sort_icon">unfold_more</i></th>
                <th class="">Type side</th>
                <th class="">P/L Currency</th>
                <th class="sort_col">Pips<i class="material-icons sort_icon">unfold_more</i></th>
                <th class="sort_col">Return %<i class="material-icons sort_icon">unfold_more</i></th>
                <th class="">Entry Price</th>
                <th class="">TP Price</th>
                <th class="">SL Price</th>
                <th class="">Entry date</th>
                <th class="">Exit date</th>
                <th class="sort_col">Duration<i class="material-icons sort_icon">unfold_more</i></th>
                </tr>
                </thead>
                <tbody class"scroll-tbody">';        
            
            if($trade->data()) {
                foreach($trade->data() as $row) {
                $output .= '
                <tr class="tr_class">
                <td class="trSymbol"><a href="#" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="material-icons deleteTrade" id='.$row->trade_ids.'>close</i></a>'.$row->symbol.'</td>
                <td class="type_side">'.$row->type_side.'<i class="material-icons type_side_arrow"></i></td>
                <td class="pl_currency">'.$row->profit_loss_currency.'</td> 
                <td class="pl_pips">'.$row->profit_loss_pips.'</td>
                <td class="return_trade">'.number_format($row->return_trade,2, '.','').'</td>
                <td class=""> '.$row->entry_price.'</td> 
                <td class=""> '.$row->tp_price.'</td> 
                <td class=""> '.$row->sl_price.'</td>
                <td class="entry_date"> '.$row->entry_date.'</td>
                <td class="exit_date"> '.$row->exit_date.'</td>
                <td class=""> '.$row->duration.'</td>
                </tr>';
                }         
            }else {
                $output .= '
                    <tr>
                    <td class="pl-5">No recorded Trades</td>
                    </tr>';
            }
        $output .= '</tbody></table>';
        echo $output;
        }
        if($_POST["trades"] == "Delete") {
            $user = DB::getInstance()->delete('trade_record', array('id', '=', $_POST['id']), 'AND recording_account_id = '.$portfolio.'');
        }    
    }
}

