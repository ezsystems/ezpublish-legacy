/* jshint camelcase: false */
/* global YUI3_config */
/* exported eZAsynchronousPublishingApp */
var eZAsynchronousPublishingApp = (function() {
    var ret = {cfg: {}}, ajaxURI, Y,
        DEFAULT_CONF = {
            max_allowed_failures: 5,
            wait_time: 1000,
            redirect_uri: false,
        },
        checkedCount = 0,
        failureCount = 0,
        errorElement, publishingElement, finishedElement, deferredElement;

    /**
     * Regularly checks the status of the function by doing an AJAX request. If
     * the version is published or its status is deferred to a cronjob task the
     * process is stopped, otherwise, we recheck the version status after 1
     * second (by default).
     *
     * @method updateStatus
     * @private
     */
    function updateStatus() {
        Y.io.ez(ajaxURI, {
            on: {
                failure: function () {
                    handleError(ret.cfg.failure_message);
                },
                success: function (id, r) {
                    var responseDoc = Y.JSON.parse(r.responseText);

                    if ( responseDoc.error_text ) {
                        handleError(responseDoc.error_text);
                    } else {
                        if ( responseDoc.content.status == 'finished' )
                        {
                            if ( ret.cfg.redirect_uri !== false ) {
                                window.location = ret.cfg.redirect_uri;
                            } else {
                                displayStatus(finishedElement, function (element) {
                                    element.one('#ezap-contentview-uri').set(
                                        'href', responseDoc.content.node_uri
                                    );
                                });
                            }
                        } else if ( responseDoc.content.status == 'deferred' ) {
                            displayStatus(deferredElement, function (element) {
                                element.one('#ezap-versionview-uri').set(
                                    'href', responseDoc.content.versionview_uri
                                );
                            });
                        } else {
                            if ( checkedCount ) {
                                displayStatus(publishingElement, lastCheckUpdate);
                            }
                            checkedCount++;
                            retryUpdateStatus();
                        }
                    }
                }
            },
            method: 'GET'
        });
    }

    /**
     * Displays the status of the last checks in the given element
     *
     * @method displayStatus
     * @private
     * @param {Y.Node} element the node to update
     * @param {String|Function} the update to make. If it's a string, the
     * content of the element is set with it. it can also be a function, in this
     * case the function called with the element in parameter
     */
    function displayStatus(element, message) {
        Y.all('.ezap-placeholder').setStyle('display', 'none');
        element.setStyle('display', 'block');
        if ( typeof message === 'function' ) {
            message(element);
        } else {
            element.setContent(message);
        }
    }

    /**
     * Handles the error of the AJAX request. If the number of failure exceeds
     * the max_allowed_failures settings, the checking process is stopped and we
     * display the fatal error message
     *
     * @method handleError
     * @private
     */
    function handleError(message) {
        failureCount++;
        if ( failureCount > ret.cfg.max_allowed_failures ) {
            displayStatus(errorElement, message);
        } else {
            if ( checkedCount ) {
                displayStatus(publishingElement, lastCheckUpdate);
            }
            checkedCount++;
            retryUpdateStatus();
        }
    }

    /**
     * Program new update status attempt in wait_time second
     *
     * @method retryUpdateStatus
     * @private
     */
    function retryUpdateStatus() {
        Y.later(ret.cfg.wait_time, null, updateStatus, null, false);
    }

    /**
     * Updates (or creates) the last check message indicating to the user how
     * many times the publishing state was checked. This method is supposed to
     * be passed as a callback to the `displayStatus` method.
     *
     * @method lastCheckUpdate
     * @private
     * @param {Y.Node} element
     */
    function lastCheckUpdate(element) {
        var last = element.one('.last-check');

        if ( !last ) {
            element.append('<span class="last-check"></span>');
            last = element.one('.last-check');
        }
        last.setContent(
            ret.cfg.last_checked_message.replace(
                '%times%', checkedCount
            ).replace('%ms%', ret.cfg.wait_time)
        );
    }

    /**
     * Initializes the component to regularly checks the version status
     * This methods expects the component to be configured before this method is
     * called, see the example below.
     *
     * @example
     *   eZAsynchronousPublishingApp.cfg = {
     *      contentobject_id: 42, // required, content object id
     *      version: 2, // required, version number
     *      failure_message: "Ouch!" // required, fatal error message
     *      redirect_uri: false, // optional, where to redirect the user when
     *                           // the version is getting published
     *      wait_time: 1000, // optional, time in ms to wait between 2 checks
     *      max_allowed_failures: 5, // optional, max number of allowed failure
     *   };
     *   eZAsynchronousPublishingApp.init();
     *
     * @method init
     */
    ret.init = function() {
        YUI(YUI3_config).use('node', 'io-ez', 'json-parse', function (yui) {
            Y = yui;
            ret.cfg = Y.merge(DEFAULT_CONF, ret.cfg);
            ajaxURI = 'ezpublishingqueue::status::' + ret.cfg.contentobject_id + '::' + ret.cfg.version;
            Y.on("domready", function (e) {
                errorElement = Y.one('#ezap-error');
                publishingElement = Y.one('#ezap-message-publishing');
                finishedElement = Y.one('#ezap-message-finished');
                deferredElement = Y.one('#ezap-message-deferred');
                updateStatus();
            });
        });
    };

    return ret;
})();
