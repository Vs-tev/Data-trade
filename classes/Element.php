<?php
class Element{
    private $_db,
            $_data,
            $_jsonData;
    
    public function __construct($portfolio, $id) {
        $this->_db = DB::getInstance();
                if($this->PortfolioPerformance($portfolio, $id)) {
                    return true;
                }
    }
    
    protected function PortfolioPerformance($portfolio, $id) {
        
        $win_streak = $this->_db->query("SELECT IFNULL(MAX(cnt), 0)as win_streak FROM (SELECT recording_account_id, trade_id, COUNT(*) AS cnt FROM 
            (SELECT *,
            SUM(CASE WHEN tradeDepositValue < 0 THEN 1 END) OVER (PARTITION BY recording_account_id ORDER BY action_date, trade_id ROWS UNBOUNDED PRECEDING) AS streak FROM balance)as dt 
            WHERE action_type = 'trade' and tradeDepositValue > 0 and recording_account_id = '$portfolio' GROUP by streak)as dt");
        if($win_streak->results()) {
            foreach($win_streak->results() as $result) {
                    $data["win_streak"] = $result->win_streak." ".'Trades';  
            }
        }
        
        $losing_streak = $this->_db->query("SELECT IFNULL(MAX(cnt), 0)as lose_streak FROM (SELECT recording_account_id, trade_id, COUNT(*) AS cnt FROM 
    (SELECT *,
    SUM(CASE WHEN tradeDepositValue > 0 THEN 1 END) OVER (PARTITION BY recording_account_id ORDER BY action_date, trade_id ROWS UNBOUNDED PRECEDING) AS streak FROM balance)as dt 
    WHERE action_type = 'trade' and tradeDepositValue < 0 and recording_account_id = '$portfolio' GROUP by streak)as dt");
        if($losing_streak->results()) {
            foreach($losing_streak->results() as $result) {
                $data["lose_streak"] = $result->lose_streak." ".'Trades';  
            }
        }
        
        
         $user = $this->_db->query("SELECT equity, total, recording_account_name, currency, total_recorded_trades, Gross_profit, Gross_loss, IFNULL(winning_trades,0)as winning_trades, IFNULL(losing_trades,0)as losing_trades,
        SQRT(sum_pow_2/(count_pow_2-1))AS standard_dev, 
        avg_losing_return, avg_win_return, max_win, max_return, max_lose, max_lose_return, gained_pips, av_win_pips, av_loss_pips, average_rr_ratio,
         av_duration,
       av_duration_win,
        av_duration_lose,
        (Gross_profit + Gross_loss)AS trade_net_profit,
        ((Gross_profit + Gross_loss)/total_recorded_trades)AS av_trade_net_profit,
        (total - equity)as total_net_profit,
        (Gross_profit / Gross_loss)*-1 AS profit_factor, 
        (Gross_profit/winning_trades)as winning_average_currency,
        (Gross_loss/losing_trades)as losing_average_currency, 
        ((Gross_profit / winning_trades) / (Gross_loss/losing_trades)*-1) as PL_ratio,
        (winning_trades/(total_recorded_trades)*100)as win_rate,
        (losing_trades/(total_recorded_trades)*100)as losing_rate,
        (Gross_profit + Gross_loss) / total_recorded_trades as expected_return_currency,
        (((IFNULL(losing_trades,1) / (total_recorded_trades)) * (avg_losing_return )) + ((IFNULL(winning_trades,1) / 
        (total_recorded_trades)) * (avg_win_return))) AS expected_return,
        ((total - equity) / equity)*100 AS RCI, 
        
        buy_winning_trades, sell_winning_trades, buy_losing_trades, sell_losing_trades, total_buy_trades, total_sell_trades, buy_Gross_profit, sell_Gross_profit, 
        buy_Gross_loss, sell_Gross_loss, buy_av_RRR, sell_av_RRR, buy_max_win, sell_max_win, buy_max_lose, sell_max_lose,
        IFNULL((buy_Gross_profit + buy_Gross_loss), 0)AS trade_net_profit_buy,
        IFNULL((sell_Gross_profit + sell_Gross_loss), 0)AS trade_net_profit_sell,
        (buy_Gross_profit + buy_Gross_loss)/total_buy_trades as av_net_profit_buy,
        (sell_Gross_profit + sell_Gross_loss)/total_sell_trades as av_net_profit_sell,
        buy_av_duration,
        sell_av_duration,
        (buy_winning_trades/total_buy_trades)*100 AS buy_win_rate,
        (sell_winning_trades/total_sell_trades)*100 AS sell_win_rate,
        (buy_Gross_profit/buy_Gross_loss)*-1 AS buy_porfit_factor,
        (sell_Gross_profit/sell_Gross_loss)*-1 AS sell_porfit_factor,
        (buy_Gross_profit/buy_winning_trades)AS buy_winnning_av,
        (sell_Gross_profit/sell_winning_trades)AS sell_winnning_av,
        (buy_Gross_loss/buy_losing_trades)AS buy_losing_av,
        (sell_Gross_loss/sell_losing_trades)AS sell_losing_av,
        buy_winning_duration_av,
       sell_winning_duration_av,
         buy_losing_duration_av,
         sell_losing_duration_av
        
        FROM
        
        (SELECT recording_account.id, recording_account.recording_account_name, recording_account.equity, recording_account.currency, balance.recording_account_id,
        (SELECT MAX(trade_record.profit_loss_currency) FROM trade_record WHERE recording_account_id = '$portfolio' AND user_id = '$id')max_win,
        (SELECT MAX(return_trade) FROM current_trade_performance WHERE portfolio_id = '$portfolio' and user_id = '$id')max_return,
        (SELECT MIN(CASE WHEN return_trade < 0 then return_trade ELSE NULL END) FROM current_trade_performance WHERE portfolio_id = '$portfolio' and user_id = '$id')max_lose_return,
        (SELECT MIN(CASE WHEN trade_record.profit_loss_currency < 0 then trade_record.profit_loss_currency ELSE NULL END) FROM trade_record WHERE recording_account_id = '$portfolio' AND user_id = '$id')max_lose,
        (SELECT AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE recording_account_id = '$portfolio' AND user_id = '$id')av_duration,
        (SELECT AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE profit_loss_currency >= 0 AND recording_account_id = '$portfolio' AND user_id = '$id')as av_duration_win,
        (SELECT AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE profit_loss_currency < 0 AND recording_account_id =  '$portfolio' AND user_id = '$id')as av_duration_lose,
        (SELECT COUNT(id) FROM trade_record WHERE user_id = '$id' AND recording_account_id = '$portfolio')total_recorded_trades,
        (SELECT SUM(CASE WHEN profit_loss_currency >= 0 THEN 1 else NULL END) FROM trade_record WHERE user_id = '$id' AND recording_account_id = '$portfolio')winning_trades,
        (SELECT SUM(profit_loss_currency) FROM trade_record WHERE profit_loss_currency >= 0 AND user_id = '$id' AND recording_account_id = '$portfolio')Gross_profit,
        (SELECT SUM(profit_loss_pips) FROM trade_record WHERE user_id = '$id' AND recording_account_id = '$portfolio')as gained_pips,
        (SELECT AVG(profit_loss_pips) FROM trade_record WHERE profit_loss_pips >= 0 AND user_id = '$id' AND recording_account_id = '$portfolio')as av_win_pips,
        (SELECT AVG(profit_loss_pips) FROM trade_record WHERE profit_loss_pips < 0 AND user_id = '$id' AND recording_account_id = '$portfolio')as av_loss_pips,
        (SELECT SUM(CASE WHEN profit_loss_currency < 0 THEN 1 else NULL END) FROM trade_record WHERE user_id = '$id' AND recording_account_id = '$portfolio')losing_trades,
        (SELECT COALESCE(SUM(profit_loss_currency),0) FROM trade_record WHERE profit_loss_currency < 0 AND recording_account_id = '$portfolio')Gross_loss,
        (SELECT COALESCE(AVG(return_trade),0) FROM current_trade_performance WHERE return_trade < 0 AND portfolio_id = '$portfolio' AND user_id = '$id')avg_losing_return,
        (SELECT COALESCE(AVG(return_trade),0) FROM current_trade_performance WHERE return_trade >= 0 AND portfolio_id = '$portfolio' AND user_id = '$id')avg_win_return,
        (SELECT AVG(risk_reward_ratio) FROM current_trade_performance WHERE user_id = '$id' AND portfolio_id = '$portfolio')average_rr_ratio,
        (SELECT SUM(pow_2) FROM standart_dev WHERE portfolio_id = '$portfolio' AND user_id = '$id')as sum_pow_2,
        (SELECT COUNT(pow_2) FROM standart_dev WHERE portfolio_id = '$portfolio' AND user_id = '$id')as count_pow_2,
        
        /*buy sell*/
        (SELECT COUNT(id) FROM trade_record WHERE user_id = '$id' AND type_side = 'buy' AND recording_account_id = '$portfolio')total_buy_trades,
        (SELECT COUNT(id) FROM trade_record WHERE user_id = '$id' AND type_side = 'sell' AND recording_account_id = '$portfolio')total_sell_trades,
        (SELECT SUM(profit_loss_currency) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'buy' AND user_id = '$id' AND recording_account_id = '$portfolio')buy_Gross_profit,
        (SELECT SUM(profit_loss_currency) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'sell' AND user_id = '$id' AND recording_account_id = '$portfolio')sell_Gross_profit,
        (SELECT COALESCE(SUM(profit_loss_currency),0) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'buy' AND recording_account_id = '$portfolio')buy_Gross_loss,
        (SELECT COALESCE(SUM(profit_loss_currency),0) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'sell' AND recording_account_id = '$portfolio')sell_Gross_loss,
        (SELECT  AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE type_side = 'buy' AND recording_account_id = '$portfolio' AND user_id = '$id')buy_av_duration,
        (SELECT  AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE type_side = 'sell' AND recording_account_id = '$portfolio' AND user_id = '$id')sell_av_duration,
        (SELECT AVG(risk_reward_ratio) FROM current_trade_performance LEFT JOIN trade_record on current_trade_performance.trade_id=trade_record.id WHERE current_trade_performance.user_id = '$id' AND portfolio_id = '$portfolio' AND type_side = 'buy')AS buy_av_RRR,
        (SELECT AVG(risk_reward_ratio) FROM current_trade_performance LEFT JOIN trade_record on current_trade_performance.trade_id=trade_record.id WHERE current_trade_performance.user_id = '$id' AND portfolio_id = '$portfolio' AND type_side = 'sell')AS sell_av_RRR,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency >= 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'buy' AND user_id = '$id' AND recording_account_id = '$portfolio')buy_winning_trades,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency >= 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'sell' AND user_id = '$id' AND recording_account_id = '$portfolio')sell_winning_trades,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency < 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'buy' AND user_id = '$id' AND recording_account_id = '$portfolio')buy_losing_trades,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency < 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'sell' AND user_id = '$id' AND recording_account_id = '$portfolio')sell_losing_trades,
        (SELECT MAX(trade_record.profit_loss_currency) FROM trade_record WHERE type_side = 'buy' AND recording_account_id = '$portfolio' AND user_id = '$id')buy_max_win,
        (SELECT MAX(trade_record.profit_loss_currency) FROM trade_record WHERE type_side = 'sell' AND recording_account_id = '$portfolio' AND user_id = '$id')sell_max_win,
        (SELECT MIN(CASE WHEN trade_record.profit_loss_currency < 0 then trade_record.profit_loss_currency ELSE NULL END) FROM trade_record WHERE type_side = 'buy' AND recording_account_id = '$portfolio' AND user_id = '$id')buy_max_lose,
        (SELECT MIN(CASE WHEN trade_record.profit_loss_currency < 0 then trade_record.profit_loss_currency ELSE NULL END) FROM trade_record WHERE type_side = 'sell' AND recording_account_id = '$portfolio' AND user_id = '$id')sell_max_lose,
        (SELECT  AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'buy' AND recording_account_id = '$portfolio' AND user_id = '$id')as buy_winning_duration_av,
        (SELECT  AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'sell' AND recording_account_id = '$portfolio' AND user_id = '$id')as sell_winning_duration_av,
        (SELECT  AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'buy' AND recording_account_id =  '$portfolio' AND user_id = '$id')as buy_losing_duration_av,
        (SELECT  AVG(DATEDIFF(exit_date, entry_date)) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'sell' AND recording_account_id =  '$portfolio' AND user_id = '$id')as sell_losing_duration_av,
        
        SUM(tradeDepositValue) AS total
        FROM recording_account 
        LEFT JOIN balance ON recording_account.id = balance.recording_account_id WHERE recording_account.id = '$portfolio' AND user_id = '$id'
        GROUP BY recording_account.id) a
        ");
        if($user->results()) {
            foreach($user->results() as $result) {
                $data["portfolio_name"] = $result->recording_account_name;  
                $data["total_equity"] = number_format($result->total,2, '.',' ');
                $data["data_total_equity"] = number_format($result->total,2, '.','');  
                $data["start_capital"] = number_format($result->equity,2, '.',' ');
                $data["currency"] = $result->currency;
                $data["return_capital_investmen"] = number_format($result->RCI,2, '.',''.'');
                $data["total_net_profit"] =number_format($result->total_net_profit,2, '.',' ');  
                $data["gained_pips"] =number_format($result->gained_pips,1, '.','');
                $data["av_win_pips"] =number_format($result->av_win_pips,1, '.',''). " ".'Pips';      
                $data["av_loss_pips"] =number_format($result->av_loss_pips,1, '.',''). " ".'Pips';
                $data["Gross_profit"] =number_format($result->Gross_profit,2, '.','')." ".$result->currency;
                $data["Gross_loss"] =number_format($result->Gross_loss,2, '.','')." ".$result->currency;    
                $data["PL_ratio"] =number_format($result->PL_ratio,2, '.','');
                $data["av_trade_net_profit"] =number_format($result->av_trade_net_profit,2, '.','')." ".$result->currency;
                $data["trade_net_profit"] =number_format($result->trade_net_profit,2, '.','')." ".$result->currency;   
                $data["total_recorded_trades"] = $result->total_recorded_trades;
                $data["winning_trades"] = $result->winning_trades; 
                $data["winning_average"] = number_format($result->winning_average_currency,2, '.','')." ".$result->currency;
                $data["average_win_percent"] = number_format($result->avg_win_return,2, '.','')." ".'%';     
                $data["losing_trades"] = $result->losing_trades;
                $data["losing_average"] = number_format($result->losing_average_currency,2, '.',' ')." ".$result->currency;
                $data["average_losing_percent"] = number_format($result->avg_losing_return,2, '.','')." ".'%';
                $data["max_win"] = number_format($result->max_win,2, '.',' ')." ".$result->currency;
                $data["max_return"] = number_format($result->max_return,2, '.',''.'')." ".'%';
                $data["av_duration"] = number_format($result->av_duration,2, '.',''.'')." ".'Days';
                $data["av_duration_win"] = number_format($result->av_duration_win,2, '.',''.'')." ".'Days';
                $data["av_duration_lose"] = number_format($result->av_duration_lose,2, '.',''.'')." ".'Days';
                $data["max_lose"] = number_format($result->max_lose,2, '.',' ')." ".$result->currency;
                $data["max_lose_return"] = number_format($result->max_lose_return,2, '.',''.'')." ".'%';
                $data["expected_return"] = number_format($result->expected_return,2, '.','')." ".'%';    
                $data["expected_return_currency"] = number_format($result->expected_return_currency,2, '.','')." ".$result->currency;      
                $data["win_rate"] = number_format($result->win_rate,2, '.','')." ".'%';
                $data["losing_rate"] = number_format($result->losing_rate,2, '.','')." ".'%';
                $data["profit_factor"] = number_format($result->profit_factor,2, '.','');
                $data["average_rr_ratio"] = number_format($result->average_rr_ratio,2, '.','');    
                $data["standard_deviation"] = number_format($result->standard_dev,2, '.','')." ".'%';    
                /*buy / sell*/    
                $data["total_buy_trades"] = $result->total_buy_trades; 
                $data["total_sell_trades"] = $result->total_sell_trades;
                $data["trade_net_profit_buy"] = number_format($result->trade_net_profit_buy,2, '.',' ')." ".$result->currency;
                $data["trade_net_profit_sell"] = number_format($result->trade_net_profit_sell,2, '.',' ')." ".$result->currency;
                $data["buy_Gross_profit"] = number_format($result->buy_Gross_profit,2, '.',' ')." ".$result->currency;
                $data["sell_Gross_profit"] = number_format($result->sell_Gross_profit,2, '.',' ')." ".$result->currency;
                $data["buy_Gross_loss"] = number_format($result->buy_Gross_loss,2, '.',' ')." ".$result->currency;
                $data["sell_Gross_loss"] = number_format($result->sell_Gross_loss,2, '.',' ')." ".$result->currency;
                $data["av_net_profit_buy"] = number_format($result->av_net_profit_buy,2, '.',' ')." ".$result->currency;
                $data["av_net_profit_sell"] = number_format($result->av_net_profit_sell,2, '.',' ')." ".$result->currency;
                $data["buy_av_duration"] = number_format($result->buy_av_duration,2, '.','')." ".'Days';
                $data["sell_av_duration"] = number_format($result->sell_av_duration,2, '.','')." ".'Days';
                $data["buy_av_RRR"] = number_format($result->buy_av_RRR,2, '.','');
                $data["sell_av_RRR"] = number_format($result->sell_av_RRR,2, '.','');
                $data["buy_winning_trades"] = $result->buy_winning_trades;
                $data["sell_winning_trades"] = $result->sell_winning_trades;
                $data["buy_losing_trades"] = $result->buy_losing_trades;
                $data["sell_losing_trades"] = $result->sell_losing_trades;
                $data["buy_win_rate"] = number_format($result->buy_win_rate,2, '.','')." ".'%';
                $data["sell_win_rate"] = number_format($result->sell_win_rate,2, '.','')." ".'%';
                $data["buy_porfit_factor"] = number_format($result->buy_porfit_factor,2, '.','');
                $data["sell_porfit_factor"] = number_format($result->sell_porfit_factor,2, '.','');
                $data["buy_winnning_av"] = number_format($result->buy_winnning_av,2, '.',' ')." ".$result->currency;
                $data["sell_winnning_av"] = number_format($result->sell_winnning_av,2, '.',' ')." ".$result->currency;
                $data["buy_losing_av"] = number_format($result->buy_losing_av,2, '.',' ')." ".$result->currency;
                $data["sell_losing_av"] = number_format($result->sell_losing_av,2, '.',' ')." ".$result->currency;
                $data["buy_max_win"] = number_format($result->buy_max_win,2, '.',' ')." ".$result->currency;
                $data["sell_max_win"] = number_format($result->sell_max_win,2, '.',' ')." ".$result->currency;
                $data["buy_max_lose"] = number_format($result->buy_max_lose,2, '.',' ')." ".$result->currency;
                $data["sell_max_lose"] = number_format($result->sell_max_lose,2, '.',' ')." ".$result->currency;
                $data["buy_winning_duration_av"] = number_format($result->buy_winning_duration_av,2, '.','')." ".'Days';
                $data["sell_winning_duration_av"] = number_format($result->sell_winning_duration_av,2, '.','')." ".'Days';
                $data["buy_losing_duration_av"] = number_format($result->buy_losing_duration_av,2, '.','')." ".'Days';
                $data["sell_losing_duration_av"] = number_format($result->sell_losing_duration_av,2, '.','')." ".'Days';
            }
        }
        if($user->count()) {
                $this->_jsonData = json_encode($data);
                $this->_data = $user->first();
                return true;
             }
         return false;
    }
  
    public function data() {
        return $this->_data;
    }
    
     public function jsonReturn() {
        return $this->_jsonData;
    }
  
}

