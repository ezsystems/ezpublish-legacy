/**
 * Custom Tags Controls TinyMCE plugin. When you focus a custom tag there will appear some extra controls
 *
 * @author Peter Keung
 * @copyright Copyright 2014, Mugo Web
 */

( function()
{
    tinymce.create('tinymce.plugins.custom_tags_controls',
    {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init: function( ed, url )
        {
            var t = this;
            ed.onKeyDown.add( function( ed, e )
            {
                if( e.keyCode != 8 )
                {
                    t.manageControls( ed );
                }
                else
                {
                    elem = jQuery( ed.selection.getNode() ).closest('div.ezoeItemCustomTag,div.ezoeItemContentTypeObjects');
                    if( elem.length && elem.find('.custom_tags_controls').length != 0 && elem.text() == '' )
                    {
                        jQuery('<p><br></p>').insertBefore(elem);
                        elem.remove();
                    }
                }
            });
            ed.onClick.add( function( ed, e )
            {
                
                t.manageControls( ed );
            });
            ed.onRemove.add( function( ed, e )
            {
                t.manageControls( ed );
            });
        },
        manageControls: function( ed )
        {
            elem = jQuery( ed.selection.getNode() ).closest('div.ezoeItemCustomTag,div.ezoeItemContentTypeObjects');
            if( elem.length && elem.find('.custom_tags_controls').length == 0 )
            {
                // first remove all existing controls
                this.removeControls( ed );
                this.addControls( elem );
            }
            else if( elem.length == 0 )
            {
                this.removeControls( ed );
            }
        },
        addControls: function( elem )
        {
            elem.append( '<span class="custom_tags_controls up"></span><span class="custom_tags_controls edit"></span><span class="custom_tags_controls down"></span>' );
            elem.find('.custom_tags_controls.up').on('click', function(event){
                event.preventDefault();
                event.stopPropagation();
                parent = jQuery(this).closest('div.ezoeItemCustomTag,div.ezoeItemContentTypeObjects');
                if( parent.prev().length )
                {
                    parent.prev().insertAfter(parent);
                }
            });
            elem.find('.custom_tags_controls.down').on('click', function(event){
                event.preventDefault();
                event.stopPropagation();
                parent = jQuery(this).closest('div.ezoeItemCustomTag,div.ezoeItemContentTypeObjects');
                if( parent.next().length )
                {
                    parent.next().insertBefore(parent);
                    if( parent.next().length == 0 )
                    {
                        jQuery('<p><br></p>').insertAfter(parent);
                    }
                }
            });
            elem.find('.custom_tags_controls.edit').on('click', function(event){
                event.preventDefault();
                event.stopPropagation();
                var ed = tinyMCE.activeEditor;
                parent = jQuery(this).closest('div.ezoeItemCustomTag,div.ezoeItemContentTypeObjects');
                var currentDomElement = jQuery( ed.selection.getNode() ).closest('div.ezoeItemCustomTag,div.ezoeItemContentTypeObjects').get(0);
                var x = ed.theme.__getTagCommand( currentDomElement );
                if (x) ed.execCommand( x.cmd, currentDomElement || false, x.val );
            });
        },
        removeControls: function( ed )
        {
            jQuery( ed.selection.getNode() ).closest( 'body' ).find( '.custom_tags_controls' ).remove();
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'custom_tags_controls', tinymce.plugins.custom_tags_controls );
})();