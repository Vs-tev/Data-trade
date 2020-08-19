<?php
require_once '../init.php';
$id = $_SESSION['user'];

$entry_rule = new EntryRules($id);
$exit_reason = new ExitReason($id);
$strategy = new Strategy($id)
?>

<html>

<head>
    <link href="trade-record/trade-record.css" rel="stylesheet">
</head>

<body>

    <div class="row justify-content-between nav trade-tab mx-4 my-2">
        <a class="deteils active-btn" data-toggle="tab" href="#page1"><i class="material-icons ">keyboard_arrow_left</i><span>Back</span></a>
        <a class="deteils" data-toggle="tab" href="#page2"><span>Add Deteils</span><i class="material-icons ">keyboard_arrow_right</i></a>
    </div>

    <form class="recording-inputs tab-content px-4" id="TradeRecord" method="POST" action="trade-record/record-first-colum.php">

        <div class="col-12 mb-2 tab-pane active" id="page1">
            <div class="row type-side mt-3">
                <label for="Qty" class="pb-1 m-auto">Quantity</label>
                <div class="btn-group btn-group-toggle buy-sell-div col-12 p-0 mt-0" data-toggle="buttons" id="type">
                    <button class="btn btn-outline-primary buy btn-sm col-6  active" type="button">
                        <input class="buy" type="radio" name="type_side" id="type_buy" value="buy" autocomplete="on" checked>BUY
                    </button>
                    <input id="quantity" type="text" name="quantity" class="form-control quantity" placeholder="0" value="0">
                    <button class="btn btn-outline-danger sell col-6 btn-sm" type="button">
                        <input class="sell" type="radio" name="type_side" id="type_sell" value="sell" autocomplete="off">SELL
                    </button>
                </div>
            </div>
            <h6 class="text-center font-weight-bold my-4 pt-2">Price</h6>

            <div class="row justify-content-between">
                <label for="Entry Price" class="mb-1">Entry</label>
                <label for="TP Price" class="mb-1">Stop Loss</label>
                <label for="SL Price" class="mb-1">Take Profit</label>
            </div>
            <div class="row price-inputs">
                <input id="entry_price" name="entry_price" type="text" class="form-control entry-price col-4" placeholder="0.00000">
                <input id="sl_price" name="sl_price" type="text" class="form-control sl-price col-4" placeholder="0.00000">
                <input id="tp_price" name="tp_price" type="text" class="form-control form-control tp-price col-4" placeholder="0.00000">
            </div>
            <div class="row justify-content-start ">
                <span class="indicator_label">Risk Reward:</span>
                <span id="risk_reward" class="indicator_current_trade">0.0</span>
            </div>
            <div class="row justify-content-between mt-4 pt-2">
                <label for="Entry Date" class="mb-1">Entry Date</label>
                <label for="exit date" class="mb-1">Exit Date</label>
            </div>
            <div class="row justify-content-between">
                <input id="entry_date" name="entry_date" type="date" class=" form-control  entry-date">
                <input id="exit_date" name="exit_date" type="date" class="form-control  exit-date">
            </div>
            <h6 class="text-center font-weight-bold my-4 pt-2">Profit</h6>
            <div class="row justify-content-between">
                <label for="pl_currency" class="mb-1">Currency</label>
                <label for="pl" class="mb-1">Pips</label>
            </div>
            <div class="row justify-content-between pl_inputs">
                <div>
                    <input id="profit_loss_currency" name="profit_loss_currency" type="text" class="form-control profit_loss_currency" placeholder="0">
                    <div class="">
                        <span class="indicator_label">Profit:</span>
                        <span class="indicator_current_trade return_per_trade">0.00%</span>
                    </div>
                </div>

                <input id="profit_loss_pips" name="profit_loss_pips" type="text" class="form-control profit_loss_pips" placeholder="0">
            </div>
            <div class="row clear-record-button mt-5">
                <button id="butsave" value="record" name="save" type="button" class="btn btn-primary btn-block butsave">Record trade</button>
            </div>
        </div>

        <div class="col-12 mb-2 tab-pane fade" id="page2">
            <div class="row mt-5">
                <label for="Entry Rules" class="mb-1">Entry Rules</label>
                <select name="entry_rules" id="entry_rules" multiple="multiple" data-max="3">
                    <?php
                        foreach ($entry_rule->data() as $row){
                            echo '<option value="'.$row->id.'">' . $row->entry_rule . '</ option>';
                        }
                    ?>
                </select>
            </div>
            <div class="row mt-3">
                <label for="tp_rule" class="mb-1">Exit Reason</label><br>
                <select id="tp_rule" name="tp_rule" class="tp_rule custom-select">
                    <option selected></option>
                        <?php
                            foreach ($exit_reason->data() as $row){
                                echo '<option value="' .$row->id. '">' . $row->exit_reason . '</ option>';
                            }
                        ?>
                </select>
            </div>
          
            
            <div class="row mt-3">
                <label for="Strategy" class="mb-1">Strategy</label><br>
                <select id="strategy" name="strategy" class="strategy custom-select">
                    <option selected></option>
                        <?php 
                            foreach ($strategy->data() as $row){     
                                echo '<option value="' .$row->id. '">' . $row->strategy_name . '</ option>';
                            }   
                        ?>
                </select>
                </div>
                <div class="row col-6 mt-3">
                    <label for="time frame" class="mb-1">Time Frame</label><br>
                    <select class="custom-select time_frame" id="time_frame" name="time_frame">
                        <option selected value="1 min">1 min</option>
                        <option value="5 min">5 min</option>
                        <option value="15 min">15 min</option>
                        <option value="30 min">30 min</option>
                        <option value="1 hour">1 hour</option>
                        <option value="2 hours">2 hours</option>
                        <option value="4 hours">4 hours</option>
                        <option value="1 day">1 day</option>
                        <option value="1 week">1 week</option>
                        <option value="1 month">1 month</option>
                    </select>
                </div>
            <div class="row">
                <div class="mt-3 col-12 m-0 p-0">
                    <label for="commentar" class="mb-1">Comment</label>
                    <textarea id="commentar" name="commentar" class="form-control trade-commentar" rows="auto" placeholder="Write something about this trade..."></textarea>
                </div>
            </div>


        </div>

    </form>


</body>

</html>
<script src="trade-record/recording-trade.js"></script>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $("#entry_rules").easySelect({
        buttons: true,
        search: true,
        placeholder: 'max 3 Rules',
        itemTitle: 'Selected Rules',
    })
</script>