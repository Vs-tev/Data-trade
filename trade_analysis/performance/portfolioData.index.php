<?php
require_once '../../init.php';
$id = $_SESSION['user'];

if(isset($_POST["id"])) {  
     if(is_numeric($_POST["id"])){
         $portfolio = $_POST["id"];
         $data = new Element($portfolio, $id);
         echo $data->jsonReturn(); 
     } 
}

if(isset($_POST["idPortfolio"])) {
    if(is_numeric($_POST["idPortfolio"])){
        $last_week = strtotime("-7 day");
        $last_month = strtotime("-1 month");
        $tree_month = strtotime("-3 month");
        $six_month = strtotime("-6 month");
    
        $portfolio = $_POST["idPortfolio"];
    
        if($_POST['date'] == 'Today'){
            $period = date("Y/m/d");
        } else {
            if($_POST['date'] == 'Last Week') {
                $period = date("Y/m/d", $last_week);

            }else {
                if($_POST['date'] == 'Last Month') {
                    $period = date('Y/m/d', $last_month);
                } else {
                    if($_POST['date'] == '3 Months') {
                    $period = date('Y/m/d', $tree_month);

                    } else {
                        if($_POST['date'] == '6 Months') {
                        $period = date('Y/m/d', $six_month);

                        }
                    }
                }
            }
        }
    }
    $curdate = date('Y/m/d');
    $sort = 'AND exit_date BETWEEN "'.$period.'" AND "'.$curdate.'" '; 
    $array = [$id, $portfolio, $sort];
    $trade = new Trades($array);
    
        
    $periods = new PeriodElement($portfolio, $id, $period);
    echo '<label class="text-2 font-weight-500 pt-2 mt-3">From: &nbsp'.$period.'</label>';
    
    $output = '
        <div class="row portfolio_info_row col-12 mb-2">
            <div class="info_boxes ">
                <label class="text-6 font-weight-400 ">Net Profit </label><br>
                <label class="output_label">'.$periods->data()->net_profit_period.'</label><span class="currency">'.$periods->data()->currency.'</span>
            </div>
            <div class="info_boxes ">
                <label class="text-6 font-weight-400">Gained Pips</label><br>
                <label class="output_label">'.$periods->data()->net_pips_period.'</label><span class="blue">Pips</span>
            </div>
            <div class="info_boxes ">
                <label class="text-6 font-weight-400">Return %</label><br>
                <label class="output_label">'.number_format($periods->data()->percent,2, '.','') .'</label><span class="blue">%</span>
            </div>
              <div class="info_boxes ">
                <label class="text-6 font-weight-400">Recorded Trades</label><br>
                      <label class="output_label">'.$periods->data()->recorded_trades.' </label>
                    <span class="win-lose"><span>Win </span><span>'.$periods->data()->winners_trade.'</span> / <span>Lose </span>'.$periods->data()->losers_trade.'<span></span></span>
                  </div>
              </div>' ;
                 
    $outputs = '
              <div class="col-12 container_trades ">
                  <table class="table table-sm table-performance trdaes_today">
                      <tbody>';
    
            if($trade->data()){ 
                foreach($trade->data() as $row) {
                    $outputs .= '
                        <tr>  
                            <td><span>'.$row->symbol.', </span><span>'.$row->type_side.' '.$row->quantity.'</span><br>
                                <span class="text-7">'.date('Y/m/d', strtotime($row->exit_date)).'</span>
                            </td>
                            <td><span class=" text-7">'.$row->profit_loss_currency.' '.$row->currency.',</span><span class=" text-7"> '.$row->profit_loss_pips.' Pips</span><br>
                                <span class=" text-7">'.$row->entry_price.'</span> - <span class=" text-7">'.$row->tp_price.'</span>
                            </td>
                           
                        </tr>';
                }
            } else {
                $outputs .= '<tr><td><h3>No Trades Found</h3></td></tr>';
                }
    $outputs .= '</tbody>
    </table>
    </div>
    <a href="/loginregister.oop/all_trades/list_all_trades.php"  id="more" class="btn btn-link a-btn border" > <i class="material-icons">poll</i>All Trades</a>';

    echo $output;
    echo $outputs;
}