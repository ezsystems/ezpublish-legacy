/**
 * Constructor of eZModalWindow class
 *
 * @param conf Configuration object containing the following keys
 *              - 'window'   a selector used to get the window container
 *              - 'content'  a relative selector to get the container of the window's content
 *              - 'title'    a relative selector to get the element containing the title text
 *              - 'mask'     [optional] a selector to get the mask element
 *              - 'close'    [optional] a relative selector of elements that will close the window on click
 *              - 'width'    [optional] the width of the window in pixel
 *              - 'centered' [optional] boolean
 *              - 'xy'       [optional] array to define the position of the window in pixel, the first element can also be 'centered'
 *              - 'zIndex'   [optional] the z-index CSS value of the window
 * @param Y YUI global object
 */
function eZModalWindow(conf, Y) {
    this.Y = Y;
    this.conf = conf;

    this.window = Y.one(this.conf.window);
    this.isOpen = false;
    this.closeCallback = {};

    this.mask = null;
    if ( !Y.UA.ie || Y.UA.ie > 6 ) {
        // the mask is ugly in IE6
        this.mask = Y.one(conf.mask);
    }
    this.overlay = new Y.Overlay({
        srcNode: conf.window,
        width: conf.width,
        centered: conf.centered,
        visible: false,
        zIndex: conf.zIndex
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
}

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
}

/**
 * Opens the window
 */
eZModalWindow.prototype.open = function () {
    var Y = this.Y;

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
    }
    this.overlay.show();
    this.isOpen = true;
}

/**
 * Defines the content of the window
 *
 * @param content String or Node or HTMLElement
 */
eZModalWindow.prototype.setContent = function (content) {
    this.getContentNode().setContent(content);
}

/**
 * Returns the Node used to show the content of the window
 *
 * @return Node
 */
eZModalWindow.prototype.getContentNode = function () {
    return this.window.one(this.conf.content);
}

/**
 * Defines the title of the window
 */
eZModalWindow.prototype.setTitle = function (title) {
    this.window.one(this.conf.title).setContent(title);
}

/**
 * Checks if the content of the window is empty or not
 *
 * @return boolean
 */
eZModalWindow.prototype.empty = function () {
    return !this.getContentNode().hasChildNodes();
}

/**
 * Closes the window
 *
 * @param keepContent boolean
 */
eZModalWindow.prototype.close = function (keepContent) {
    this.overlay.hide();
    if ( this.mask ) {
        this.mask.hide();
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
}
