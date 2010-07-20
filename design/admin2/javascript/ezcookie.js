/**
 * Using YUI Cookie for cookie management (Revealing module pattern)
 */
var eZCookie = function() {
    var config = {}, Y;

     // Sets expiry date 10 years from now
    var expiresDate = new Date();
    expiresDate.setFullYear(expiresDate.getFullYear() + 10);

    /**
     * @return {String} The cookie value or null if non-existant
     * @private
     */
    var getCookieValue = function() {
        return Y.Cookie.get(config.name);
    }

    /**
     * @param {String} The name of the subcookie to retrieve.
     * @return {String} The cookie sub value or null if non-existant
     * @private
     */
    var getCookieSubValue = function(subName) {
        return Y.Cookie.getSub(config.name, subName);
    }

    /**
     * @return {Object} containing name-value pairs stored in the cookie
     * @private
     */
    var getCookieSubValues = function() {
        return Y.Cookie.getSubs(config.name);
    }

    /**
     * @param The value to set for the cookie.
     * @private
     */
    var setCookieValue = function(value) {
        Y.Cookie.set(config.name, value, {
            path : "/",
            expires : expiresDate,
            secure : config.secure,
            domain : config.domain
        });
    }

    /**
     * @param The name of the subcookie to set.
     * @private
     */
    var setCookieSubValue = function(subName, value) {
        Y.Cookie.setSub(config.name, subName, value, {
            path : "/",
            expires : expiresDate,
            secure : config.secure,
            domain : config.domain
        });
    }

    /**
     * @param {Object} An object containing name-value pairs.
     * @private
     */
    var setCookieSubValues = function(nameValuePairs) {
        Y.Cookie.setSubs(config.name, nameValuePairs, {
            path : "/",
            expires : expiresDate,
            secure : config.secure,
            domain : config.domain
        });
    }

    return {
        /**
         * The initialization of the module
         * @param {Object} An object containing one or more cookie options:
         *      name . (String) The cookie name
         *      domain (String), Restricts access to pages on a given domain
         *      secure (true/false) If the cookie should only be accessible via SSL
         */
        init : function(configuration, yui) {
            config = configuration;
            Y = yui;
        },
        setCookieValue : setCookieValue,
        setCookieSubValue : setCookieSubValue,
        setCookieSubValues : setCookieSubValues,
        getCookieValue : getCookieValue,
        getCookieSubValue : getCookieSubValue,
        getCookieSubValues : getCookieSubValues
    }
}();