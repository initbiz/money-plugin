(function ($) {
    "use strict";
    /**
     * Data attributes config manager for money widgets
     * @param {Object} domElement DOM Object with data config parameters
     */
    var ConfigManager = function (domElement) {
        /**
         * Money-config div
         * @type {Object}
         */
        this.domElement = domElement;
    };

    /**
     * Get config of domElement
     * @param  {string}     [name=null] name of config you want to get
     * @return {Object|mixed}           returns all object data or value of config specified in name param
     */
    ConfigManager.prototype.get = function (name = null) {
        if (name) {
            return this.domElement.data(name);
        }
        return this.domElement.data();
    };

    /**
     * Set config on the domElement
     * @param  {string} name  name of config you want to set
     * @param  {mixed}  value value of config you want to set
     */
    ConfigManager.prototype.set = function (name, value) {
        this.domElement.data(name, value);
    };

    /**
     * set data attribute on every element with given selector
     * @param  {string} name            name of the data attribute to set
     * @param  {mixed} value            value of the data attribute to set
     * @param  {string} [selector=null] selector to get elements which you want to set
     */
    ConfigManager.prototype.setAll = function (name, value, selector = null) {
        if (!selector) {
            selector = '.money-config';
        }

    	$(selector).each(function() {
            $(this).data(name, value);
    	})
    };

    $.ConfigManager = ConfigManager;
}(jQuery));
