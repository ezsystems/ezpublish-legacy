function ContentStructureMenu( params, i18n )
{
    this.cookieName     = "contentStructureMenu";
    this.cookieValidity = 3650; // days
    this.cookie         = params.useCookie ? _getCookie( this.cookieName ) : '';
    this.open           = ( this.cookie )? this.cookie.split( '/' ): [];
    this.autoOpenPath   = params.path;


    this.updateCookie = function()
    {
        if ( !params.useCookie )
            return;
        this.cookie = this.open.join('/');
        expireDate  = new Date();
        expireDate.setTime( expireDate.getTime() + this.cookieValidity * 86400000 );
        _setCookie( this.cookieName, this.cookie, expireDate );
    };

    this.setOpen = function( nodeID )
    {
        if ( jQuery.inArray( '' + nodeID, this.open ) !== -1 )
        {
            return;
        }
        this.open[this.open.length] = nodeID;
        this.updateCookie();
    };

    this.setClosed = function( nodeID )
    {
        var openIndex = jQuery.inArray( '' + nodeID, this.open );
        if ( openIndex !== -1 )
        {
            this.open.splice( openIndex, 1 );
            this.updateCookie();
        }
    };

    this.generateEntry = function( item, lastli, rootNode )
    {
        var liclass = '';
        if ( lastli )
        {
            liclass += ' lastli';
        }
        if ( params.path && ( params.path[params.path.length-1] == item.node_id || ( !item.has_children && jQuery.inArray( item.node_id, params.path ) !== -1 ) ) )
        {
            liclass += ' currentnode';
        }
        var html = '<li id="n'+item.node_id+'"'
            + ( ( liclass )? ' class="' + liclass + '"':
                             '' )
            + '>';
        if ( item.has_children && !rootNode )
        {
            html += '<a class="openclose-open" id="a'
                + item.node_id
                + '" href="#" onclick="this.blur(); return treeMenu.load( this, '
                + item.node_id
                + ', '
                + item.modified_subnode
                +' )"><\/a>';
        }

        var languages = "[";
        var firstLanguage = true;
        for ( var j = 0; j < item.languages.length; j++ )
        {
            if ( params.languages[item.languages[j]] )
            {
                if ( !firstLanguage )
                {
                    languages += ",";
                }
                firstLanguage = false;
                languages += "{locale:'"
                    + item.languages[j].replace(/'/g,"\\'")
                    + "',name:'"
                    + params.languages[item.languages[j]].replace(/'/g,"\\'")
                    + "'}";
            }
        }
        languages += "]";

        var canCreateClasses = false;
        var classes = "[";
        if ( params.createHereMenu != 'disabled' )
        {
            if ( params.createHereMenu == 'full' )
            {
                var classList = item.class_list;

                for ( var j = 0; j < classList.length; j++ )
                {
                    if ( params.classes[classList[j]] )
                    {
                        if ( canCreateClasses )
                        {
                            classes += ",";
                        }
                        canCreateClasses = true;
                        classes += "{classID:'"
                            + classList[j]
                            + "',name:'"
                            + String(params.classes[classList[j]].name).replace(/'/g,"\\'").replace(/>/g,'&gt;').replace(/"/g,'&quot;')
                            + "'}";
                    }
                }
            }
            else
            {
                for ( j in params.classes )
                {
                    if ( canCreateClasses )
                    {
                        classes += ",";
                    }
                    canCreateClasses = true;
                    classes += "{classID:'"
                        + j
                        + "',name:'"
                        + String(params.classes[j].name).replace(/'/g,"\\'").replace(/>/g,'&gt;').replace(/"/g,'&quot;')
                        + "'}";
                }
            }
        }
        classes += "]";

        var classIdentifier = params.classes[item.class_id].identifier;
        var icon = ( params.iconsList[classIdentifier] )? params.iconsList[classIdentifier]: params.iconsList['__default__'];
        if ( params.context != 'browse' && item.node_id > 1 )
        {
            html += '<a class="nodeicon" href="#" onclick="ezpopmenu_showTopLevel( event, \'ContextMenu\', {\'%nodeID%\':'
                + item.node_id
                + ', \'%objectID%\':'
                + item.object_id
                + ', \'%languages%\':'
                + languages
                + ', \'%classList%\':'
                + classes
                + ' }, \''
                + String(item.name).replace(/'/g,"\\'").replace(/>/g,'&gt;').replace(/"/g,'&quot;')
                + '\', '
                + item.node_id
                + ', '
                + ( ( canCreateClasses )? '-1':
                                          '\'menu-create-here\'' )
                + ' ); return false"><img src="'
                + icon
                + '" alt="" title="['
                + params.classes[item.class_id].name.replace(/>/g,'&gt;').replace(/"/g, '&quot;')
                + '] ' + i18n.expand + '" width="16" height="16" /><\/a>';
        }
        else
        {
            html += '<img src="'
                + icon
                + '" alt="" width="16" height="16" />';
        }
        html += '&nbsp;<a class="image-text" href="'
            + ( ( params.action )? params.action + '/' + item.node_id : item.url )
            + '"';

        if ( params.showTips )
        {
            html += ' title="' + i18n.node_id + ': '
                + item.node_id
                + ', ' + i18n.object_id + ': '
                + item.object_id
                + ', ' + i18n.visibility + ': '
                + ( item.is_hidden ? i18n.hidden: ( item.is_invisible ? i18n.hiddenbyparent : i18n.visible ) )
                + '"';
        }

        html += '><span class="node-name-'
            + ( ( item.is_hidden )? 'hidden':
                                    ( item.is_invisible )? 'hiddenbyparent':
                                                           'normal' )
            + '">'
            + item.name
            + '<\/span>';

        if ( item.is_hidden )
        {
            html += '<span class="node-hidden"> (' + i18n.hidden + ')<\/span>';
        }
        else if ( item.is_invisible )
        {
            html += '<span class="node-hiddenbyparent"> (' + i18n.hiddenbyparent + ')<\/span>';
        }

        html += '<\/a>';
        html += '<div id="c'
             + item.node_id
             + '"><\/div>';
        html += '<\/li>';

        return html;
    };

    this.load = function( aElement, nodeID, modifiedSubnode )
    {
        var divElement = document.getElementById('c' + nodeID);

        if ( !divElement )
        {
            return false;
        }

        if ( divElement.className == 'hidden' )
        {
            divElement.className = 'loaded';
            if ( aElement )
            {
                aElement.className = 'openclose-close';
            }

            this.setOpen( nodeID );

            return false;
        }

        if ( divElement.className == 'loaded' )
        {
            divElement.className = 'hidden';
            if ( aElement )
            {
                aElement.className = 'openclose-open';
            }

            this.setClosed( nodeID );

            return false;
        }

        if ( divElement.className == 'busy' )
        {
            return false;
        }

        var url = params.contentTreeUrl + nodeID
            + "/" + modifiedSubnode
            + "/" + params.expiry
            + "/" + params.perm;

        divElement.className = 'busy';
        if ( aElement )
        {
            aElement.className = "openclose-busy";
        }

        var thisThis = this, request = jQuery.ajax({
            'url': url,
            'dataType': 'json',
            'success': function( data, textStatus )
            {
                var html = '<ul>', items = [];
                // Filter out nodes to hide
                for ( var i = 0, l = data.children_count; i < l; i++ )
                {
                    if ( jQuery.inArray( data.children[i].node_id, params.hideNodes ) === -1 )
                    {
                        items.push( data.children[i] );
                    }
                }
                // Generate html content
                for ( var i = 0, l = items.length; i < l; i++ )
                {
                    html += thisThis.generateEntry( items[i], i == l - 1, false );
                }
                html += '<\/ul>';

                divElement.innerHTML += html;
                divElement.className = 'loaded';
                if ( aElement )
                {
                    aElement.className = 'openclose-close';
                }

                thisThis.setOpen( nodeID );
                thisThis.openUnder( nodeID );

                return;
            },
            'error': function( xhr, textStatus, errorThrown )
            {
                divElement.className = 'error';
                if (aElement) aElement.className = 'openclose-error';

                function setErrorText( txt )
                {
                    if (aElement) aElement.title = txt;
                    else divElement.innerHTML = '<span></span>' + txt;
                }

                switch( xhr.status )
                {
                    case 403:
                    {
                      setErrorText( i18n.disabled );
                    } break;
                    case 404:
                    {
                      setErrorText( i18n.not_exist );
                    } break;
                    default:
                    {
                      setErrorText( i18n.internal_error );
                    }
                }
                if (aElement) aElement.onclick = function()
                {
                    return false;
                };
            }
        });

        return false;
    };

    this.openUnder = function( parentNodeID )
    {
        var divElement = document.getElementById( 'c' + parentNodeID );
        if ( !divElement )
        {
            return;
        }

        var ul = divElement.getElementsByTagName( 'ul' )[0];
        if ( !ul )
        {
            return;
        }

        var children = ul.childNodes;
        for ( var i = 0; i < children.length; i++ )
        {
            var liCandidate = children[i];
            if ( liCandidate.nodeType == 1 && liCandidate.id )
            {
                var nodeID = liCandidate.id.substr( 1 ), openIndex = jQuery.inArray( nodeID, this.autoOpenPath );
                if ( this.autoOpen && openIndex !== -1 )
                {
                    this.autoOpenPath.splice( openIndex, 1 );
                    this.setOpen( nodeID );
                }
                if ( jQuery.inArray( nodeID, this.open ) !== -1 )
                {
                    var aElement = document.getElementById( 'a' + nodeID );
                    if ( aElement )
                    {
                        aElement.onclick();
                    }
                }
            }
        }
    };

    this.collapse = function( parentNodeID )
    {
        var divElement = document.getElementById( 'c' + parentNodeID );
        if ( !divElement )
        {
            return;
        }

        var aElements = divElement.getElementsByTagName( 'a' );
        for ( var index in aElements )
        {
            var aElement = aElements[index];
            if ( aElement.className == 'openclose-close' )
            {
                var nodeID        = aElement.id.substr( 1 );
                var subdivElement = document.getElementById( 'c' + nodeID );
                if ( subdivElement )
                {
                    subdivElement.className = 'hidden';
                }
                aElement.className = 'openclose-open';
                this.setClosed( nodeID );
            }
        }

        var aElement = document.getElementById( 'a' + parentNodeID );
        if ( aElement )
        {
            divElement.className = 'hidden';
            aElement.className   = 'openclose-open';
            this.setClosed( parentNodeID );
        }
    };

    // Internal cookie functions
    function _setCookie( name, value, expires, path )
    {
        document.cookie = name + '=' + escape(value) + ( expires ? '; expires=' + expires.toUTCString(): '' ) + '; path='+ (path ? path : '/');
    }

    function _getCookie( name )
    {
        var n = name + '=', c = document.cookie, start = c.indexOf( n ), end = c.indexOf( ";", start );
        if ( start !== -1 )
        {
            return unescape( c.substring( start + n.length, ( end === -1 ? c.length : end ) ) );
        }
        return null;
    }

    function _delCookie( name )
    {
        _setCookie( name, '', ( new Date() - 86400000 ) );
    }
}