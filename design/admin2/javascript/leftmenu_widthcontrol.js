// jquery code to a allow changing width  on left menu by dragging
(function( $ )
{
    var leftMenuDrag = {
            elements : false,
            timeout : null,
            down: function( e )
            {
    	        leftMenuDrag.elements = [ $( '#leftmenu' ), $( '#maincontent' ) ];
                if ( leftMenuDrag.timeout !== null )
                {
                    clearTimeout( leftMenuDrag.timeout );
                    leftMenuDrag.timeout = null;
                }
            },
            up: function( e )
            {
                if ( leftMenuDrag.elements )
                {
                    leftMenuDrag.elements = false;
                    leftMenuDrag.timeout = setTimeout( leftMenuDrag.save, 500 );
                }
            },
            on: function( e )
            {
                if ( leftMenuDrag.elements  )
                {
                    var els = leftMenuDrag.elements, offset = els[0].offset().left, pos = e.pageX, size = pos - offset;
                    if ( size < 20 ) size = 20;
                    els[0].css( 'width', ( size + 3 )  + 'px' );
                    els[1].css( 'marginLeft', ( size ) + 'px' );
                }
            },
            save: function()
            {
                var px  = $( '#leftmenu' ).width();
                var url = $.ez.url.replace( 'ezjscore/', 'user/preferences/' ) + 'set_and_exit/admin_left_menu_size/' + leftMenuDrag.em( px ) + 'em';
                $.post( url, {}, function(){} );
            },
            em: function( px )
            {
                var test = jQuery('<div style="display: none; font-size: 1em; margin: 0; padding:0; height: auto; line-height: 1; border:0;">&nbsp;</div>').appendTo('#columns'), scale = test.height();
                test.remove();
                return (px / scale).toFixed(8);
            }
    };
    var wl = $('#widthcontrol-links'), wh = $('#widthcontrol-handler'); 
    if ( wl && wh )
    {
        wl.addClass( 'hide' );
        wh.removeClass( 'hide' ) ;
        wh.bind( 'mousedown', leftMenuDrag.down );
        $( document ).bind('mouseup click', leftMenuDrag.up );
        $( document ).bind('mousemove', leftMenuDrag.on );
        $('#leftmenu').addClass( 'widthcontroled' );
    }
})( jQuery );