/* jshint camelcase: false */
/* global YUI3_config */
/* exported eZAsynchronousPublishingApp */
var eZAsynchronousPublishingApp = (function() {
    var ret = {}, Y, publishQueueUpdater, ajaxURI,
        yCallback = function (yui, result) {
            Y = yui;
            Y.on("contentready", function (e) {
                updateStatus(Y);
                publishQueueUpdater = Y.later(1000, null, updateStatus, null, true);
            }, '#ezap-message-publishing');
        },
        updateStatus = function () {
            Y.io.ez(ajaxURI, {
                on: {
                    success: function (id, r) {
                        var status;

                        if ( r.responseJSON.error_text ) {
                            toggle('#ezap-error');
                            Y.one('#ezap-error').setContent( r.responseJSON.error_text );
                        } else {
                            status = r.responseJSON.content.status;

                            // publishing finished
                            if ( status == 'finished' )
                            {
                                if ( publishQueueUpdater ) {
                                    publishQueueUpdater.cancel();
                                }

                                if ( ret.cfg.redirect_uri !== false ) {
                                    window.location = ret.cfg.redirect_uri;
                                } else {
                                    toggle('#ezap-message-finished');
                                    Y.one('#ezap-message-finished #ezap-contentview-uri').set(
                                        'href', r.responseJSON.content.node_uri
                                    );
                                }
                            } else if ( status == 'deferred' ) {
                                // deferred to crontab
                                if ( publishQueueUpdater ) {
                                    publishQueueUpdater.cancel();
                                }

                                toggle('#ezap-message-deferred');
                                Y.one('#ezap-message-finished #ezap-contentview-uri').set(
                                    'href', r.responseJSON.content.versionview_uri
                                );
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
        YUI(YUI3_config).use('node', 'io-ez', yCallback);
    };

    return ret;
})();
