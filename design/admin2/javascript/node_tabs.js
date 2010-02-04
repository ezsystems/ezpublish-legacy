// jquery code to a provide tabbing
jQuery(function( $ )
{
    var NodeTab = {
        timeout : null,
    	click : function( e )
        {
    	    NodeTab.open( e.target.parentNode.id, true );
        	return false;
        },
    	open : function( id, save )
        {
        	var li = $( '#' + id );
        	if ( li.size() && !li.hasClass('selected') )
        	{
                if ( NodeTab.timeout !== null )
                {
                    clearTimeout( NodeTab.timeout );
                    NodeTab.timeout = null;
                }
                var block = $( li[0].parentNode.parentNode.parentNode );
        		block.find("ul.tabs li.selected").removeClass('selected');
        		block.find("div.tab-content.selected").addClass('hide').removeClass('selected');
        		li.addClass('selected');
        		$( '#' + id + '-content' ).addClass('selected').removeClass('hide');

        		if ( save ) NodeTab.timeout = setTimeout( function(){ NodeTab.save( id ) }, 400 );
        	}
        },
    	save : function( id )
    	{
            if ( id.indexOf( 'node-tab-' ) === 0 )
            {
                expireDate  = new Date();
                expireDate.setTime( expireDate.getTime() + 365 * 86400000 );
        	    NodeTab.setCookie( 'adminNavigationTab', id.split('tab-')[1], expireDate );
            }
        },
        // cookie functions
        setCookie: function( name, value, expires, path )
        {
            document.cookie = name + '=' + escape(value) + ( expires ? '; expires=' + expires.toUTCString(): '' ) + '; path='+ (path ? path : '/');
        },
        getCookie: function( name )
        {
            var n = name + '=', c = document.cookie, start = c.indexOf( n ), end = c.indexOf( ";", start );
            if ( start !== -1 )
            {
                return unescape( c.substring( start + n.length, ( end === -1 ? c.length : end ) ) );
            }
            return null;
        }
    };
	$('div.tab-block ul.tabs li a').click( NodeTab.click );
	var openTab = NodeTab.getCookie( 'adminNavigationTab' );
	if ( openTab )
	{
		NodeTab.open( 'node-tab-' + openTab );
	}
});
