YUI(YUI3_config).add('ezcollapsiblemenu', function (Y) {

    Y.namespace('eZ');

    function doTransition(elements, type, endCallback) {
        var el, transitionConf, e;
        for(var i=0; i!=elements.length; i++) {
            el = elements[i];
            e = Y.one(el.selector);
            if ( !e )
                continue;
            transitionConf = {duration: el.duration};
            for(var k in el[type]) {
                if ( el[type][k].call ) {
                    transitionConf[k] = el[type][k].call();
                } else {
                    transitionConf[k] = el[type][k];
                }
            }
            if ( i == 0 && endCallback ) {
                e.transition(transitionConf, endCallback);
            } else {
                e.transition(transitionConf);
            }
        }
    }

    /**
     * Constructor of the eZCollapsibleMenu component
     *
     * @param conf Configuration object containing the following items:
     *        - link: a selector to the element that (un)collapses the menu
     *        - content: array|false, contents of the link (see above) depending on uncollapsed/collapsed state, if false the link is not updated
     *        - collapsed: 0/1, the state of the menu
     *        - beforecollapse (not required): a callback function to call before collapsing
     *        - beforeuncollapse (not required): a callback function to call before uncollapsing
     *        - aftercollapse (not required): a callback function to call after collapsing
     *        - afteruncollapse (not required): a callback function to call after uncollapsing
     *        - callback (not required): a callback function to call when the menu is (un)collapsed.
     *                                   'this' in this function is the instance of eZCollapsibleMenu
     *        - elements: array of objects describing the transition between collapsed and uncollapsed state. each contains:
     *          - selector: a selector to the element to change
     *          - duration: duration in seconds of the transition
     *          - fullStyle: object containing the styles to apply on the element selected by 'selector' when the menu is uncollapsed,
     *                       each style can also be a function.
     *          - collapsedStyle: same as above for collapsed state.
     */
    function eZCollapsibleMenu(conf) {
        var that = this;
        this.conf = conf;

        Y.all(this.conf.link).on('click', function (e) {
            e.preventDefault();
            if ( that.conf.collapsed == 1 ) {
                that.uncollapse();
            } else {
                that.collapse();
            }
        });
    }

    /**
     * Executes the transitions to collapse the area
     */
    eZCollapsibleMenu.prototype.collapse = function () {
        if ( this.conf.beforecollapse ) {
            this.conf.beforecollapse.call(this);
        }
        doTransition(this.conf.elements, 'collapsedStyle', this.conf.aftercollapse);
        this.conf.collapsed = 1;
        this._setLinkContent();
        if ( this.conf.callback ) {
            this.conf.callback.call(this);
        }
    }

    /**
     * Executes the transition to uncollapse the area
     */
    eZCollapsibleMenu.prototype.uncollapse = function () {
        if ( this.conf.beforeuncollapse ) {
            this.conf.beforeuncollapse.call(this);
        }
        doTransition(this.conf.elements, 'fullStyle', this.conf.afteruncollapse);
        this.conf.collapsed = 0;
        this._setLinkContent();
        if ( this.conf.callback ) {
            this.conf.callback.call(this);
        }
    }

    /**
     * Sets the link content based on the configuration
     * @private
     */
    eZCollapsibleMenu.prototype._setLinkContent = function () {
        if ( Y.Lang.isArray(this.conf.content) ) {
            Y.one(this.conf.link).setContent(
                this.conf.content[this.conf.collapsed]
            );
        }
    }

    Y.eZ.CollapsibleMenu = eZCollapsibleMenu;

}, '1.0.0', {
    requires: [
        'transition', 'event'
    ]
});
