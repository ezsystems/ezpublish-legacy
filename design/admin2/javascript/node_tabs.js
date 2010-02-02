// jquery code to a provide tabbing
jQuery(function( $ )
{
    var NodeTab = {
        timeout : null,
    	click : function( e )
        {
        	var li = $( e.target.parentNode ), block = $( e.target.parentNode.parentNode.parentNode );
        	if ( !li.hasClass('selected') )
        	{
                if ( NodeTab.timeout !== null )
                {
                    clearTimeout( NodeTab.timeout );
                    NodeTab.timeout = null;
                }

        		block.find("ul.tabs li.selected").removeClass('selected');
        		block.find("div.tab-content.selected").addClass('hide').removeClass('selected');
        		li.addClass('selected');
        		$( '#' + li.attr('id') + '-content' ).addClass('selected').removeClass('hide');

        		// Sticky Tabs! TODO: Should use cookie instead to avoid generating new view cache hash maybe..
        		NodeTab.timeout = setTimeout( function(){ NodeTab.save( li.attr('id') ) }, 500 );
        	}
        	return false;
        },
    	save : function( id )
    	{
            if ( id.indexOf( 'node-tab-' ) === 0 )
            {
        	    var url = $.ez.url.replace( 'ezjscore/', 'user/preferences/' ) + 'set_and_exit/admin_navigation_content/' + id.split('tab-')[1];
        	    $.post( url, {}, function(){} );
            }
        }
    };
	$('div.tab-block ul.tabs li a').click( NodeTab.click );
});
