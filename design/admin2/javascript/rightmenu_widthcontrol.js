var rightMenuWidthCotroll = function()
{

    var link = jQuery('#rightmenu-showhide'), rightmenu = jQuery( '#rightmenu' ), timeout = null;

    link.attr('href', 'JavaScript:void(0);').html( rightmenu.width() <= 22 ? '&laquo;' : '&raquo;' ).click(function()
    {
        if ( timeout !== null )
        {
            clearTimeout( timeout );
            timeout = null;
        }
        var link = jQuery( this ), rightmenu = jQuery( '#rightmenu' ), hidden = rightmenu.width() < 22;
        if ( hidden )
        {
            jQuery('#maincolumn').css( 'marginRight', '180px' );
            rightmenu.animate({
                width: '181px'
            }, 650, 'swing', function(){
                timeout = setTimeout( saveRightMenuStatus, 500 );
            } );
        }
        else
        {
            rightmenu.animate({
                width: '18px'
            }, 650, 'swing', function(){
                jQuery('#maincolumn').css( 'marginRight', '17px' );
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