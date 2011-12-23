YUI(YUI3_config).add('ezcollapsiblemenu', function (Y) {

    Y.namespace('eZ');

    function doTransition(elements, type) {
        var el, transitionConf;
        for(var i=0; i!=elements.length; i++) {
            el = elements[i];
            transitionConf = {duration: el.duration};
            for(var k in el[type]) {
                transitionConf[k] = el[type][k];
            }
            Y.one(el.selector).transition(transitionConf);
        }
    }

    /**
     * Constructor of the eZCollapsibleMenu component
     *
     * @param conf Configuration object containing the following items:
     *        - link: a selector to the element that (un)collapses the menu
     *        - content: array, contents of the link (see above) depending on uncollapsed/collapsed state
     *        - collapsed: 0/1, the state of the menu
     *        - callback (not required): a callback function to call when the menu is (un)collapsed. 'this' in this function is the instance of eZCollapsibleMenu
     *        - elements: array of objects describing the transition between collapsed and uncollapsed state. each contains:
     *          - selector: a selector to the element to change
     *          - duration: duration in seconds of the transition
     *          - fullStyle: object containing the styles to apply on the element selected by 'selector' when the menu is uncollapsed
     *          - collapsedStyle: same as above for collapsed state.
     */
    function eZCollapsibleMenu(conf) {
        var that = this;
        this.conf = conf;

        Y.one(this.conf.link).on('click', function (e) {
            e.preventDefault();
            if ( that.conf.collapsed == 1 ) {
                doTransition(that.conf.elements, 'fullStyle');
                that.conf.collapsed = 0;
            } else {
                doTransition(that.conf.elements, 'collapsedStyle');
                that.conf.collapsed = 1;
            }
            Y.one(that.conf.link).setContent(that.conf.content[that.conf.collapsed]);
            if ( that.conf.callback ) {
                that.conf.callback.call(that);
            }
        });
    }

    Y.eZ.CollapsibleMenu = eZCollapsibleMenu;

}, '1.0.0', {
    requires: [
        'transition', 'event'
    ]
});
