(function ($) {
    "use strict";
    //right pad string for a specified length with a padString
    String.prototype.rpad = function(padString, length) {
    	var str = this;
        while (str.length < length)
            str = str + padString;
        return str;
    }
}(jQuery));
