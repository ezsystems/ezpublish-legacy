/*YUI( YUI3_config ).use( 'node', 'io-ez', function( Y )
{

    Y.on( "contentready", function( e )
    {
        //Y.io.ez( 'ezjsc::time', {data: 'postData=hi!', on: {success: function(id,r){ alert(r.responseJSON.content) }}} );
        Y.one('#content-tree').addClass( 'widthcontroled' )
        var widthcontrol = Y.one( '#content-tree div.widthcontrol' ).addClass( 'js-widthcontroled' );
        Y.on('mousedown', contentTreeDrag.down, widthcontrol );
        //widthcontrol.on('mouseup', contentTreeDrag.up );
    }, '#content-tree' );

});*/
// jquery code to a allow changing width  on left menu by dragging
jQuery(function( $ )
{
    var contentTreeDrag = {
    		elements : false,
    		timeout : null,
    	    down: function( e )
    	    {
	            contentTreeDrag.elements = [ $( '#leftmenu' ), $( '#maincontent' ) ];
	            if ( contentTreeDrag.timeout !== null )
	            {
	                clearTimeout( contentTreeDrag.timeout );
	            	contentTreeDrag.timeout = null;
	            }
	        },
	        up: function( e )
	        {
	        	if ( contentTreeDrag.elements )
	        	{
	        		contentTreeDrag.elements = false;
	        		contentTreeDrag.timeout = setTimeout( contentTreeDrag.save, 500 );
	        	}
		    },
		    on: function( e )
		    {
			    if ( contentTreeDrag.elements  )
			    {
			    	var els = contentTreeDrag.elements, offset = els[0].offset().left, pos = e.pageX;
			    	els[0].css( 'width', ( pos + 3 ) - offset + 'px' );
			    	els[1].css( 'marginLeft', ( pos - offset ) + 'px' );
			    }
			},
			save: function()
			{
				var px  = $( '#leftmenu' ).width();
				var url = $.ez.url.replace( 'ezjscore/', 'user/preferences/' ) + 'set_and_exit/admin_left_menu_size/' + contentTreeDrag.em( px ) + 'em';
				$.post( url, {}, function(){} );
			},
			em: function( px )
			{
				var test = jQuery('<div style="display: none; font-size: 1em; margin: 0; padding:0; height: auto; line-height: 1; border:0;">&nbsp;</div>').appendTo('#columns'), scale = test.height();
				test.remove();
				return (px / scale).toFixed(8);
			}
    };
    $('#content-tree').addClass( 'widthcontroled' );
    var wc = $( '#content-tree div.widthcontrol' ).addClass( 'js-widthcontroled' );
    wc.bind( 'mousedown', contentTreeDrag.down );
    $( document ).bind('mouseup click', contentTreeDrag.up );
    $( document ).bind('mousemove', contentTreeDrag.on );
});