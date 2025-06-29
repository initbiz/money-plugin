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

addEventListener('page:loaded', function() {
    registerMoneyHandlers();

    // Focus all text in the amount-input fields once first click
    var focusedElement;
    $(document).on('focus', '.amount-input', function () {
        if (focusedElement == this) return;
        focusedElement = this;
        setTimeout(function () { focusedElement.select(); }, 50);
    });
});

addEventListener('ajax:update-complete', function(event) {
    registerMoneyHandlers();
});
