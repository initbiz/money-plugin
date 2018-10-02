(function ($) {
    "use strict";
    /**
     * Money form widget
     * @param {Object} domElement DOM Object with money form widget
     */
    var MoneyWidget = function (domElement) {
        /**
         * Money-widget div
         * @type {Object}
         */
        this.domElement = domElement;

        this.configManager = new $.ConfigManager(this.domElement.find($('.money-config')));

        this.manipulator = new $.MoneyManipulator(this.configManager.get());

        this.currencyInput = this.domElement.find($('.amount-input'));
    };

    MoneyWidget.prototype.parseCurrencyInput = function () {
        var value = this.currencyInput.val();
        value = this.manipulator.parseCurrency(value);
        this.currencyInput.val(value).trigger("change");
    };

    MoneyWidget.prototype.parseCurrencyInputRaw = function () {
        var value = this.currencyInput.val();
        value = this.manipulator.parseCurrencyRaw(value);
        this.currencyInput.val(value).trigger("change");
    };

    $.MoneyWidget = MoneyWidget;
}(jQuery));
