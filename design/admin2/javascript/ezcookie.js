/**
 * Created as a YUI3 module so that it can be included in an YUI sandbox
 * 
 * Example:
 * {ezscript_require( array( 'ezjsc::yui3', 'ezcookie.js') )}
 * YUI(YUI3_config).use('ezcookie', function (Y) {
 *    Y.eZCookie.init({name: 'mycookie', domain: 'www.mydomain.net', secure: false});
 * });
 */
YUI.add('ezcookie', function(Y) {

    /**
     * Using YUI Cookie for cookie management (Revealing module pattern)
     */
    Y.eZCookie = function() {
        var config = {};

         // Sets expiry date 10 years from now
        var expiresDate = new Date();
        expiresDate.setFullYear(expiresDate.getFullYear() + 10);

        /**
         * @return {String} The cookie value or null if non-existant
         * @private
         */
        function _getCookieValue() {
            return Y.Cookie.get(config.name);
        }

        /**
         * @param {String} The name of the subcookie to retrieve.
         * @return {String} The cookie sub value or null if non-existant
         * @private
         */
        function _getCookieSubValue(subName) {
            return Y.Cookie.getSub(config.name, subName);
        }

        /**
         * @return {Object} containing name-value pairs stored in the cookie
         * @private
         */
        function _getCookieSubValues() {
            return Y.Cookie.getSubs(config.name);
        }

        /**
         * Sets cookie
         * @param The value to set for the cookie.
         * @private
         */
        function _setCookieValue(value) {
            Y.Cookie.set(config.name, value, {
                path : "/",
                expires : expiresDate,
                secure : config.secure,
                domain : config.domain
            });
        }

        /**
         * Adds subcookie
         * @param The name of the subcookie to set.
         * @private
         */
        function _setCookieSubValue(subName, value) {
            Y.Cookie.setSub(config.name, subName, value, {
                path : "/",
                expires : expiresDate,
                secure : config.secure,
                domain : config.domain
            });
        }

        /**
         * Replaces all subcookies
         * @param {Object} An object containing name-value pairs.
         * @private
         */
        function _setCookieSubValues(nameValuePairs) {
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
            init : function(configuration) {
                config = configuration;
            },
            setCookieValue : _setCookieValue,
            setCookieSubValue : _setCookieSubValue,
            setCookieSubValues : _setCookieSubValues,
            getCookieValue : _getCookieValue,
            getCookieSubValue : _getCookieSubValue,
            getCookieSubValues : _getCookieSubValues
        }
    }();

}, '.01', { requires : ['cookie'] });
