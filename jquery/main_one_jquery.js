//On load page show spinner
$(window).on('load', function () {
    setTimeout(function () {
        $('#cover').css("display", "none");
    }, 1000);
});


$(document).ready(function () {
    /*enable all tooltips*/
    $(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
    
    //deposit-withradw
    $("#dateRange").styleSelect({ 
       
    })
    
    $("#portfolio").styleSelect({
        
    })
    
    //all trades dropdown
    $("#sort_by_profit").styleSelect({
         
    })
     $("#sort_by").styleSelect({
            
    })
    $("#sort_portfolio").styleSelect({
        
    })
    $("#result_per_page").styleSelect({
        margin:'4px auto auto -15.5px',
    })
     $(".date-allTrades").styleSelect({
        disableinput: true,
         readonly:false
    })
    
    $("#symbol_edit_trade").styleSelect({
        textshadow: "none",
        disableinput: "false",
        margin:'0px auto auto auto',
        search: true,
        readonly: false
    })
  
  
 
  
    
    
    
    
      
    
    
    
    
    //Trade Performance
    $("#portfolios").styleSelect({
        
    })
    $(".date-performance").styleSelect({
        disableinput: true,
        
    })
    


    
    /*  Mega Tab*/
    $('.hero_tabs li a').on('click', function () {
        $('.hero_tabs').find('li.active').removeClass('active');
        $(this).parent('li').addClass('active');
    });

    /*Minimize - maximize Mega-tabs classes mainpage-traderecord-style.CSS*/
    $(".nav-menu .nav-tabs a").dblclick(function () {
        $(".rowchart").addClass('minimize-top');
        $(".rowtab").addClass('minimize-bottom');
    });
    $(".nav-menu .nav-tabs a").click(function () {
        $(".rowchart").removeClass('minimize-top');
        $(".rowtab").removeClass('minimize-bottom');
    });

    $(".min-max-btn").click(function () {
        $(".rowchart").toggleClass('minimize-top');
        $(".rowtab").toggleClass('minimize-bottom');
    });
    $('.min-max-btn').tooltip({
        title: "Toggle",
        placement: "left",
        trigger: "hover"
    });

    /**********************/

    /* Trade record / performance  tabs*/
    $('.container-2 .nav-tabs a').on('click', function () {
        $('.container-2 .nav-tabs').find('li.active').removeClass('active');
        $(this).parent('li').addClass('active');
    });

    /*  My trading rules Tab*/
    $('#TradingSettings .nav-tabs a').on('click', function () {
        $('#TradingSettings .nav-tabs').find('li.active').removeClass('active');
        $(this).parent('li').addClass('active');
    });


    /*END*/

    /*Sort table row*/

    $(document).on('click', '.sort_col', function () {
        var table = $(this).parents('table').eq(0);
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
        this.asc = !this.asc;
        if (!this.asc) {
            rows = rows.reverse();
        }
        table.children('tbody').empty().html(rows);
    });

    function comparer(index) {

        return function (a, b) {
            var valA = getCellValue(a, index),
                valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB) ?
                valA - valB : valA.localeCompare(valB);
        };
    }

    function getCellValue(row, index) {
        return $(row).children('td').eq(index).text();
    }
    /******************/
    
    
    
    
    
});
   
function rec_trade_panel_toggle() {
        $(".left").toggleClass("display_trade_panel") // sidebar_modal.css
    };
    
    function openNav() {
        $(".side").addClass("display_accountmanager"); // sidebar_modal.css
        
    }

    function openNavIfNoPOrtfolio() {
        $(".side").addClass("display_accountmanager"); // sidebar_modal.css
        $(".closeaccountmanager").hide();
    }


    function closeNav() {
        $(".side").removeClass("display_accountmanager");
    }

    function toggleChosePortfolio() {
       if($("#trade-left").hasClass("active")){
           $('.accountInfoContainer').removeClass('active');
           $(".chose_portfolio_tab").addClass('active');
       }else {
           $('.chose_portfolio_tab').removeClass('active');
           $("#trade-left").addClass('active');
       }
           
    }

    function openSwitchPortfolio(){
        $('.accountInfoContainer').removeClass('active');
           $(".chose_portfolio_tab").addClass('active');
    }


 


