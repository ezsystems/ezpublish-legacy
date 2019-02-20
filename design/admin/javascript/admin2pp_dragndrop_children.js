/**
 * $Id: admin2pp_dragndrop_children.js 79 2010-03-14 11:19:58Z dpobel $
 * $HeadURL: http://svn.projects.ez.no/admin2pp/trunk/extension/admin2pp/design/admin2/javascript/admin2pp_dragndrop_children.js $
 *
 */

function admin2ppDragNDropChildren() {
    this.sortOrder = 1;
}

admin2ppDragNDropChildren.PRIORITY_OFFSET = 2;
admin2ppDragNDropChildren.TABLE_ID = 'content-sub-items-list > table';

admin2ppDragNDropChildren.prototype = {
    init:function() {
        var instance = this;
        this.sortOrder = subItemsTable.get('sortedBy').dir === 'yui-dt-asc' ? 1 : -1;
        jQuery('#' + admin2ppDragNDropChildren.TABLE_ID + ' .yui-dt-data').sortable( {
                placeholder: {
                    /**
                     * Function to create the placeholder
                     */
                    element: function(currentItem) {
                        var tr = $('<tr class="yui-dt-even"><td colspan="' + currentItem.find('> :visible').length + '"><div class="yui-dt-liner">&nbsp;</div></td></tr>');

                        return tr;
                    },
                    update: function(container, p) {
                        return;
                    }
                },
                axis:'y',
                opacity:0.8,
                items:'> tr',
                cursor:'move',
                stop:function( evt, ui ) {
                    instance.fixBackgrounds(); 
                    instance.setPriorities();
                }
            } )
            .css( 'cursor', 'move' );
        jQuery('#' + admin2ppDragNDropChildren.TABLE_ID + ' .yui-dt-data').disableSelection();
     },

    setPriorities:function() {
        var inputs = jQuery( '#' + admin2ppDragNDropChildren.TABLE_ID + ' .yui-dt0-col-priority > .yui-dt-liner' );
        // Fetch the starting point
        var start = +inputs.first().text();
        var increments = admin2ppDragNDropChildren.PRIORITY_OFFSET * this.sortOrder;
        var instance = this;
        inputs.each(function() {
            jQuery( this ).text( start ); 
            var recordId = jQuery( this ).closest('tr').attr('id');
            var record = subItemsTable.getRecordSet().getRecord(recordId);
            instance.updatePriority(record, start);
            start = start + increments;
        });
    },

    updatePriority: function(record, priority) {
        var sortedBy = subItemsTable.get('sortedBy'),
            paginator = subItemsTable.get('paginator')
        ;
        record.setData('priority', priority);

        jQuery.ez('ezjscnode::updatepriority', {
            ContentNodeID: record.getData('parent_node_id'),
            ContentObjectID: record.getData('contentobject_id'),
            PriorityID: [record.getData('node_id')],
            Priority: [priority]
        }, onSuccess);

        var onSuccess = function(data) {
            record.setData('priority', priority);
        }
    },

    fixBackgrounds:function() {
        var cssClass = 'yui-dt-odd';
        jQuery('#' + admin2ppDragNDropChildren.TABLE_ID + ' tbody tr').each(function() {
            var $this = jQuery( this );
            $this.removeClass('yui-dt-odd yui-dt-even');
            $this.addClass( cssClass );
            if ( cssClass == 'yui-dt-odd' ) {
                cssClass = 'yui-dt-even';
            } else {
                cssClass = 'yui-dt-odd';
            }
        });
    }

};
