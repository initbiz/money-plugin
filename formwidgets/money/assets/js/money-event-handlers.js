$(function(){
	$('.money-widget').each(function() {
		var moneyWidget = new Money($(this));
		moneyWidget.parseCurrencyInput();
	})
});
