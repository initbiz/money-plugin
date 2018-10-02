(function ($) {
    "use strict";
    /**
     * Money manipulator for money strings and fields
     * @param {Object} config configuration of currency and amount to manipulate
     */
    var MoneyManipulator = function (config) {
        this.config = config;
    };

    /**
     * Make Dinero object with the amount using this.config
     * @param  {integer} amount Amount
     * @return {Dinero}        	Created Dinero object
     */
    MoneyManipulator.prototype.newDineroObj = function (amount) {
        var dineroObj = Dinero({
            amount: amount,
            currency: this.config.currencyCode,
            precision: this.config.fractionDigits
            //Setting locale to en makes sense
            //it turns out to be a good idea to override the 'en' localed string instead of trying to force format
        }).setLocale('en');

        return dineroObj;
    };

    /**
     * Get nice formatted currency
     * @param  {string|Number} value number you want to display in currency format
     * @return {string}     		 nice currency string
     */
    MoneyManipulator.prototype.parseCurrency = function (value) {
        value = this.addFractionDigits(value);
        value = this.removeNonNumeric(value);
        value = this.formatMoney(value);
        return value;
    };

    /**
     * Get nice formatted currency without adding fraction digits
     * @param  {string|Number} value number you want to display in currency format
     * @return {string}     		 nice currency string
     */
    MoneyManipulator.prototype.parseCurrencyRaw = function (value) {
        value = this.removeNonNumeric(value);
        value = this.formatMoney(value);
        return value;
    };

    /**
     * Add fraction digits, rpad with zeros if zero
     * @param {string} value  number string to add fraction digits
     * @param {number} places number of fraction digits (default 2)
     */
    MoneyManipulator.prototype.addFractionDigits = function (value, places = this.config.fractionDigits) {
        //split value by decimalPoint
        var array = value.split(this.config.decimalPoint);

        if (array[1]) {
            //Slice of fill string with precision number of zeros
            array[1] = array[1].slice(0, places);
            array[1] = array[1].rpad("0", places);
        } else {
            //If array[1] not set, fill with zeros to precision
            array[1] = "".rpad("0", places);
        }

        value = array[0] + "." + array[1];

        return value;
    };

    /**
     * remove all non numeric and decimalPoint chars from string
     * @param  {string|Number} value 	string to escape
     * @return {string}        			escaped string
     */
    MoneyManipulator.prototype.removeNonNumeric = function (value) {
        //convert to string
        value = "" + value;
        //remove all non-numeric chars and white spaces
        value = value.replace(/[^0-9]|\s/g, "");

        return value;
    };


    /**
     * Get amount in human readable syntax using the currency config
     * @param  {string} value string or integer of amount in integer
     * @return {string}       human readable beauty syntaxed amount using currency config
     */
    MoneyManipulator.prototype.formatMoney = function (value) {
        value = Number(value);

        var formatString = "0,0";

        if (this.config.fractionDigits > 0) {
            formatString = formatString + ".";
            for (var i = 0; i < this.config.fractionDigits; i++) {
                formatString = formatString + "0";
            }
        }

        var str = this.newDineroObj(value).toFormat(formatString);

        //replace dot with x so that if thousandSeparator is dot it will not be replaced
        str = str.replace(/\./, "x");
        str = str.replace(/,/g, this.config.thousandSeparator);
        str = str.replace(/x/, this.config.decimalPoint);

        if (this.config.widgetMode == "amount") {
            if (this.config.placeSymbolBefore) {
                str = this.config.currencySymbol + str;
            } else {
                str = str + this.config.currencySymbol;
            }
        }

        return str;
    };

    /**
     * Get integer amount out of string with formatting
     * @param  {string} value 	String with commas, spaces, currency sign etc
     * @return {int}       		amount in integer
     */
    MoneyManipulator.prototype.unformatMoney = function (value) {
        if (value == "") {
            return 0;
        }
        var intValue = removeNonNumeric(value);
        return intValue;
    };

    MoneyManipulator.prototype.cAdd = function (param1, param2) {
        //TODO: move to newDineroObj method
        let amount = Dinero({
            amount: param1
        }).add(Dinero({
            amount: param2
        })).getAmount();
        return amount;
    };

    MoneyManipulator.prototype.cSubtract = function (param1, param2) {
        let amount = Dinero({
            amount: param1
        }).subtract(Dinero({
            amount: param2
        })).getAmount();
        return amount;
    };

    MoneyManipulator.prototype.cMultiply = function (param1, param2) {
        let amount = Dinero({
            amount: param1
        }).multiply(param2).getAmount();
        return amount;
    };

    MoneyManipulator.prototype.cDivide = function (param1, param2) {
        let amount = Dinero({
            amount: param1
        }).divide(param2).getAmount();
        return amount;
    };

    MoneyManipulator.prototype.cPercentage = function (param1, param2) {
        let amount = Dinero({
            amount: param1
        }).percentage(param2).getAmount();
        return amount;
    };

    $.MoneyManipulator = MoneyManipulator;
}(jQuery));
