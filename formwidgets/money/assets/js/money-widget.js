(function ($) {
    "use strict";
    /**
     * Money form widget
     * @param {Object} domElement DOM Object with money form widget
     */
    var MoneyWidget = function (domElement) {
        /**
         * Money-widget div
         * @type {[type]}
         */
        this.domElement = domElement;

        var config = this.domElement.find($('.money-config')).data();

        this.manipulator = new $.MoneyManipulator(config);

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
