YUI(YUI3_config).add('ezmodalwindow', function (Y) {
    /**
     * Constructor of eZModalWindow class
     *
     * @param conf Configuration object containing the following keys
     *              - 'window'   [required] a selector used to get the window container
     *              - 'content'  [default ".window-content"] a relative selector to get the container of the window's content
     *              - 'title'    [default "h2 span"]a relative selector to get the element containing the title text
     *              - 'mask'     [default "#overlay-mask"] a selector to get the mask element
     *              - 'maskOpacity' [default 0.5] the opacity of the mask after animation
     *              - 'close'    [default ".window-close, .window-cancel"] a relative selector of elements that will close the window on click
     *              - 'width'    [default "auto"] the width of the window in pixel
     *              - 'centered' [default "true"] boolean
     *              - 'xy'       [default empty array] array to define the position of the window in pixel, the first element can also be 'centered'
     *              - 'zIndex'   [default 50] the z-index CSS value of the window
     */
    function eZModalWindow(conf) {
        this.conf = Y.merge(eZModalWindow.DEFAULT_CONFIG, conf);

        this.window = Y.one(this.conf.window);
        this.isOpen = false;
        this.closeCallback = {};

        this.mask = null;
        if ( !Y.UA.ie || Y.UA.ie > 6 ) {
            // the mask is ugly in IE6
            this.mask = Y.one(this.conf.mask);
        }
        this.overlay = new Y.Overlay({
            srcNode: this.conf.window,
            width: this.conf.width,
            centered: this.conf.centered,
            visible: false,
            zIndex: this.conf.zIndex
        });

        this.overlay.render();
        this.window.show();

        var that = this;
        this.window.delegate('click', function (e) {
            e.preventDefault();
            that.close();
        }, this.conf.close);

        // close with <ESC>
        Y.one(document).on('keydown', function (e) {
            if ( e.charCode === 27 ) {
                that.close();
            }
        });
    };

    /**
     * Default configuration of eZModalWindow
     */
    eZModalWindow.DEFAULT_CONFIG = {
        content: '.window-content',
        close: '.window-close, .window-cancel',
        title: 'h2 span',
        centered: true,
        xy: [],
        zIndex: 50,
        mask: '#overlay-mask',
        maskOpacity: 0.5
    };

    /**
     * Defines a function to call when the window is closed
     *
     * @param fn a function
     * @param context the context to use when calling the function
     */
    eZModalWindow.prototype.onClose = function (fn, context) {
        this.closeCallback = {
            fn: fn,
            context: context
        };
    };

    /**
     * Opens the window
     */
    eZModalWindow.prototype.open = function () {

        this.overlay.reset();
        if ( this.conf.xy && this.conf.xy.join ) {
            var x = this.conf.xy[0],
                y = this.conf.xy[1];
            if ( x == 'centered' ) {
                x = (Y.DOM.winWidth() - this.overlay.get('width')) / 2;
            }
            y = Y.DOM.docScrollY() + y;
            this.overlay.set('xy', [x, y]);
        }
        if ( this.mask ) {
            this.mask.show();
            var anim = new Y.Anim({
                node: this.mask,
                to: {
                    opacity: this.conf.maskOpacity
                },
                duration: 0.4
            });
            anim.run();
        }
        this.overlay.show();
        this.isOpen = true;
    };

    /**
     * Defines the content of the window
     *
     * @param content String or Node or HTMLElement
     */
    eZModalWindow.prototype.setContent = function (content) {
        this.getContentNode().setContent(content);
    };

    /**
     * Returns the Node used to show the content of the window
     *
     * @return Node
     */
    eZModalWindow.prototype.getContentNode = function () {
        return this.window.one(this.conf.content);
    };

    /**
     * Defines the title of the window
     */
    eZModalWindow.prototype.setTitle = function (title) {
        this.window.one(this.conf.title).setContent(title);
    };

    /**
     * Checks if the content of the window is empty or not
     *
     * @return boolean
     */
    eZModalWindow.prototype.empty = function () {
        return !this.getContentNode().hasChildNodes();
    };

    /**
     * Closes the window
     *
     * @param keepContent boolean
     */
    eZModalWindow.prototype.close = function (keepContent) {
        var that = this;

        this.overlay.hide();
        if ( this.mask ) {
            var anim = new Y.Anim({
                node: this.mask,
                to: {
                    opacity: 0
                },
                duration: 0.2
            });
            anim.run();
            anim.on('end', function () {
                that.mask.hide();
            });
        }
        this.isOpen = false;
        if ( !keepContent ) {
            this.setContent('');
        }

        if ( this.closeCallback.fn && this.closeCallback.fn.call ) {
            var context = this;
            if ( this.closeCallback.context ) {
                context = this.closeCallback.context;
            }
            this.closeCallback.fn.call(context);
        }
    };

    Y.namespace('eZ');

    Y.eZ.ModalWindow = eZModalWindow;

}, '1.0.0', {
    requires: [
        'node', 'overlay', 'dom-base', 'anim'
    ]
});
