/**
 * Money form widget
 */
class MoneyManipulator {
    /**
     * Manipulate money strings and fields
     * @param {Object} config configuration of currency and amount to manipulate
     */
    constructor(config) {
		this.setConfig(config);
    }

	setConfig (config) {
		this.config = config;
	}

	newDineroObj(amount) {
		var dineroObj = Dinero({
			amount: amount,
			currency: this.config.currencyCode,
			precision: this.config.fractionDigits
		}).setLocale('en');

		return dineroObj;
	}

	/**
	 * Add fraction digits, rpad with zeros if zero
	 * @param {string} value  number string to add fraction digits
	 * @param {number} places number of fraction digits (default 2)
	 */
	addFractionDigits(value, places = 2) {
		var replaceString = "/" + this.config.decimalPoint + "/g";
		value = value.replace(replaceString, "\.");
		//split value by dot
		var array = value.split('\.');

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
	}

	/**
	 * remove all non numeric and decimalPoint chars from string
	 * @param  {string} value 	string to escape
	 * @return {string}        	escaped string
	 */
	removeNonNumeric(value) {
		//convert to string
		value = "" + value;
		//remove all non-numeric chars and white spaces
		var replaceString = "/[^0-9" + this.config.decimalPoint + "]\s/g";
	    value = value.replace(replaceString, "");

		return value;
	}

    parseCurrency(value) {
        value = this.removeNonNumeric(value);
		value = this.addFractionDigits(value);
        value = this.formatMoney(value);
		return value;
    }

	/**
	 * Get amount in human readable syntax using the currency config
	 * @param  {string} value string or integer of amount in integer
	 * @return {string}       human readable beauty syntaxed amount using currency config
	 */
	formatMoney(value) {
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

		if (this.config.placeSymbolBefore) {
			str = this.config.currencySymbol + str;
		} else {
			str = str + this.config.currencySymbol;
		}
		return str;
	}

	/**
	 * Get integer amount out of string with formatting
	 * @param  {string} value 	String with commas, spaces, currency sign etc
	 * @return {int}       		amount in integer
	 */
	unformatMoney(value) {
		if (value == "") {
			return 0;
		}
	    var intValue = removeNonNumeric(value);
	    return intValue;
	}

	cAdd(param1, param2) {
		//TODO: move to newDineroObj method
	    let amount = Dinero({amount: param1}).add(Dinero({amount: param2})).getAmount();
		return amount;
	}

	cSubtract(param1, param2) {
	    let amount = Dinero({amount: param1}).subtract(Dinero({amount: param2})).getAmount();
		return amount;
	}

	cMultiply(param1, param2) {
	    let amount = Dinero({amount: param1}).multiply(param2).getAmount();
		return amount;
	}

	cDivide(param1, param2) {
	    let amount = Dinero({amount: param1}).divide(param2).getAmount();
		return amount;
	}

	cPercentage(param1, param2) {
	    let amount = Dinero({amount: param1}).percentage(param2).getAmount();
		return amount;
	}

}

// Dinero.globalLocale = 'pl-PL';
// Dinero.defaultCurrency = 'PLN';



// $(window).on('ajaxUpdateComplete', function(event, context, data) {
// 	$('.currency-value, .currency span').each(function() {
// 	    var value = $(this).html();
// 	    value = parseCurrency(value);
// 	    $(this).html(value);
// 	})
// 	$('.currency input').each(function() {
// 	    var value = $(this).val();
// 		if (value != "") {
// 		    value = parseCurrency(value);
// 		}
// 	    $(this).val(value);
// 	})
// })
