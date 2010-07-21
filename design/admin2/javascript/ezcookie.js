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
         * Retrieves multiple values delimited by '|'
         * @param {String} The name of the subcookie to retrieve.
         * @return {Array} The cookie sub values as Strings or null if non-existant
         * @private
         */
        function _getCookieSubMultiValue(subName) {
            var sub = Y.Cookie.getSub(config.name, subName);
            if (sub) { sub = unescape(sub).split('|'); }
            return sub;
        }

        /**
         * Sets or replaces cookie
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
         * Adds or replaces subcookie
         * @param {String} The name of the subcookie to set.
         * @param {String} The value of the subcookie.
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
         * Sets or replaces all subcookies
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

        /**
         * Adds or replaces subcookie with multiple delimited ('|') values
         * @param {String} The name of the subcookie to set.
         * @param {Array} The values of the subcookie to set.
         * @private
         */
        function _setCookieSubMultiValue(subName, values) {
            var joined = values.join('|');
            Y.Cookie.setSub(config.name, subName, escape(joined), {
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
            setCookieSubMultiValue : _setCookieSubMultiValue,
            getCookieValue : _getCookieValue,
            getCookieSubValue : _getCookieSubValue,
            getCookieSubValues : _getCookieSubValues,
            getCookieSubMultiValue : _getCookieSubMultiValue
        }
    }();

}, '.01', { requires : ['cookie'] });
