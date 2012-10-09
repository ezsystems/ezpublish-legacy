// jquery code to a provide tabbing
jQuery(function( $ )
{
    var NodeTab = {

        timeout : null,

        // click on a tab event
        click : function( e )
        {
            NodeTab.open( e.target.parentNode.id, true );

            // enable tabs if disabled
            var link = document.getElementById('maincontent-show');
            if ( link )
            {
                NodeTab.toggle( $('div.tab-block ul.tabs'), $('div.tab-block div.tabs-content'), link );
            }

            return false;
        },

        // toggle tabs ( enable / disable state )
        toggleClick : function( e )
        {
            NodeTab.toggle( $('div.tab-block ul.tabs'), $('div.tab-block div.tabs-content'), e.target );
            return false;
        },

        // toggle tabs ( enable / disable state )
        toggle : function( ul, div, link )
        {
            if ( ul.hasClass('disabled') )
            {
                ul.removeClass('disabled');
                div.removeClass('disabled');
                NodeTab.saveTabState( 1 );
                link.innerHTML = '-';
                link.id = 'maincontent-hide';
            }
            else
            {
                ul.addClass('disabled');
                div.addClass('disabled');
                NodeTab.saveTabState( 0 );
                link.innerHTML = '+';
                link.id = 'maincontent-show';
            }
            return false;
        },

        // open a tab & optionally save state
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

                if ( save ) NodeTab.timeout = setTimeout( function(){ NodeTab.saveOpenTab( id ) }, 400 );
            }
        },

        // save tab state (using ajax)
        saveTabState : function( intBool )
        {
            var url = $.ez.url.replace( 'ezjscore/', 'user/preferences/set_and_exit/admin_navigation_content/' ) + intBool;
            var _token = '', _tokenNode = document.getElementById('ezxform_token_js');
            if ( _tokenNode ) _token = 'ezxform_token=' + _tokenNode.getAttribute('title');
            $.post( url, _token, function(){} );
        },

        // save open tab (using cookie)
        saveOpenTab : function( id )
        {
            if ( id.indexOf( 'node-tab-' ) === 0 )
            {
                expireDate  = new Date();
                expireDate.setTime( expireDate.getTime() + 365 * 86400000 );
                NodeTab.setCookie( 'adminNavigationTab', id.split('tab-')[1], expireDate );
            }
        },

        // set cookie value
        setCookie: function( name, value, expires, path )
        {
            document.cookie = name + '=' + escape(value) + ( expires ? '; expires=' + expires.toUTCString(): '' ) + '; path='+ (path ? path : '/');
        },

        // get cookie value
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

    $('#maincontent-hide').click( NodeTab.toggleClick );

    var openTab = NodeTab.getCookie( 'adminNavigationTab' );
    if ( openTab && $('div.tab-block ul.tabs.tabs-by-cookie #node-tab-' + openTab).size() )
    {
        NodeTab.open( 'node-tab-' + openTab );
    }
});