/*
SELECT equity, total, recording_account_name, currency, total_recorded_trades, Gross_profit, Gross_loss, IFNULL(winning_trades,0)as winning_trades, IFNULL(losing_trades,0)as losing_trades,
        SQRT(sum_pow_2/(count_pow_2-1))AS standard_dev, 
        avg_losing_return, avg_win_return, max_win, max_return, max_lose, max_lose_return, gained_pips, av_win_pips, av_loss_pips, average_rr_ratio,
        (duration/total_recorded_trades)AS av_duration,
        (win_duration/winning_trades)av_duration_win,
        (losing_duration/losing_trades)av_duration_lose,
        (Gross_profit + Gross_loss)AS trade_net_profit,
        ((Gross_profit + Gross_loss)/total_recorded_trades)AS av_trade_net_profit,
        (total - equity)as total_net_profit,
        (Gross_profit / Gross_loss)*-1 AS profit_factor, 
        (Gross_profit/winning_trades)as winning_average_currency,
        (Gross_loss/losing_trades)as losing_average_currency, 
        ((Gross_profit / winning_trades) / (Gross_loss/losing_trades)*-1) as PL_ratio,
        (winning_trades/(total_recorded_trades)*100)as win_rate,
        (losing_trades/(total_recorded_trades)*100)as losing_rate,
        (Gross_profit + Gross_loss) / total_recorded_trades as expected_return_currency,
        (((IFNULL(losing_trades,1) / (total_recorded_trades)) * (avg_losing_return )) + ((IFNULL(winning_trades,1) / 
        (total_recorded_trades)) * (avg_win_return))) AS expected_return,
        ((total - equity) / equity)*100 AS RCI, 
        
        buy_winning_trades, sell_winning_trades, buy_losing_trades, sell_losing_trades, total_buy_trades, total_sell_trades, buy_Gross_profit, sell_Gross_profit, 
        buy_Gross_loss, sell_Gross_loss, buy_av_RRR, sell_av_RRR, buy_max_win, sell_max_win, buy_max_lose, sell_max_lose,
        IFNULL((buy_Gross_profit + buy_Gross_loss), 0)AS trade_net_profit_buy,
        IFNULL((sell_Gross_profit + sell_Gross_loss), 0)AS trade_net_profit_sell,
        (buy_Gross_profit + buy_Gross_loss)/total_buy_trades as av_net_profit_buy,
        (sell_Gross_profit + sell_Gross_loss)/total_sell_trades as av_net_profit_sell,
        (buy_duration/total_buy_trades)as buy_av_duration,
        (sell_duration/total_sell_trades)as sell_av_duration,
        (buy_winning_trades/total_buy_trades)*100 AS buy_win_rate,
        (sell_winning_trades/total_sell_trades)*100 AS sell_win_rate,
        (buy_Gross_profit/buy_Gross_loss)*-1 AS buy_porfit_factor,
        (sell_Gross_profit/sell_Gross_loss)*-1 AS sell_porfit_factor,
        (buy_Gross_profit/buy_winning_trades)AS buy_winnning_av,
        (sell_Gross_profit/sell_winning_trades)AS sell_winnning_av,
        (buy_Gross_loss/buy_losing_trades)AS buy_losing_av,
        (sell_Gross_loss/sell_losing_trades)AS sell_losing_av,
        (buy_win_duration/buy_winning_trades)AS buy_winning_duration_av,
        (sell_win_duration/sell_winning_trades)AS sell_winning_duration_av,
        (buy_losing_duration/ buy_losing_trades)AS buy_losing_duration_av,
        (sell_losing_duration/sell_losing_trades)AS sell_losing_duration_av
        
        FROM
        
        (SELECT recording_account.id, recording_account.recording_account_name, recording_account.equity, recording_account.currency, balance.recording_account_id,
        (SELECT MAX(trade_record.profit_loss_currency) FROM trade_record WHERE recording_account_id = 1257 AND user_id = 31)max_win,
        (SELECT MAX(return_trade) FROM current_trade_performance WHERE portfolio_id = 1257 and user_id = 31)max_return,
        (SELECT MIN(CASE WHEN return_trade < 0 then return_trade ELSE NULL END) FROM current_trade_performance WHERE portfolio_id = 1257 and user_id = 31)max_lose_return,
        (SELECT MIN(CASE WHEN trade_record.profit_loss_currency < 0 then trade_record.profit_loss_currency ELSE NULL END) FROM trade_record WHERE recording_account_id = 1257 AND user_id = 31)max_lose,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE recording_account_id = 1257 AND user_id = 31)duration,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE profit_loss_currency >= 0 AND recording_account_id = 1257 AND user_id = 31)as win_duration,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE profit_loss_currency < 0 AND recording_account_id =  1257 AND user_id = 31)as losing_duration,
        (SELECT COUNT(id) FROM trade_record WHERE user_id = 31 AND recording_account_id = 1257)total_recorded_trades,
        (SELECT SUM(CASE WHEN profit_loss_currency >= 0 THEN 1 else NULL END) FROM trade_record WHERE user_id = 31 AND recording_account_id = 1257)winning_trades,
        (SELECT SUM(profit_loss_currency) FROM trade_record WHERE profit_loss_currency >= 0 AND user_id = 31 AND recording_account_id = 1257)Gross_profit,
        (SELECT SUM(profit_loss_pips) FROM trade_record WHERE user_id = 31 AND recording_account_id = 1257)as gained_pips,
        (SELECT AVG(profit_loss_pips) FROM trade_record WHERE profit_loss_pips >= 0 AND user_id = 31 AND recording_account_id = 1257)as av_win_pips,
        (SELECT AVG(profit_loss_pips) FROM trade_record WHERE profit_loss_pips < 0 AND user_id = 31 AND recording_account_id = 1257)as av_loss_pips,
        (SELECT SUM(CASE WHEN profit_loss_currency < 0 THEN 1 else NULL END) FROM trade_record WHERE user_id = 31 AND recording_account_id = 1257)losing_trades,
        (SELECT COALESCE(SUM(profit_loss_currency),0) FROM trade_record WHERE profit_loss_currency < 0 AND recording_account_id = 1257)Gross_loss,
        (SELECT COALESCE(AVG(return_trade),0) FROM current_trade_performance WHERE return_trade < 0 AND portfolio_id = 1257 AND user_id = 31)avg_losing_return,
        (SELECT COALESCE(AVG(return_trade),0) FROM current_trade_performance WHERE return_trade >= 0 AND portfolio_id = 1257 AND user_id = 31)avg_win_return,
        (SELECT AVG(risk_reward_ratio) FROM current_trade_performance WHERE user_id = 31 AND portfolio_id = 1257)average_rr_ratio,
        (SELECT SUM(pow_2) FROM standart_dev WHERE portfolio_id = 1257 AND user_id = 31)as sum_pow_2,
        (SELECT COUNT(pow_2) FROM standart_dev WHERE portfolio_id = 1257 AND user_id = 31)as count_pow_2,
        
   
        (SELECT COUNT(id) FROM trade_record WHERE user_id = 31 AND type_side = 'buy' AND recording_account_id = 1257)total_buy_trades,
        (SELECT COUNT(id) FROM trade_record WHERE user_id = 31 AND type_side = 'sell' AND recording_account_id = 1257)total_sell_trades,
        (SELECT SUM(profit_loss_currency) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'buy' AND user_id = 31 AND recording_account_id = 1257)buy_Gross_profit,
        (SELECT SUM(profit_loss_currency) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'sell' AND user_id = 31 AND recording_account_id = 1257)sell_Gross_profit,
        (SELECT COALESCE(SUM(profit_loss_currency),0) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'buy' AND recording_account_id = 1257)buy_Gross_loss,
        (SELECT COALESCE(SUM(profit_loss_currency),0) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'sell' AND recording_account_id = 1257)sell_Gross_loss,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE type_side = 'buy' AND recording_account_id = '$portfolio' AND user_id = 31)buy_duration,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE type_side = 'sell' AND recording_account_id = 1257 AND user_id = 31)sell_duration,
        (SELECT AVG(risk_reward_ratio) FROM current_trade_performance LEFT JOIN trade_record on current_trade_performance.trade_id=trade_record.id WHERE current_trade_performance.user_id = '$id' AND portfolio_id = 1257 AND type_side = 'buy')AS buy_av_RRR,
        (SELECT AVG(risk_reward_ratio) FROM current_trade_performance LEFT JOIN trade_record on current_trade_performance.trade_id=trade_record.id WHERE current_trade_performance.user_id = 31 AND portfolio_id = 1257 AND type_side = 'sell')AS sell_av_RRR,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency >= 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'buy' AND user_id = 31 AND recording_account_id = 1257)buy_winning_trades,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency >= 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'sell' AND user_id = 31 AND recording_account_id = 1257)sell_winning_trades,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency < 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'buy' AND user_id = 31 AND recording_account_id = 1257)buy_losing_trades,
        (SELECT COALESCE(SUM(CASE WHEN profit_loss_currency < 0 THEN 1 else NULL END), 0) FROM trade_record WHERE type_side = 'sell' AND user_id = 31 AND recording_account_id = 1257)sell_losing_trades,
        (SELECT MAX(trade_record.profit_loss_currency) FROM trade_record WHERE type_side = 'buy' AND recording_account_id = 1257 AND user_id = 31)buy_max_win,
        (SELECT MAX(trade_record.profit_loss_currency) FROM trade_record WHERE type_side = 'sell' AND recording_account_id = 1257 AND user_id = 31)sell_max_win,
        (SELECT MIN(CASE WHEN trade_record.profit_loss_currency < 0 then trade_record.profit_loss_currency ELSE NULL END) FROM trade_record WHERE type_side = 'buy' AND recording_account_id = 1257 AND user_id = 31)buy_max_lose,
        (SELECT MIN(CASE WHEN trade_record.profit_loss_currency < 0 then trade_record.profit_loss_currency ELSE NULL END) FROM trade_record WHERE type_side = 'sell' AND recording_account_id = 1257 AND user_id = 31)sell_max_lose,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'buy' AND recording_account_id = 1257 AND user_id = 31)as buy_win_duration,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE profit_loss_currency >= 0 AND type_side = 'sell' AND recording_account_id = 1257 AND user_id = 3131)as sell_win_duration,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'buy' AND recording_account_id =  1257 AND user_id = 31)as buy_losing_duration,
        (SELECT SUM(exit_date-entry_date) FROM trade_record WHERE profit_loss_currency < 0 AND type_side = 'sell' AND recording_account_id =  1257 AND user_id = 31)as sell_losing_duration,
        
        SUM(tradeDepositValue) AS total
        FROM recording_account 
        LEFT JOIN balance ON recording_account.id = balance.recording_account_id WHERE recording_account.id = 1257 AND user_id = 31
        GROUP BY recording_account.id) a
*/