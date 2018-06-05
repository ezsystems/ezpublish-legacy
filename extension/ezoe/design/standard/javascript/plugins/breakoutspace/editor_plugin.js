/**
 * Break Out Space TinyMCE plugin. When you press CTRL+Enter in a custom tag you will get an empty paragraph outside the tag
 *
 * @author Peter Keung
 * @copyright Copyright 2014, Mugo Web
 */

( function()
{
    tinymce.create('tinymce.plugins.breakoutspace',
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
            ed.onKeyDown.add( function( ed, e )
            {
                // Capture CTRL+Enter
                if( ( ( e.keyCode == 13 ) || ( e.keyCode == 10 ) ) && ( e.ctrlKey == true ) )
                {
                    var dom = ed.dom;
                    
                    var parents = dom.getParents( ed.selection.getNode() );
                    for( var i=0; i < parents.length; i++ )
                    {
                        currentNode = parents[i];
                        // Insert empty paragraph at the end of the parent of the closest custom tag
                        if( currentNode.nodeName == 'DIV' && currentNode.getAttribute( 'type' ) == 'custom' )
                        {
                            // dom.insertAfter doesn't work reliably
                            var uniqueID = dom.uniqueId();
                            jQuery( '<p id="' + uniqueID + '"><br /></p>' ).insertAfter( currentNode );
                            
                            // Move to the new node
                            var newParagraph = dom.select( 'p#' + uniqueID )[0];
                            ed.selection.setCursorLocation( newParagraph );

                            // Don't create an extra paragraph
                            e.preventDefault();
                            break;
                        }
                    }
                }
            });
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'breakoutspace', tinymce.plugins.breakoutspace );
})();