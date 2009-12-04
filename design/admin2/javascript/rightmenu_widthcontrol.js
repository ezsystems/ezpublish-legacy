jQuery(function( $ )
{

    var link = $('#rightmenu-showhide').removeClass( 'hide' ), timeout = null;
    link.html( link.width() <= 10 ? '&lt;' : '&gt;' );
    link.click(function()
    {
        if ( timeout !== null )
        {
        	clearTimeout( timeout );
        	timeout = null;
        }
    	var link = $( this ), linkbox = $( '#rightmenu' ), hidden = linkbox.width() < 20;
        if ( hidden )
        {
        	$('#maincontent').css( 'marginRight', '13em' );
        	linkbox.animate({
	            width: '13em'
	        }, 650, 'swing', function(){
	        	timeout = setTimeout( saveRightMenuStatus, 500 );
	        } );
        }
        else
        {
	        linkbox.animate({
	            width: '1em'
	        }, 650, 'swing', function(){
	        	$('#maincontent').css( 'marginRight', '1em' );
	        	timeout = setTimeout( saveRightMenuStatus, 500 );
	        } );
        }
        link.html( hidden ? '&gt;' : '&lt;' );
    });
    function saveRightMenuStatus()
    {
		var value  = $( '#rightmenu' ).width() < 20 ? '1' : '';
		var url = $.ez.url.replace( 'ezjscore/', 'user/preferences/' ) + 'set_and_exit/admin_right_menu_hide/' + value;
		$.post( url, {}, function(){} );
    }
});