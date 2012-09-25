YUI.add('ez-expandable', function (Y) {
    "use strict";

    /**
     * Provides the Y.eZ.Expandable class
     *
     * @module ez-expandable
     */

    Y.namespace('eZ');


    /**
     * Expandable widget is pretty universal. 
     * srcNode should contain header(.ez-ei-expandable-header) and content(.ez-ei-expandable-content) divs.
     *
     *
     * @class Y.eZ.Expandable
     * @extends Widget
     * @construct
     * @param {Object} config the configuration of the widget
     *   @param {Node} config.srcNode the Node where to find the HTML structure (see above)
     */
    function eZExpandable(config) {
        eZExpandable.superclass.constructor.apply(this, arguments);
    }

    eZExpandable.NAME = 'expandable';
    eZExpandable.CSS_PREFIX = "ez-ei-" + eZExpandable.NAME;

    eZExpandable.ATTRS = {
        headerClassName:{
            value:'box-header'
        },
        triggerClassName:{
            value:'-trigger'
        },
        contentClassName:{
            value:'box-bc'
        }
    };

    eZExpandable.HTML_PARSER = {
    };

    Y.extend(eZExpandable, Y.Widget, {
        initializer: function () {
            
            this.sourceNode = this.get("srcNode");
            
            this.contentNode = Y.one('#' + this.sourceNode.generateID() + ' .' + this.get("contentClassName"));
            this.headerNode = Y.one('#' + this.sourceNode.generateID() + ' .'  + this.get("headerClassName"));
            
            this.contentNode.hide();            
            this._addTriggerToHeader();
            
            this.headerNode.on('click', this._triggerClick, this);

        },

        destructor: function () {
        },

        renderUI: function () {
        },

        bindUI: function () {
        },

        syncUI: function () {
        },

        expand: function () {
            this.contentNode.show();
            this.contentNode.addClass("expanded");
            this.sourceNode.addClass("expanded");
            
        },

        hide: function () {

            this.contentNode.hide();
            this.contentNode.removeClass("expanded");
            this.sourceNode.removeClass("expanded");
        },
        
        _addTriggerToHeader: function () {
            
            this.triggerNode = Y.Node.create('<span class="' + eZExpandable.CSS_PREFIX + this.get("triggerClassName") + '" >&nbsp;&nbsp;&nbsp;<span>');
            this.headerNode.appendChild(this.triggerNode);
            this.headerNode.setStyles({
                'cursor':'pointer'
            });
            
        },

        _triggerClick: function () {
            
            if (this.contentNode.hasClass("expanded")){
                this.hide();
            }
            else {
                this.expand();
            }
            
        }
    });

    Y.eZ.Expandable = eZExpandable;

}, '0.1alpha', ['widget']);
