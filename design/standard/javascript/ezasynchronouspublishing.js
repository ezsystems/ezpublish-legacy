/* jshint camelcase: false */
/* global YUI3_config */
/* exported eZAsynchronousPublishingApp */
var eZAsynchronousPublishingApp = (function() {
    var ret = {}, ajaxURI, Y,
        yCallback = function (yui) {
            Y = yui;
            Y.on("domready", function (e) {
                updateStatus();
            });
        },
        updateStatus = function () {
            Y.io.ez(ajaxURI, {
                on: {
                    success: function (id, r) {
                        var responseDoc = Y.JSON.parse(r.responseText);

                        if ( responseDoc.error_text ) {
                            toggle('#ezap-error');
                            Y.one('#ezap-error').setContent(responseDoc.error_text);
                            Y.later(1000, null, updateStatus, null, false);
                        } else {
                            // publishing finished
                            if ( responseDoc.content.status == 'finished' )
                            {
                                if ( ret.cfg.redirect_uri !== false ) {
                                    window.location = ret.cfg.redirect_uri;
                                } else {
                                    toggle('#ezap-message-finished');
                                    Y.one('#ezap-message-finished #ezap-contentview-uri').set(
                                        'href', responseDoc.content.node_uri
                                    );
                                }
                            } else if ( responseDoc.content.status == 'deferred' ) {
                                // deferred to crontab
                                toggle('#ezap-message-deferred');
                                Y.one('#ezap-message-finished #ezap-contentview-uri').set(
                                    'href', responseDoc.content.versionview_uri
                                );
                            } else {
                                Y.later(1000, null, updateStatus, null, false);
                            }
                        }
                    }
                },
                method: 'GET'
            });
        },
        toggle = function(id) {
            Y.one('.ezap-placeholder').setStyle('display', 'none');
            Y.one(id).setStyle('display', 'block');
        };

    ret.cfg = {};

    ret.init = function() {
        ajaxURI = 'ezpublishingqueue::status::' + ret.cfg.contentobject_id + '::' + ret.cfg.version;
        YUI(YUI3_config).use('node', 'io-ez', 'json-parse', yCallback);
    };

    return ret;
})();
