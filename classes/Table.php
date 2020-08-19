<?php
class Table extends DB{
    
    protected function getUser($array) {
        $user = DB::getInstance()->query($array[1]);
        $output = '
        <table class="table table-sm table-borderless mega-tabls-table-control" id='.$array[2].'> 
        <thead >
        '.$array[5].'
        </thead>
        <tbody>';          
    if($user->count() > 0) {
        foreach($user->results() as $row) {
            $output .= '
                <tr >
                <td class=""><a href="#" class="'.$array[3].'" id="'.$row->b.'">'.$row->a.'</a></td>
                <td class=""> '.$row->d.'</td>
                <td class=""> <span>'.number_format($row->inpercent,2, '.','').'%</span></td>
                <td class="">
                <a href="#" class="'.$array[3].'" id="'.$row->b.'"><i class="material-icons btn-i">&#xe254;</i></a>
                <a href="#" id="'.$row->b.'" class="'.$array[4].'"><i class="material-icons btn-i">delete</i></a>
                </td>
                </tr>';
        }
    } else {
        $output .= '
        <tr>
        <td class="pl-5">Create Your First Strategy</td>
        </tr>';
    }
    $output .= '</tbody></table>';
        echo $output;
    }
    
    protected function getData($item_id, $sql) {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$item_id]);
        $result = $stmt->fetchAll();
        echo json_encode($result);
    }
        
    protected function getLineChart($portfolio, $sort){
     
    $data = DB::getInstance()->query("SELECT *
        FROM (
        SELECT 
        trade_record.profit_loss_pips,    
        return_trade,
        current_trade_performance.id, 
        portfolio_id,
        tradeDepositValue,
        balance.action_date, 
        SUM(tradeDepositValue) OVER(ORDER by action_date) AS Running,
            SUM(profit_loss_pips) OVER(ORDER by exit_date) AS pip,
         SUM(return_trade) OVER(ORDER by action_date) AS Retur
        FROM balance LEFT JOIN current_trade_performance on balance.trade_id = current_trade_performance.trade_id LEFT JOIN trade_record on balance.trade_id = trade_record.id WHERE 
        balance.recording_account_id = '$portfolio') t $sort");
        
   /*
   SQL 5.6 running total
   SELECT * FROM(
SELECT total,
ret,
action_date,
 @running_total:=@running_total + total AS Running,
 @runn:=@runn + ret as Retur
 FROM 
 (SELECT date(balance.action_date)as action_date,
 	SUM(tradeDepositValue)as total,
  	COALESCE((SUM(current_trade_performance.return_trade)), 0)as ret 
  FROM balance LEFT JOIN current_trade_performance on balance.trade_id = current_trade_performance.trade_id 
  WHERE balance.recording_account_id = '$portfolio' GROUP by action_date)s 
  JOIN(SELECT @running_total:=0, @runn:=0)r )f $sort
   
   */
        
    $rows = array();
    $table = array();
    $table['cols'] = array(
    array(
        'label' => 'Date', 
        'type' => 'date'
    ),
         array(
  'label' => 'Return %', //rec id
  'type' => 'number' //balance
 ),
    array(
        
        'label' => 'Portfolio', //rec id
        'type' => 'number' //balance
    )
    );

    foreach($data->results() as $row) {
        $return = number_format($row->Retur,2, '.','');
        $pips = number_format($row->pip,2, '.','') ;
        
        $sub_array = array();
        $t1 = strtotime($row->action_date);
        $year = date("Y",$t1);
        $month = date("m",$t1) - 1; // need to zero-index the month
        $day = date("d",$t1);
        $sub_array[] =  array(
            "v" => "Date($year, $month, $day)"
        );
        
          $sub_array[] =  array(
      "v" => $return // balance
     );
        
        $sub_array[] =  array(
            "v" => number_format($row->Running,2, '.','')  // balance
        );
        $rows[] =  array(
            "c" => $sub_array
        );
    }
    $table['rows'] = $rows;
        $jsonTable = json_encode($table);
        echo $jsonTable;    
    }
    
}

