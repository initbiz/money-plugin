(function ($) {
    "use strict";

    function registerMoneyHandlers() {
    	$('.money-widget').each(function() {
    		var moneyWidget = new $.MoneyWidget($(this));
    		moneyWidget.parseCurrencyInputRaw();
    	})

        $('.amount-input').blur(function() {
        	var moneyWidget = new $.MoneyWidget($(this).closest('.money-widget'));
        	moneyWidget.parseCurrencyInput();
        });
    }

    $( document ).ready(function() {
        registerMoneyHandlers();
    });

    $(window).on('ajaxUpdateComplete', function(event, context, data) {
        registerMoneyHandlers();
    })

}(jQuery));
