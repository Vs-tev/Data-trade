// TRADE RECORD INPUTS

$(document).ready(function () {

    var date_error = false;
    var price_error = false;
    $('#exit_date , #entry_date').on('input', function () {
        var start = new Date($('#entry_date').val());
        var end = new Date($('#exit_date').val());
        var diff = new Date(end - start);
        days = (diff / 1000 / 60 / 60 / 24) || 0;
        if ((!Date.parse(start)) || (!Date.parse(end))) {
            $('#duration_trade').html(days);
            $('#duration_trade').append(" <small>Days</small>");
            $("#entry_date").tooltip({
                title: "Entry date must be before exit date"
            }).tooltip('dispose');
            date_error = false;
            $("#exit_date").css({
                'border': ''
            });
        }
        if (start <= end) {
            $('#duration_trade').html(days);
            $('#duration_trade').append(" <small>Days</small>");
            $("#entry_date").tooltip({
                title: "Entry date must be before exit date"
            }).tooltip('dispose');
            date_error = false;
            $("#exit_date").css({
                'border': ''
            });
        } else {
            if (start > end) {
                date_error = true;
                $('#duration_trade').html("0");
                $('#duration_trade').append(" <small>Days</small>");
                $("#entry_date").tooltip({
                    title: "Entry date must be before exit date"
                }).tooltip('show');
                $("#exit_date").css({
                    'border': '1px solid rgba(255, 37, 37, 0.68)'
                });
            }
        }
    });

    //Calculate pips on change buy/sell
    $('input[type=radio][name=type_side]').change(function () {
        var entry_price = $("#entry_price").val();
        var tp_price = $("#tp_price").val();
        if (this.value == 'buy') {
            var pl = (tp_price - entry_price);
        } else if (this.value == 'sell') {
            var pl = (entry_price - tp_price);
        }
        $("#profit_loss_pips").val((pl).toFixed(4));
        $("#output_pl_pips").text((pl).toFixed(4));
    })

    //Calculate pips on type price
    $("#entry_price, #tp_price").keyup(function () {
        var entry_price = $("#entry_price").val();
        var tp_price = $("#tp_price").val();
        var pl = (tp_price - entry_price);
        if ($('input:radio.buy:checked')) {
            var pl = (tp_price - entry_price);
        } else if ($('input:radio.sell:checked')) {
            var pl = (entry_price - tp_price);
        }
        $("#profit_loss_pips").val((pl).toFixed(4) || 0);
    })

    $('#tp_price, #sl_price, #entry_price').on('input', function () {
        var entry_price = $("#entry_price").val();
        var tp_price = $("#tp_price").val();
        var sl_price = $("#sl_price").val();
        var output = $('#risk_reward').text();
        var risk_rew_ratio = (entry_price - tp_price) / (sl_price - entry_price) || 0;
        if (risk_rew_ratio == Infinity || risk_rew_ratio == -Infinity || risk_rew_ratio < 0) {
            $('#risk_reward').text('0.0');
            $('#TradeInfoMessageContainer').css("display", "block").html('Check the price');
            setTimeout(function () {
                $('#TradeInfoMessageContainer').css("display", "none");
            }, 4500);
            price_error = true;
        } else {
            $('#risk_reward').text((risk_rew_ratio).toFixed(2) || 0);
            $('#TradeInfoMessageContainer').fadeOut("Slow");
            price_error = false;
        }
    });


    //VALIDATION TRADE RECORD INPUTS + CALCULATE 'current trade table'.

    //caluclate %return per trade

    $('#profit_loss_currency').keyup(function () {
        var x = $('#profit_loss_currency').val();
        var y = $('#total_equity').data("equity");
        y.substr()
        var return_per_trade = (x / y) * 100 || 0;
        $('.return_per_trade').text((return_per_trade).toFixed(2) || 0);
        $('.return_per_trade').append("%");
        if (return_per_trade < 0) {
            $('.return_per_trade').css('color', 'var(--negative-red-color)');
        } else {
            $('.return_per_trade').css('color', 'var(--main-text-color)');
        }

    });


    //VALIDATION Inputs
    //Validation P/L Currency

    $("#profit_loss_currency").keyup(function () { //lenght of value on currency input
        myVal = $(this).val();
        var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
        if (myVal.length > 12 || (!$("#profit_loss_currency").val().match($regex))) {
            $(this).val(myVal.substring(0, 11))
            $("#butsave").attr('disabled', true);
            $("#profit_loss_currency").tooltip({
                title: "Invalide Value"
            }).tooltip('show');
            $("#profit_loss_currency").css({
                'border': '1px solid rgba(255, 37, 37, 0.68)'
            });
            return false;
        } else {
            $("#profit_loss_currency").css({
                'border': ''
            });
            $("#butsave").attr('disabled', false);
        }
    });

    $(document).on('input', function () {
        $("input#profit_loss_currency").blur(function () {
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(2);
            $(this).val(cleanNum);
            cleanNum = isNaN(cleanNum) ? '0.00' : cleanNum;
            $(this).val(cleanNum);
            //$("#butsave").attr('disabled', false);
            $(this).css({
                'border': ''
            });
            $(this).tooltip({
                title: "Invalide Value"
            }).tooltip('dispose');
            if (num / cleanNum < 1) {}
        });
        $("#profit_loss_currency").on('change blur', function () {
            a = $('#profit_loss_currency').val();
            $("#output_pl_currency").text(a);
        });
    });

    //input P/L PIPS validation

    $(document).on('input', function () {
        $("#profit_loss_pips").blur(function () {
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(4);
            cleanNum = isNaN(cleanNum) ? '0.00000' : cleanNum;
            $(this).val(cleanNum);
            //$("#butsave").attr('disabled', false);    
            $(this).css({
                'border': ''
            });
            $(this).tooltip({
                title: "Invalide Value"
            }).tooltip('dispose');
        });

        $("#profit_loss_pips").blur(function () {
            a = $('#profit_loss_pips').val();
            $("#output_pl_pips").html(a);
        });
    });

    $("#profit_loss_pips").keyup(function (e) { //P/L Pips lenght of value
        txtVal = $(this).val();
        var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
        if (txtVal.length > 8 || (!$("#profit_loss_pips").val().match($regex))) {
            $(this).val(txtVal.substring(0, 8))
            $("#butsave").attr('disabled', true);
            $("#profit_loss_pips").tooltip({
                title: "Invalid Value"
            }).tooltip('show');
            $("#profit_loss_pips").css({
                'border': '1px solid rgba(255, 37, 37, 0.68)'
            });
            return false;
        } else {
            $("#butsave").attr('disabled', false);
            $("#profit_loss_pips").tooltip({
                title: "Maximum P/L pips"
            }).tooltip('dispose');
            $("#profit_loss_pips").css({
                'border': ''
            });
        }
    });



    //Quanity input validation

    $("#quantity").keyup(function () { //lenght of value on currency input
        myVal = $(this).val();
        var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
        if (myVal.length > 16 || (!$("#quantity").val().match($regex))) {
            $(this).val(myVal.substring(0, 16))
            $("#butsave").attr('disabled', true);
            $("#quantity").tooltip({
                title: "Invalide Value"
            }).tooltip('show');
            $("#quantity").css({
                'border': '1px solid rgba(255, 37, 37, 0.68)'
            });
            return false;
        } else {
            $("#quantity").css({
                'border': ''
            });
            $("#butsave").attr('disabled', false);
        }
    });

    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        return parts.join(".");
    }
    $(document).on('input', function () {
        $("#quantity").each(function () {
            var num = $(this).text();
            var commaNum = numberWithCommas(num);
            $(this).text(commaNum);
        });
    });


    $(document).focusout(function () {
        $("input#quantity").blur(function () {
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(0);
            $(this).val(cleanNum);
            cleanNum = isNaN(cleanNum) ? '0' : cleanNum;
            $(this).val(cleanNum);
            $("#butsave").attr('disabled', false);
            $(this).css({
                'border': ''
            });
            $("#quantity").tooltip({
                title: "Invalide Value"
            }).tooltip('dispose');
            if (num / cleanNum < 1) {}
        });
    });

    //Entry Pirce input validation
    $(document).on('input', function () {
        $("#entry_price").blur(function () {
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(5);
            cleanNum = isNaN(cleanNum) ? '1.00000' : cleanNum;
            $(this).val(cleanNum);
            $("#butsave").attr('disabled', false);
            $(this).css({
                'border': ''
            });
            $("#entry_price").tooltip({
                title: "Invalide Value"
            }).tooltip('dispose');
        });
    });

    $("#entry_price").keyup(function (e) { //P/L Pips lenght of value
        txtVal = $(this).val();
        var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
        if (txtVal.length > 16 || (!$("#entry_price").val().match($regex))) {
            $(this).val(txtVal.substring(0, 16))
            $("#butsave").attr('disabled', true);
            $("#entry_price").tooltip({
                title: "Invalid Value"
            }).tooltip('show');
            $("#entry_price").css({
                'border': '1px solid rgba(255, 37, 37, 0.68)'
            });
            return false;
        } else {
            $("#butsave").attr('disabled', false);
            $("#entry_price").tooltip({
                title: "Invalid Value"
            }).tooltip('dispose');
            $("#entry_price").css({
                'border': ''
            });
        }
    });


    //Take Profit price input validation
    $(document).on('input', function () {
        $("#tp_price").blur(function () {
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(5);
            cleanNum = isNaN(cleanNum) ? '1.00000' : cleanNum;
            $(this).val(cleanNum);
            $("#butsave").attr('disabled', false);
            $(this).css({
                'border': ''
            });
            $("#tp_price").tooltip({
                title: "Invalide Value"
            }).tooltip('dispose');
        });
    });

    $("#tp_price").keyup(function (e) { //P/L Pips lenght of value
        txtVal = $(this).val();
        var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
        if (txtVal.length > 16 || (!$("#tp_price").val().match($regex))) {
            $(this).val(txtVal.substring(0, 16))
            $("#butsave").attr('disabled', true);
            $("#tp_price").tooltip({
                title: "Invalid Value"
            }).tooltip('show');
            $("#tp_price").css({
                'border': '1px solid rgba(255, 37, 37, 0.68)'
            });
            return false;
        } else {
            $("#butsave").attr('disabled', false);
            $("#tp_price").tooltip({
                title: "Invalid Value"
            }).tooltip('dispose');
            $("#tp_price").css({
                'border': ''
            });
        }
    });


    //Stop Los price input validation
    $("#sl_price").keyup(function (e) { //P/L Pips lenght of value
        txtVal = $(this).val();
        sl = $('#sl_price').val();
        entry = $('#entry_price').val();
        tp = $('#tp_price').val();
        var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
        if (sl == entry || sl == tp || (!$("#sl_price").val().match($regex))) {
            $(this).val(txtVal.substring(0, 8))
            $("#butsave").attr('disabled', true);
            $("#sl_price").tooltip({
                title: "Invalid Value"
            }).tooltip('show');
            $("#sl_price").css({
                'border': '2px solid rgba(255, 37, 37, 0.68)'
            });
            return false;
        } else {
            $("#butsave").attr('disabled', false);
            $("#sl_price").tooltip({
                title: "Maximum P/L pips"
            }).tooltip('dispose');
            $("#sl_price").css({
                'border': ''
            });
        }
    });

    $(document).on('input', function () {
        $("#sl_price").blur(function () {
            var num = parseFloat($(this).val());
            var cleanNum = num.toFixed(5);
            cleanNum = isNaN(cleanNum) ? '1.00000' : cleanNum;
            $(this).val(cleanNum);
            $("#butsave").attr('disabled', false);
            $(this).css({
                'border': ''
            });
            $("#sl_price").tooltip({
                title: "Invalide Value"
            }).tooltip('dispose');
        });
    });

    $("#sl_price").keyup(function (e) { //P/L Pips lenght of value
        txtVal = $(this).val();
        var $regex = /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:(\.|,)\d+)?$/;
        if (txtVal.length > 16 || (!$("#sl_price").val().match($regex))) {
            $(this).val(txtVal.substring(0, 16))
            $("#butsave").attr('disabled', true);
            $("#sl_price").tooltip({
                title: "Invalid Value"
            }).tooltip('show');
            $("#sl_price").css({
                'border': '1px solid rgba(255, 37, 37, 0.68)'
            });
            return false;
        } else {
            $("#butsave").attr('disabled', false);
            $("#sl_price").tooltip({
                title: "Invalid Value"
            }).tooltip('dispose');
            $("#sl_price").css({
                'border': ''
            });
        }
    });

    getSymbol();
    function getSymbol(query) {
        //var load_symbol = $("#symbol").val();
        $.ajax({
            url: "trade-record/symbol.php",
            type: "POST",
            data: {
                query: query
            },
            success: function (data) {
                $('.styleSelect-list-ul').html(data);
                $("#symbol").styleSelect({
                    textshadow: "none",
                    disableinput: "false",
                    margin:'9px auto auto auto',
                    search: true,
                    readonly: false
                });
            }
        })
    }
    
    $('#symbol').keyup(function () {
        var query = $('#symbol').val();
        getSymbol(query);
    });
    
    $('.deteils').on("click", function () {
        $('.trade-tab').find('.deteils.active-btn').removeClass('active-btn');
        $(this).addClass('active-btn');
    });

    $('#butsave').on('click', function () {
        var save = $('#butsave').val();
        var type_side = $('input[name="type_side"]:checked').val();
        var symbol = $('#symbol').val();
        var quantity = $('#quantity').val();
        var entry_price = $('#entry_price').val();
        var tp_price = $('#tp_price').val();
        var sl_price = $('#sl_price').val();
        var time_frame = $('#time_frame option:selected').text();
        var strategy = $('#strategy option:selected').val();
        var entry_rules = [];
        $.each($("#entry_rules option:selected"), function() {
            entry_rules.push($(this).val());
        });
        var tp_rule = $('#tp_rule option:selected').val();
        var commentar = $('#commentar').val();
        var entry_date = $('#entry_date').val();
        var exit_date = $('#exit_date').val();
        var profit_loss_currency = $('#profit_loss_currency').val();
        var profit_loss_pips = $('#profit_loss_pips').val();
        var returnpertrade = $('#returnpertrade').val();

        if (symbol !== "" && entry_date !== "" && exit_date !== "" && price_error == false && date_error == false && entry_price !== "" && tp_price !== "" && sl_price !== "" && profit_loss_currency !== "" && profit_loss_pips !== "") {
            $.ajax({
                url: "trade-record/record-first-colum.php",
                type: "POST",
                data: {
                    save: save,
                    type_side: type_side,
                    symbol: symbol,
                    quantity: quantity,
                    entry_price: entry_price,
                    tp_price: tp_price,
                    sl_price: sl_price,
                    time_frame: time_frame,
                    strategy: strategy,
                    entry_rules: entry_rules,
                    tp_rule: tp_rule,
                    commentar: commentar,
                    entry_date: entry_date,
                    exit_date: exit_date,
                    profit_loss_currency: profit_loss_currency,
                    profit_loss_pips: profit_loss_pips,
                    returnpertrade: returnpertrade
                },
                success: function (response) {
                    console.log(response);
                    if (response == 1) {
                        $('#TradeInfoMessageContainer').css("display", "block").html('Entry or exit date cannot be smaller than portfolio start date');
                        setTimeout(function () {
                            $('#TradeInfoMessageContainer').css("display", "none");
                        }, 4500);
                    } else {
                        $("#TradeRecord").trigger("reset");
                        $(".return_per_trade").html('0.00<small> %</small>');
                        $("#risk_reward").html(0);
                        $("#duration_trade").html(0);
                        $('#strategy').val(null).trigger("change");
                        $('#entry_rules').val(null).trigger("change");
                        $('#tp_rule').val(null).trigger("change");
                        $('#sl_rule').val(null).trigger("change");
                        $('#TradeInfoMessageContainer').css("display", "block").html('The trade was successful recorded.');
                        setTimeout(function () {
                            $('#TradeInfoMessageContainer').css("display", "none");
                        }, 4500);
                    }
                }
            });
        } else {
            if (price_error == true) {
                $('#TradeInfoMessageContainer').css("display", "block").html('Change the price.');
                setTimeout(function () {
                    $('#TradeInfoMessageContainer').css("display", "none");
                }, 4500);
            }
            if (entry_date == "" || exit_date == "") {
                $('#TradeInfoMessageContainer').css("display", "block").html('Select Entry Date');
                setTimeout(function () {
                    $('#TradeInfoMessageContainer').css("display", "none");
                }, 4500);
            }
            if (date_error == true) {
                $('#TradeInfoMessageContainer').css("display", "block").html('Entry date must be before exit date.');
                setTimeout(function () {
                    $('#TradeInfoMessageContainer').css("display", "none");
                }, 4500);
            }
            if (symbol == "" || profit_loss_pips == "" || profit_loss_currency == "") {
                $('#TradeInfoMessageContainer').css("display", "block").html('You need to fill all required fields.');
                setTimeout(function () {
                    $('#TradeInfoMessageContainer').css("display", "none");
                }, 4500);
            }
            if (entry_price == "" || tp_price == "" || sl_price == "") {
                $('#TradeInfoMessageContainer').css("display", "block").html('You need to fill all required fields.');
                setTimeout(function () {
                    $('#TradeInfoMessageContainer').css("display", "none");
                }, 4500);
            }
        }
    });
});