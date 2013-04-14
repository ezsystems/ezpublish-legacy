YUI(YUI3_config).add('ezajaxuploader', function (Y) {

    /**
     * Constructor of the eZAjaxUploader component
     *
     * @param modalWindow eZModalWindow instance
     * @param conf Configuration object containing the following items
     *          - title String, title of the modal window
     *          - requiredInput [default "input.input-required"] selector to get the required input
     *          - labelErrorClass [default "message-error"] CSS class to add on a label for a required field if this field is filled
     *          - parseJSONErrorText [default "Unable to parse the JSON response."] text to show when the JSON response can not be parsed
     *          - validationErrorText [default "Some required fields are empty."] text to show if a required field is not filled
     *          - validationErrorTextElement [default ".ajaxuploader-error"] selector to get the element where to put the validationErrorText
     *          - errorTemplate [default"'<div class="message-error">%message</div>'"] template to use when displaying a server side error, %message is the variable for the message
     *          - defaultValuedInputClass [default "has-default-value"] CSS class set on input that have a default value
     *          - loading Object containing the following values
     *              - opacity [default 0.2] opacity to set while an AJAX request is being done
     *              - loader [default "#ajaxuploader-loader"] selector to get the GIF loader to show
     *              - zIndex [default 51] z-index style to set on the GIF loader
     *          - target Object containing the hidden fields to post in each POST request
     *          - open Object containing the following values
     *              - action eZJSCore ajax action to use to open the window
     *          - upload Object containing the following values
     *              - action eZJSCore ajax action to use to upload a file
     *              - form selector to get the upload form relative to window's content
     *          - location Object containing the following values
     *              - action eZJSCore ajax action to use to post the location
     *              - form selector to get the location form relative to window's content
     *              - browse selector to get the are where links are used to browse the content tree
     *              - required the error message to display if the user does not select a location
     *          - preview Object containing the following values
     *              - form selector to get the preview form relative to window's content
     *              - callback callback function called at the end of the process
     */
    function eZAjaxUploader(modalWindow, conf) {

        this.conf = Y.merge(eZAjaxUploader.DEFAULT_CONFIG, conf);
        this.modalWindow = modalWindow;

        this.lastMetaData = false;
        this.windowEvents = [];

        var that = this;
        this.defaultAjaxConfiguration = {
            on:{
                start: function () {
                    that.waitAjax();
                },
                end: function () {
                    that.endAjax();
                },
                success: function (transactionId, data) {
                    var input;
                    if ( data.responseJSON.error_text != "" ) {
                        that.displayError(data.responseJSON.error_text);
                    } else {
                        that.lastMetaData = data.responseJSON.content.meta_data;
                        that.modalWindow.setContent(data.responseJSON.content.html);
                    }
                }
            },
            method: 'POST'
        };
    };

    eZAjaxUploader.HANDLER_FIELD_NAME = "AjaxUploadHandlerData";
    eZAjaxUploader.HAD_DEFAULT_VALUE = "had-default-value";

    eZAjaxUploader.DEFAULT_CONFIG = {
        requiredInput: 'input.input-required',
        labelErrorClass: 'message-error',
        validationErrorText: "Some required fields are empty.",
        parseJSONErrorText: "Unable to parse the JSON response.",
        validationErrorTextElement: '.ajaxuploader-error',
        errorTemplate: '<div class="message-error">%message</div>',
        defaultValuedInputClass: 'has-default-value',
        loading:{
            opacity: 0.2,
            loader: "#ajaxuploader-loader",
            zIndex:51
        }
    };

    /**
     * Displays the errorText in the modal window using the template
     * from the configuration
     *
     * @param errorText
     */
    eZAjaxUploader.prototype.displayError = function (errorText) {
        var e = Y.Node.create(this.conf.errorTemplate),
            contentNode = this.modalWindow.getContentNode();
        e.setContent(e.get('innerHTML').replace('%message', errorText));
        contentNode.setContent(e);
    };

    /**
     * Adds an effect while an AJAX request is being done
     */
    eZAjaxUploader.prototype.waitAjax = function () {
        var contentNode = this.modalWindow.getContentNode(),
            xy = contentNode.getXY(),
            height = parseInt(contentNode.getStyle('height')),
            width = parseInt(contentNode.getStyle('width')),
            img = Y.one(this.conf.loading.loader);

        xy[0] = xy[0] + (width/2);
        xy[1] = xy[1] + (height/4);
        contentNode.setStyle('opacity', this.conf.loading.opacity);
        img.show();
        img.setStyle('zIndex', this.conf.loading.zIndex)
        img.setXY(xy)

    };

    /**
     * Stops the effect set by eZAjaxUploader::waitAjax()
     */
    eZAjaxUploader.prototype.endAjax = function () {
        var contentNode = this.modalWindow.getContentNode();

        contentNode.setStyle('opacity', 1);
        Y.one(this.conf.loading.loader).hide();
    };

    /**
     * Defines the events needed by eZAjaxUploader
     */
    eZAjaxUploader.prototype.delegateWindowEvents = function () {

        var contentNode = this.modalWindow.getContentNode();
        var that = this, sub, defaultValues = {};

        var clearDefaultValueHint = function (e) {
            defaultValues[this.generateID()] = this.get('value');
            this.set('value', '');
            this.removeClass(that.conf.defaultValuedInputClass).addClass(eZAjaxUploader.HAD_DEFAULT_VALUE);
        };
        sub = contentNode.delegate(
            'click', clearDefaultValueHint, '.' + this.conf.defaultValuedInputClass
        );
        this.windowEvents.push(sub);

        var restoreDefaultValueHint = function(e) {
            var id = this.generateID();
            if ( this.get('value') == '' && defaultValues[id] ) {
                this.set('value', defaultValues[id]);
                this.addClass(that.conf.defaultValuedInputClass);
            }
        };
        sub = contentNode.delegate(
            'blur', restoreDefaultValueHint, '.' + eZAjaxUploader.HAD_DEFAULT_VALUE
        );
        this.windowEvents.push(sub);

        /**
         * Highlight the submit button by adding the "defaultbutton" class when a location is choosen
         * Using click instead of change to make it works in IE
         */
        var highlightSubmitButton = function (e) {
            contentNode.one('input[type="submit"]').addClass('defaultbutton');
            contentNode.one(that.conf.validationErrorTextElement).setContent('');
        };
        sub = contentNode.delegate(
            'click', highlightSubmitButton, this.conf.location.browse + ' input[type="radio"]'
        );
        this.windowEvents.push(sub);

        /**
         * Highlights the button to submit the upload form
         * as soon as the file input is filled
         * @todo find a way to fix this in IE
         */
        sub = contentNode.delegate('change', highlightSubmitButton, 'input[type="file"]');
        this.windowEvents.push(sub);

        // Internet Explorer does not support delegate() on form submit...
        // http://yuilibrary.com/forum/viewtopic.php?p=7784
        // As a workaround, click on submit button is used to detect
        // form submit...

        /**
         * Makes generic operations on the form to be submited:
         *   - check required fields
         *   - fill the token if necessary  (see ezformtoken)
         *   - add hidden fields
         */
        var formPreSubmit = function (e) {
            var valid = true,
                hiddenPlace = contentNode.one('form p'),
                form = this.ancestor('form', false);

            contentNode.all(that.conf.requiredInput).each(function () {
                if ( !this.get('value') ) {
                    contentNode.all('label[for="' + this.get('id') + '"]').addClass(that.conf.labelErrorClass);
                    valid = false;
                }
            });
            if ( !valid ) {
                contentNode.one(that.conf.validationErrorTextElement).setContent(that.conf.validationErrorText);
                e.halt(true);
                return;
            }
            contentNode.all('label').removeClass(that.conf.labelErrorClass);
            contentNode.all('.has-default-value').set('value', '');
            contentNode.one(that.conf.validationErrorTextElement).setContent("");

            for(var k in that.conf.target) {
                hiddenPlace.append('<input type="hidden" name="' + eZAjaxUploader.HANDLER_FIELD_NAME + '[' + k + ']" value="' + that.conf.target[k] + '" />');
            }
            if ( that.conf.token )
                hiddenPlace.append('<input type="hidden" name="ezxform_token" value="' + that.conf.token + '" />');


        };
        sub = contentNode.delegate(
            'click', formPreSubmit, 'form input[type="submit"]'
        );
        this.windowEvents.push(sub);

        /**
         * Performs the upload
         */
        var upload = function (e) {
            e.halt(true);
            that.waitAjax()
            form = this.ancestor('form', false);

            var c = Y.clone(that.defaultAjaxConfiguration, true),
                ioCompleteSub = false;

            c.form = {
                id: form,
                upload: true
            };
            c.on.success = undefined;

            ioCompleteSub = Y.on('io:complete', function (transactionId, data) {
                // data.responseText is in fact a json encoded hash
                // and the value with 'html' key is url encoded to keep
                // the HTML...
                // see ezjscServerFunctionsAjaxUploader::upload()
                var json;

                ioCompleteSub.detach();
                try {
                    json = Y.JSON.parse(data.responseText);
                } catch (e) {
                    that.displayError(that.conf.parseJSONErrorText);
                    that.endAjax();
                    return;
                }
                if ( json.error_text ) {
                    that.displayError(json.error_text);
                } else {
                    that.modalWindow.setContent(decodeURIComponent(json.html));
                }
                that.endAjax();
            });

            Y.io(Y.io.ez.url + 'call/' + that.conf.upload.action, c);
        };
        sub = contentNode.delegate(
            'click', upload, this.conf.upload.form + ' input[type="submit"]'
        );
        this.windowEvents.push(sub);

        /**
         * Post the location form to set the location
         * of the future content object
         */
        var postLocation = function (e) {
            var c = Y.clone(that.defaultAjaxConfiguration, true),
                form = this.ancestor('form', false),
                radios = contentNode.all('input[type="radio"]'),
                checked = false;

            // forced to loop over radios button because
            // :checked selector does not work in IE
            radios.each(function () {
                if ( this.get('checked') ) {
                    checked = true;
                }
            });

            if ( !checked ) {
                contentNode.one(that.conf.validationErrorTextElement).setContent(that.conf.location.required);
                e.halt(true);
                return;
            }


            c.form = {
                id: form
            };
            Y.io.ez(that.conf.location.action, c);
            e.halt();
        };
        sub = contentNode.delegate(
            'click', postLocation, this.conf.location.form + ' input[type="submit"]'
        );
        this.windowEvents.push(sub);

        /**
         * Set click event in browse area to browse for the location
         * of the future content object
         */
        var browse = function (e) {
            var c = Y.clone(that.defaultAjaxConfiguration, true),
                placeholder = contentNode.one(that.conf.location.browse);

            e.halt(true);
            c.on.success = function (transactionId, data) {
                if ( data.responseJSON.error_text != "" ) {
                    that.displayError(data.responseJSON.error_text);
                } else {
                    that.lastMetaData = data.responseJSON.content.meta_data;
                    placeholder.setContent(data.responseJSON.content.html);
                    contentNode.one('input[type="submit"]').removeClass('defaultbutton').addClass('button');
                }
            };
            c.method = 'GET';
            Y.io.ez(e.currentTarget.getAttribute('href'), c);
        };
        sub = contentNode.delegate(
            'click', browse, this.conf.location.browse + ' a'
        );
        this.windowEvents.push(sub);


        /**
         * Call the preview.callback when the last form is submitted
         */
        var endForm = function (e) {
            e.halt();
            that.conf.preview.callback.call(that);
        };
        sub = contentNode.delegate(
            'click', endForm, this.conf.preview.form + ' input[type="submit"]'
        );
        this.windowEvents.push(sub);

        /**
         * Last event handler submitted before any submit
         * Make sure we cannot click several times on the submit buttons
         */
        var formLastBeforeSubmit = function (e) {
            this.addClass('button-disabled').removeClass('defaultbutton').removeClass('button').set('disabled', 'disabled');
        };
        sub = contentNode.delegate(
            'click', formLastBeforeSubmit, 'form input[type="submit"]'
        );
        this.windowEvents.push(sub);
    };

    /**
     * Detaches the events set by eZAjaxUploader::delegateWindowEvents
     */
    eZAjaxUploader.prototype.detachWindowEvents = function () {
        for (var i = 0; i != this.windowEvents.length; i++) {
            this.windowEvents[i].detach();
        }
    };

    /**
     * Build the POST string to use with the necessary POST parameters
     *
     * @return string
     */
    eZAjaxUploader.prototype.buildPostString = function () {
        var res = '';
        for (var k in this.conf.target) {
            if ( res != '' )
                res += '&';
            res += eZAjaxUploader.HANDLER_FIELD_NAME + '[' + k + ']=' + this.conf.target[k];
        }
        return res;
    };

    /**
     * Cleans the state of the eZAjaxUploader instance
     *    - detach events
     *    - hide the AJAX loader if it's not hidden
     */
    eZAjaxUploader.prototype.cleanup = function () {
        this.detachWindowEvents();
        this.endAjax();
    };

    /**
     * Opens the upload window and delegate events
     */
    eZAjaxUploader.prototype.open = function () {
        var c = Y.clone(this.defaultAjaxConfiguration, true);

        this.delegateWindowEvents();

        this.modalWindow.setTitle(this.conf.title);
        this.modalWindow.open();
        this.modalWindow.onClose(this.cleanup, this);

        c.data = this.buildPostString();
        Y.io.ez(this.conf.open.action, c);
    };

    Y.namespace('eZ');

    Y.eZ.AjaxUploader = eZAjaxUploader;

}, '1.0.0', {
    requires: [
        'ezmodalwindow', 'io-ez', 'io-form', 'io-upload-iframe', 'json-parse'
    ]
});
