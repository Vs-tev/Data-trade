(function ($) {
    $.fn.styleSelect = function (options) {

        var settings = $.extend({
            color: "#414c52",
            fontWeight: "500",
            fontSize: "14px",
            fontFamily: "Roboto",
            cursor: "pointer",
            listStyle: "none",
            padding: "5px 0px 5px 12px",
            margin: "4px auto auto -5.5px",
            paddingul: "0px",
            width: "100%",
            marginul: "0px",
            disableinput: false,
            Zindex: "99",
            search: false,
            readonly: true,
            
        }, options);

        return this.each(function () {
            $(this).css('color', settings.color);
            $(this).css('font-weight', settings.fontWeight);
            $(this).css('font-size', settings.fontSize);
            $(this).attr('readonly', settings.readonly);

            $(this).on('keydown keyup cut copy paste', function () {
                return settings.disableinput
            });

            var input = $(this);
            var a = $(this).closest('.dropdiv');

            a.find('li').on('click', function () {
                $(this).css('cursor', 'pointer');
                var c = $(this).find('.element-li').text();
                var e = $(this).data('value');
                input.data('value', e)
                input.val(c);
            })
            a.css('width', settings.width);
            a.find('li').css('cursor', settings.cursor);
            a.find(".dropdown-menu").css('margin', settings.margin);
            a.find("li").css('font-weight', settings.fontWeight);
            a.find("li").css('font-size', settings.fontSize);
            a.find("li").css('font-family', settings.fontFamily);
            a.find("li").css('list-style', settings.listStyle);
            a.find("li").css('padding', settings.padding);
            a.find("ul").css('padding', settings.paddingul);
            a.find("ul").css('margin-bottom', settings.marginul);
            a.find(".dropdown-menu").css('z-index', settings.Zindex);

            /*Search in dropdown function*/
            var search = settings.search;
            var $block = $(this).find('.no-results');
            if(search == true){
            input.keyup(function () {
                var val = $(this).val();
                var isMatch = false;
                a.find('li').each(function (i) {
                    var content = $(this).html();
                    if (content.toLowerCase().indexOf(val) == -1) {
                        $(this).hide();
                    } else {
                        isMatch = true;
                        $(this).show();
                    }
                });

                $block.toggle(!isMatch);
            });
    }




        });
    }
}(jQuery));





