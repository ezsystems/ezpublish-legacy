var rightMenuWidthControl = function()
{

    var link = jQuery('#rightmenu-showhide'), rightmenu = jQuery('#rightmenu'), timeout = null;

    link.attr('href', 'JavaScript:void(0);').html( rightmenu.width() <= 22 ? '&laquo;' : '&raquo;' ).click(function()
    {
        if ( timeout !== null )
        {
            clearTimeout( timeout );
            timeout = null;
        }
        
        var link = jQuery( this ), rightmenu = jQuery('#rightmenu'), hidden = rightmenu.width() < 22;
        var maincolumn = jQuery('#maincolumn'); 

        if ( hidden )
        {
        	maincolumn.animate({
                marginRight: '180px'
            }, 650, 'swing');

            rightmenu.animate({
                width: '181px'
            }, 650, 'swing', function(){
                timeout = setTimeout( saveRightMenuStatus, 500 );
            } );
        }
        else
        {
        	maincolumn.animate({
                marginRight: '17px'
            }, 650, 'swing');

        	rightmenu.animate({
                width: '18px'
            }, 650, 'swing', function(){
                timeout = setTimeout( saveRightMenuStatus, 500 );
            } );
        }
        link.html( hidden ? '&raquo;' : '&laquo;' );
    });
    
    function saveRightMenuStatus()
    {
        var show  = jQuery( '#rightmenu' ).width() < 22 ? '' : '1';
        jQuery.post( jQuery.ez.url.replace( 'ezjscore/', 'user/preferences/set_and_exit/admin_right_menu_show/' ) + show );
    }
};