(function ($) {
    "use strict";

    $( document ).ready(function() {
    	$('.money-widget').each(function() {
    		var moneyWidget = new $.MoneyWidget($(this));
    		moneyWidget.parseCurrencyInput();
    	})
    });

    $('.amount-input').blur(function() {
    	var moneyWidget = $.MoneyWidget($(this).closest('.money-widget'));
    	moneyWidget.parseCurrencyInput();
    });

}(jQuery));
