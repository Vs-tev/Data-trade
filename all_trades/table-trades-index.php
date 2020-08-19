    <?php
    require_once '../init.php';

    $id = $_SESSION['user'];
    //$portfolio = Session::get(Config::get('session/session_portfolio'));

if($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    
 if(isset($_POST["load_trades"])) {
        if($_POST["load_trades"] == 'load_trades') {

    $limit = is_numeric($_POST['pageresult']) ? $_POST['pageresult'] : 10;
    $page = 1;

    if(@$_POST['page'] > 1) {
        $start = (($_POST['page'] - 1) * $limit);
        $page = $_POST['page'];
    } else {
        $start = 0;
    }
    $order = '';
    $column_name = '';
    $sort = '';
    $type = '';
    $date = '';

    if(Input::exists()) { 
        if($_POST['sort_by'] == 'Profit descending'){ 
            $order = 'desc'; 
            $column_name = 'profit_loss_currency';
        } else {
            if($_POST['sort_by'] == 'Date descending'){ 
                $order = 'desc'; 
                $column_name = 'entry_date';
            } else {
                if($_POST['sort_by'] == 'Date ascending'){ 
                $order = 'asc'; 
                $column_name = 'entry_date';
                } else {          
                    if($_POST['sort_by'] == 'Profit ascending'){ 
                        $order = 'asc'; 
                        $column_name = 'profit_loss_currency';
                    } else {
                        if($_POST['sort_by'] == 'Return descending'){ 
                            $order = 'desc'; 
                            $column_name = 'return_trade';
                        } else {
                            if($_POST['sort_by'] == 'Return ascending'){ 
                                $order = 'asc'; 
                                $column_name = 'return_trade';
                            } else {
                                if($_POST['sort_by'] == ''){
                                    $order = 'desc'; 
                                    $column_name = 'entry_date';
                                }
                            }    
                        }
                    }            
                }
            }
        } if(isset($_POST['load_date'])){
            if(!empty($_POST['from_date']) && !empty($_POST['to_date'])){
                 $date = 'AND entry_date BETWEEN "'.$_POST['from_date'].'" AND "'.$_POST['to_date'].'" ';
            }else {
                $date = '';
            }   
        }

        if($_POST['type_side'] == 'all'){
            $type = '';
        }else {
            if($_POST['type_side']){
                $type = 'AND type_side = "'.$_POST['type_side'].'" ';
            }
        }


            if($_POST['query']) {
                $search = 'AND symbol LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" ';
            }else {
                if($_POST['query'] == ''){
                    $search = '';
                }
            }

        if($_POST['pl'] == 'Win trades') {
            $sort = 'AND profit_loss_currency > 0 '.$type.' '.$date.' '.$search.' ORDER BY '.$column_name.' '.$order.'  LIMIT '.$start.', '.$limit.''; 
        } else {
            if($_POST['pl'] == 'Losing Trades') {
                $sort = 'AND profit_loss_currency < 0 '.$type.' '.$date.' '.$search.' ORDER BY '.$column_name.' '.$order.'  LIMIT '.$start.', '.$limit.'';
            } else {
                if($_POST['pl'] == 'All Trades') {  
                    $sort = ' '.$type.' '.$date.' '.$search.' ORDER BY '.$column_name.' '.$order.' LIMIT '.$start.', '.$limit.''; 
                }
            }
        }
    }

    $getPortfolio = new PortfolioData($id);

    $portfolio = !empty($_POST['idPortfolio']) ? $_POST['idPortfolio'] : $getPortfolio->first()->id;
    $array = [$id, $portfolio, $sort];
    $trade = new Trades($array);
    $data = new Element($portfolio, $id);
      $output = '

        <p class="text-2 font-weight-500 py-1 px-4  alltrades_p ">Shown<span class="text-2"> '.$trade->count_trades().' from '.$data->data()->total_recorded_trades.' Trades</span></p>
          <div class="tansaction-title th-allTradesTable py-2 px-3 px-lg-4 sticky-top">
            <div class="row text-left">
                <div class="col col-sm-3 d-md-none">Symbol</div>
                <div class="col col-sm-2 col-md-2 d-none d-md-block"><span>Symbol</span></div>
                <div class="col col-sm-1 d-none d-xl-block">Quantity</div>
                <div class="col col-sm-1 d-none d-xl-block">Side</div>
                <div class="col col-sm-2 d-none d-md-block d-xl-none">Qty/Side</div>
                <div class="col col-sm-2 d-none d-sm-block">Price</div>
                <div class="col col-sm-2 d-none d-sm-block d-xs-none">Date</div>
                <div class="col col-sm-2">Profit</div>
                <div class="text-right col col-sm-3 col-md-2 col-lg-1 col-xl-2 ">Options</div>
           </div>
        </div>';

        if($trade->data()){ 
            foreach($trade->data() as $row) {
            $output .= '
             <div class="trade-row px-3 px-lg-4 py-2">
                <div class="row flex-row py-2 noneCollapse_td" >

                <div class="text-left m-auto col col-sm-3 d-md-none ">
                    <span class="text-2 font-weight-500">'.$row->symbol.'</span>
                    <span class="text-7 font-weight-500 d-block">'.$row->quantity.'</span>
                    <span class="text-5 font-weight-500 type_side">'.$row->type_side.'<i class="material-icons type_side_arrow"></i></span>
                </div>
                <div class="text-left m-auto col col-sm-2 col-md-2 d-none d-md-block ">
                    <span class="text-2 font-weight-500">'.$row->symbol.'</span>
                </div>
                <div class="text-left m-auto col col-sm-1 d-none d-xl-block">
                    <span class="text-5 font-weight-500">'.$row->quantity.'</span>
                </div>
                <div class="text-left m-auto col col-sm-1 d-none d-xl-block">
                    <span class="text-5 font-weight-500 type_side">'.$row->type_side.'<i class="material-icons type_side_arrow"></i></span>
                </div>
                <div class="text-left m-auto col-sm-2 d-none d-md-block d-xl-none">
                    <span class="d-block text-7 font-weight-500">'.$row->quantity.'</span>
                    <span class="d-block text-5 font-weight-500 type_side">'.$row->type_side.'<i class="material-icons type_side_arrow"></i></span>
                </div>

                <div class="text-left col col-sm-2 d-none d-sm-block">
                <div>
                    <span class="text-6 font-weight-400">Entry:</span>
                    <span class="text-7 font-weight-500">'.$row->entry_price.'</span><br>
                </div>
                <div>
                    <span class="text-6 font-weight-400">TP:</span>
                    <span class="text-7 font-weight-500">'.$row->tp_price.'</span><br>
                </div>
                <div>
                    <span class="text-6 font-weight-400">SL:</span>
                    <span class="text-7 font-weight-500">'.$row->sl_price.'</span>
                </div>
                </div>

                <div class="text-left m-auto col col-sm-2 d-none d-sm-block d-xs-none">
                <div>
                    <span class="text-6 font-weight-400">Entry:</span>
                    <span class="text-7 font-weight-500">'.$row->entry_date.'</span><br>
                </div>
                <div>
                    <span class="text-6 font-weight-400">Exit:</span>
                    <span class="text-7 font-weight-500">'.$row->exit_date.'</span>
                </div>
                <div>
                    <i class="material-icons duration_i">access_time</i>
                    <span class="text-7 font-weight-400 ml-4">'.$row->duration.' Days<span>
                </div>
                </div>

                <div class="col col-sm-2 m-auto">
                <div class="">
                    <span class="text-7 m font-weight-500 pl_pips">'.$row->profit_loss_currency.' '.$row->currency.' ('.number_format($row->return_trade,2, '.','').'%)</span>
                </div>
                <div class="">
                    <span class="text-7  text-right">'.number_format($row->profit_loss_pips,1, '.','').' Pips</span>
                </div>
                </div>

                <div class="text-right  m-auto col col-sm-3 col-md-2 col-lg-2 col-xl-2">
                <div class="btn-group d-flex flex-column flex-column-reverse flex-sm-row flex-sm-nowrap justify-content-end ">
                    <a href="#" class="ml-sm-2 p-1 p-md-0" data-toggle="collapse" data-target="#bb'.$row->trade_ids.'"><i class="material-icons collapseTrade">keyboard_arrow_down</i></a>
                    <a href="#" class="ml-sm-2 d-none d-sm-block" data-toggle="tooltip" data-placement="bottom" title="Edit Trade"><i class="material-icons editTrade" id="'.$row->trade_ids.'">edit</i></a>
                    <a href="#" class="ml-sm-2 p-1 p-md-0" data-toggle="tooltip" data-placement="bottom" title="Delete Trade"><i class="material-icons deleteTrade" id="'.$row->trade_ids.'">delete</i></a>
                </div>
                </div>
                </div>

        <div class="collapse_tr row flex-row">
            <div class="hiddenRow col-12 ">
                <div class="main_collapse collapse py-2" id="bb'.$row->trade_ids.'">
                    <div class="row content-collapse">
                        <div class="col col-xl-6 col-sm-6 ">
                            <label class="text-7">Commentar:</label>
                            <textarea id="'.$row->trade_ids.'" class="commentar_p commentar_div">'.$row->trade_commentar.'</textarea>
                                <button type="button" id="'.$row->trade_ids.'" class="btn btn-primary my-3 save_commentar">Save Commentar </button>

                        </div>
                        <div class="col col-xl-6 col-sm-6  d-none d-sm-block mt-4">
                            <table class="performance_collapse_row">
                                <tr>
                                    <td class="text-6 font-weight-400">Strategy:</td>
                                    <td class="text-7 font-weight-500">'.$row->strategy.'</td>
                                </tr>
                                <tr>
                                    <td class="text-6 font-weight-400">Entry Rules:</td>
                                    <td class="text-7 font-weight-500">'.$row->entryrule.'</td>
                                </tr>
                                <tr>
                                    <td class="text-6 font-weight-400">Exit Reason:</td>
                                    <td class="text-7 font-weight-500">'.$row->tprule.'</td>
                                </tr>
                                <tr>
                                    <td class="text-6 font-weight-400">Risk Reward Ratio:</td>
                                    <td class="text-7 font-weight-500">'.$row->rr.'</td>
                                </tr>
                            </table>
                             <a href="#bb'.$row->trade_ids.'" data-toggle="collapse" class="float-right close_collapse">Close</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
        ';
       }
    } else {
        $output .= '
        <h3 class="p-4">No Trades Found</h3>';
      }
      $output .= '

        <br />
        <div>
        <ul class="pagination justify-content-center">';

   
            
    $total_data = $data->data()->total_recorded_trades;

    $total_links = ceil($total_data/$limit);
    $previous_link = '';
    $next_link = '';
    $page_link = '';

    if($total_links > 4)
    {
      if($page < 5)
      {
        for($count = 1; $count <= 5; $count++)
        {
          $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
      }
      else
      {
        $end_limit = $total_links - 5;
        if($page > $end_limit)
        {
          $page_array[] = 1;
          $page_array[] = '...';
          for($count = $end_limit; $count <= $total_links; $count++)
          {
            $page_array[] = $count;
          }
        }
        else
        {
          $page_array[] = 1;
          $page_array[] = '...';
          for($count = $page - 1; $count <= $page + 1; $count++)
          {
            $page_array[] = $count;
          }
          $page_array[] = '...';
          $page_array[] = $total_links;
        }
      }
    }
    else {
        for($count = 1; $count <= $total_links; $count++){
        $page_array[] = $count;
        }
    }

        for($count = 0; $count < count((array)@$page_array); @$count++){
            if($page == $page_array[$count]){
                $page_link .= '
                <li class="page-item active">
                  <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
                </li>
                ';

                $previous_id = $page_array[$count] - 1;
                if($previous_id > 0){
                    $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
                }else{
                      $previous_link = '
                      <li class="page-item disabled">
                        <a class="page-link" href="#">Previous</a>
                      </li>
                      ';
                }
                $next_id = $page_array[$count] + 1;
                if($next_id >= $total_links){
                  $next_link = '
                  <li class="page-item disabled">
                    <a class="page-link" href="#">Next</a>
                  </li>
                    ';
                }else{
                    $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
                }
            } else {
                if($page_array[$count] == '...'){
                      $page_link .= '
                      <li class="page-item disabled">
                          <a class="page-link" href="#">...</a>
                      </li>
                      ';
                } else {
                  $page_link .= '
                  <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
                  ';
                }
            }
        }
            $output .= $previous_link . $page_link . $next_link;
            $output .= '
              </ul>
            </div>
            ';
            echo $output;
        }
        if($_POST["load_trades"] == 'Delete') {
            $delete = DB::getInstance()->delete('trade_record', array('id', '=', $_POST['id']), 'AND recording_account_id = '.$_POST['portfolio'].'');
        }  
    }
}

    if(isset($_POST["save"])){
        
        if($_POST["save"] == 'commentar'){
             $commnetar = DB::getInstance()->update('trade_record', $_POST['id'], array(
                'trade_commentar' => $_POST['commentar'],
                ), 'AND user_id = '.$id.'');
        }
        
        if($_POST["save"] == 'edit_item'){
        $edit_trade = new View;
        $item_id = $_POST["id"];
        $sql="Select *, 
        (SELECT TRUNCATE(return_trade, 2)return_trade FROM current_trade_performance WHERE user_id = '".$id."' and trade_id = '".$item_id."')as return_trade,
        (SELECT currency FROM recording_account WHERE id = trade_record.recording_account_id and user_id = '".$id."')as currency
        FROM trade_record WHERE user_id = '".$id."' and id = ? LIMIT 1"; 
        $edit_trade->data($item_id, $sql);
        }
         
        if($_POST["save"] == 'save_edit_item'){ 
            $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'type_side' => array(
                'check_type_side' => true,
            ),
            'quantity' => array(
                'min' => 1,
                'max' => 16,
                'numeric' => true,
            ),
           'entry_price' =>array(
                'min' => 1,
                'max' => 14,
                'numeric' => true,
                'required' => true,
            ),
            'tp_price' =>array(
                'min' => 1,
                'max' => 14,
                'numeric' => true,
                'required' => true,
            ),
            'sl_price' =>array(
                'min' => 1,
                'max' => 14,
                'numeric' => true,
                'required' => true,
            ), 
            'entry_date' =>array(
                'required' => true,
                'valide_date' => true, 
                'check_date' => $_POST['portfolio']
            ),
            'exit_date' =>array(
                'required' => true,
                'valide_date' => true, 
                'check_date' => $_POST['portfolio']
            ),
        ));
             if($validation->passed()) {
                 /*
                $entry_rules = !empty($_POST['entry_rules']) ? $_POST['entry_rules'] : NULL;
                $val = implode(", ", $entry_rules);    
                 */
                $trade = DB::getInstance()->update('trade_record', $_POST['id'], array(
                'type_side' => strtolower($_POST['type_side']),
                'quantity' => $_POST['quantity'],
                'entry_price' => $_POST['entry_price'],
                'tp_price' => $_POST['tp_price'],
                'sl_price' => $_POST['sl_price'],
                'entry_date' => $_POST['entry_date'],
                'exit_date' => $_POST['exit_date'],
                //'strategy_id' => !empty($_POST['strategy']) ? $_POST['strategy'] : NULL,
                //'exit_reason_id' => !empty($_POST['exit_reason']) ? $_POST['exit_reason'] : NULL,
                //'entry_rules'  => $val,  
                ), 'AND user_id = '.$id.'');
                 
               // $update_rule = DB::getInstance()->query('UPDATE recordet_trade_entry_rules SET recordet_trade_rule = "'.$val.'" WHERE trade_id ="'.$_POST['id'].'" ');      
                }
            
            else {
                foreach($validation->errors() as $error) {
                exit($error);
            }         
        }
        }
  
    }
  
