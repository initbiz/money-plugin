//right pad string for a specified length with a padString
String.prototype.rpad = function(padString, length) {
	var str = this;
    while (str.length < length)
        str = str + padString;
    return str;
}

/**
 * Round float value to specified places
 * @param  {string} value  Value to round
 * @param  {places} places decimal places
 * @return {string}        rounded value
 */
function round(value, places = 2 ) {
	var exponentialForm = Number(value + 'e' +places);
	var rounded = Math.round(exponentialForm);
	var result = Number(rounded + 'e-' +places).toFixed(places);
	result = parseFloat(result).toString();
    result = result.replace(/\./g, ",");
	return result;
}
