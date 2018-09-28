/**
 * Money form widget
 */
class Money {
    /**
     * create
     * @param {Object} domElement DOM Object with money form widget
     */
    constructor(domElement) {
        /**
         * Money-widget div
         * @type {[type]}
         */
        this.domElement = domElement;

        var config = this.domElement.find($('.money-config')).data();

        this.manipulator = new MoneyManipulator(config);

        this.currencyInput = this.domElement.find($('.amount-input'));
    }

    parseCurrencyInput() {
        var value = this.currencyInput.val();
        value = this.manipulator.parseCurrency(value);
        this.currencyInput.val(value).trigger("change");
    }
}
