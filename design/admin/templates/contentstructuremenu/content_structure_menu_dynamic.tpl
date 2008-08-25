{def $root_node_id=ezini('TreeMenu','RootNodeID','contentstructuremenu.ini')}
{if is_set( $custom_root_node_id )}
    {set $root_node_id=$custom_root_node_id}
{/if}
{def $root_node=fetch('content','node',hash('node_id',$root_node_id))
     $user_class_group_id=ezini('ClassGroupIDs', 'Users', 'content.ini')
     $setup_class_group_id=ezini('ClassGroupIDs', 'Setup', 'content.ini')
     $user_root_node_id=ezini('NodeSettings', 'UserRootNode', 'content.ini')
     $filter_type=cond($root_node.path_array|contains($user_root_node_id), 'include', 'exclude')
     $filter_groups=cond($root_node.path_array|contains($user_root_node_id), array( $user_class_group_id ), array($user_class_group_id, $setup_class_group_id))
    }
<script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibcookiesupport.js"|ezdesign}></script>
{if ezini('TreeMenu','PreloadClassIcons','contentstructuremenu.ini')|eq('enabled')}
    <script language="JavaScript" type="text/javascript" src={"javascript/lib/ezjslibimagepreloader.js"|ezdesign}></script>
{/if}

{def $click_action=ezini('TreeMenu','ItemClickAction','contentstructuremenu.ini')}
{if and( is_set( $csm_menu_item_click_action ), $click_action|not )}
    {set $click_action=$csm_menu_item_click_action}
{/if}

{if $click_action}
    {set $click_action=$click_action|ezurl(no)}
{/if}

<script type="text/javascript" src={"javascript/yui/2.5.2/build/utilities/utilities.js"|ezdesign}></script>
<script type="text/javascript" src={"javascript/yui/2.5.2/build/json/json-min.js"|ezdesign}></script>
{literal}
<script type="text/javascript">
<!--

if( !Array.prototype.inArray )
{
    Array.prototype.inArray = function( value )
    {
        for ( var i = 0; i < this.length; i++ )
        {
            if ( this[i] == value )
            {
                return true;
            }
        }
        return false;
    }
}

Array.prototype.removeFirst = function( value )
{
    for ( var i = 0; i < this.length; i++ )
    {
        if ( this[i] == value )
        {
            this.splice( i, 1 );
            return true;
        }
    }

    return false;
}

function ContentStructureMenu()
{
    this.cookieName = "contentStructureMenu";
    this.cookieValidity = 3650; // days
    this.cookie = ezjslib_getCookie( this.cookieName );
    this.open = ( this.cookie )? this.cookie.split( '/' ): [];
{/literal}

    this.action = "{$click_action}";
    this.context = "{$ui_context}";
    this.expiry = "{fetch('content','content_tree_menu_expiry')}";

{cache-block keys=array( $filter_type ) expiry="0" ignore_content_expiry}
    this.languages = {*
        *}{ldelim}{*
            *}{foreach fetch('content','translation_list') as $language}{*
                *}"{$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}"{*
                *}{delimiter},{/delimiter}{*
            *}{/foreach}{*
        *}{rdelim};
    this.classes = {*
        *}{ldelim}{*
            *}{foreach fetch('class','list_by_groups',hash('group_filter',$filter_groups,'group_filter_type',$filter_type)) as $class}{*
                *}"{$class.id}":{ldelim}name:"{$class.name|wash(javascript)}",identifier:"{$class.identifier|wash(javascript)}"{rdelim}{*
                *}{delimiter},{/delimiter}{*
            *}{/foreach}{*
        *}{rdelim};

{def $iconInfo = icon_info('class')
     $classIconsSize = ezini('TreeMenu','ClassIconsSize','contentstructuremenu.ini')}

    this.iconsList = new Array();
    var wwwDirPrefix = "{ezsys('wwwdir')}/{$iconInfo.theme_path}/{$iconInfo.size_path_list[$classIconsSize]}/";
    {foreach $iconInfo.icons as $class => $icon}{*
        *}this.iconsList['{$class}'] = wwwDirPrefix + "{$icon}";
    {/foreach}
    this.iconsList['__default__'] = wwwDirPrefix + "{$iconInfo.default}";
    {if ezini('TreeMenu','PreloadClassIcons','contentstructuremenu.ini')|eq('enabled')}
    ezjslib_preloadImageList( this.iconsList );
    {/if}

    this.showTips = {if ezini('TreeMenu','ToolTips','contentstructuremenu.ini')|eq('enabled')}true{else}false{/if};
    this.createHereMenu = "{ezini('TreeMenu','CreateHereMenu','contentstructuremenu.ini')}";
    this.autoOpen = {if ezini('TreeMenu','AutoopenCurrentNode','contentstructuremenu.ini')|eq('enabled')}true{else}false{/if};
{/cache-block}


{default current_user=fetch('user','current_user')}
    this.perm = "{concat($current_user.role_id_list|implode(','),'|',$current_user.limited_assignment_value_list|implode(','))|md5}";
{/default}

{literal}
    this.updateCookie = function()
    {
        this.cookie = this.open.join('/');
        expireDate = new Date();
        expireDate.setTime( expireDate.getTime() + this.cookieValidity * 86400000 );
        ezjslib_setCookie( this.cookieName, this.cookie, expireDate );
    }

    this.setOpen = function( nodeID )
    {
        if ( this.open.inArray( nodeID ) )
        {
            return;
        }
        this.open[this.open.length] = nodeID;
        this.updateCookie();
    }

    this.setClosed = function( nodeID )
    {
        if ( this.open.removeFirst( nodeID ) )
        {
            this.updateCookie();
        }
    }

    this.generateEntry = function( item, lastli, rootNode )
    {
        var liclass = '';
        if ( lastli )
        {
            liclass += ' lastli';
        }
        if ( path && ( path[path.length-1] == item.node_id || ( !item.has_children && path.inArray( item.node_id ) ) ) )
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
                +' )"></a>';
        }

        var languages = "[";
        var firstLanguage = true;
        for ( var j = 0; j < item.languages.length; j++ )
        {
            if ( this.languages[item.languages[j]] )
            {
                if ( !firstLanguage )
                {
                    languages += ",";
                }
                firstLanguage = false;
                languages += "{locale:'"
                    + item.languages[j].replace(/'/g,"\\'")
                    + "',name:'"
                    + this.languages[item.languages[j]].replace(/'/g,"\\'")
                    + "'}";
            }
        }
        languages += "]";

        var canCreateClasses = false;
        var classes = "[";
        if ( this.createHereMenu != 'disabled' )
        {
            if ( this.createHereMenu == 'full' )
            {
                var classList = item.class_list;

                for ( var j = 0; j < classList.length; j++ )
                {
                    if ( this.classes[classList[j]] )
                    {
                        if ( canCreateClasses )
                        {
                            classes += ",";
                        }
                        canCreateClasses = true;
                        classes += "{classID:'"
                            + classList[j]
                            + "',name:'"
                            + String(this.classes[classList[j]].name).replace(/'/g,"\\'").replace(/>/g,'&gt;').replace(/"/g,'&quot;')
                            + "'}";
                    }
                }
            }
            else
            {
                for ( j in this.classes )
                {
                    if ( canCreateClasses )
                    {
                        classes += ",";
                    }
                    canCreateClasses = true;
                    classes += "{classID:'"
                        + j
                        + "',name:'"
                        + String(this.classes[j].name).replace(/'/g,"\\'").replace(/>/g,'&gt;').replace(/"/g,'&quot;')
                        + "'}";
                }
            }
        }
        classes += "]";

        var classIdentifier = this.classes[item.class_id].identifier;
        var icon = ( this.iconsList[classIdentifier] )? this.iconsList[classIdentifier]: this.iconsList['__default__'];
        if ( this.context != 'browse' )
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
                + this.classes[item.class_id].name.replace(/>/g,'&gt;').replace(/"/g, '&quot;')
{/literal}
                + '] {"Click on the icon to display a context-sensitive menu."|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}" /></a>';
{literal}
        }
        else
        {
            html += '<img src="'
                + icon
                + '" alt="" />';
        }
        html += '&nbsp;<a class="nodetext" href="'
            + ( ( this.action )? this.action + '/' + item.node_id:
                                 item.url )
            + '"';

        if ( this.showTips )
        {
{/literal}
            html += ' title="{"Node ID"|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}: '
                + item.node_id
                + ' {"Visibility"|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}: '
                + ( ( item.is_hidden )? '{"Hidden"|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}':
                                        ( item.is_invisible )? '{"Hidden by superior"|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}':
                                                               '{"Visible"|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}' )
                + '"';
{literal}
        }

        html += '><span class="node-name-'
            + ( ( item.is_hidden )? 'hidden':
                                    ( item.is_invisible )? 'hiddenbyparent':
                                                           'normal' )
            + '">'
            + item.name
            + '</span>';

        if ( item.is_hidden )
        {
{/literal}
            html += '<span class="node-hidden"> ({"Hidden"|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)})</span>';
{literal}
        }
        else if ( item.is_invisible )
        {
{/literal}
            html += '<span class="node-hiddenbyparent"> ({"Hidden by superior"|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)})</span>';
{literal}
        }

        html += '</a>';
        html += '<div id="c'
            + item.node_id
            + '"></div>';
        html += '</li>';

        return html;
    }

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

{/literal}
        var url = "{"content/treemenu"|ezurl(no)}/" + nodeID
            + "/" + modifiedSubnode
            + "/" + this.expiry
            + "/" + this.perm;
{literal}

        divElement.className = 'busy';
        if ( aElement )
        {
            aElement.className = "openclose-busy";
        }

        var thisThis = this;

        var callbacks = {

            result: false,

            success: function(o)
            {
                try 
                {
                    result = YAHOO.lang.JSON.parse(o.responseText);
                }
                catch (x) 
                {
                    return false;
                }
                
                var html = '<ul>';
                for ( var i = 0; i < result.children.length; i++ )
                {
                    var item = result.children[i];
                    html += thisThis.generateEntry( item, i == result.children.length - 1, false );
                }
                html += '</ul>';

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

            failure: function(o) {
                if ( aElement )
                {
                    aElement.className = 'openclose-error';

                    switch( o.status )
                    {
                        case 403:
                        {
{/literal}
                            aElement.title = '{"Dynamic tree not allowed for this siteaccess"|i18n('design/admin/contentstructuremenu')|wash(javascript)}';
{literal}
                        } break;

                        case 404:
                        {
{/literal}
                            aElement.title = '{"Node does not exist"|i18n('design/admin/contentstructuremenu')|wash(javascript)}';
{literal}
                        } break;

                        case 500:
                        {
{/literal}
                            aElement.title = '{"Internal error"|i18n('design/admin/contentstructuremenu')|wash(javascript)}';
{literal}
                        } break;
                    }
                    aElement.onclick = function()
                    {
                        return false;
                    }
                }
            }
        }

        var request = YAHOO.util.Connect.asyncRequest('GET', url, callbacks);

    }

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
                var nodeID = parseInt( liCandidate.id.substr( 1 ) );
                if ( this.autoOpen && autoOpenPath.inArray( nodeID ) )
                {
                    autoOpenPath.removeFirst( nodeID );
                    this.setOpen( nodeID );
                }
                if ( this.open.inArray( nodeID ) )
                {
                    var aElement = document.getElementById( 'a' + nodeID );
                    if ( aElement )
                    {
                        aElement.onclick();
                    }
                }
            }
        }
    }

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
                var nodeID = aElement.id.substr( 1 );
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
            aElement.className = 'openclose-open';
            this.setClosed( parentNodeID );
        }
    }
}

// -->
</script>
{/literal}

<script type="text/javascript">
<!--
    var path = [{foreach $module_result.path as $element}{$element.node_id}{delimiter}, {/delimiter}{/foreach}];
    var autoOpenPath = path;

    var treeMenu = new ContentStructureMenu();

{cache-block keys=$root_node_id expiry="0"}

    var rootNode = {ldelim}{*
        *}"node_id":{$root_node.node_id},{*
        *}"object_id":{$root_node.object.id},{*
        *}"class_id":{$root_node.object.contentclass_id},{*
        *}"has_children":{if $root_node.children_count}true{else}false{/if},{*
        *}"name":"{$root_node.name|wash(javascript)}",{*
        *}"url":{$root_node.url|ezurl},{*
        *}"modified_subnode":{$root_node.modified_subnode},{*
        *}"languages":["{$root_node.object.language_codes|implode('", "')}"],{*
        *}"class_list":[{foreach fetch('content','can_instantiate_class_list',hash('parent_node',$root_node.node_id, 'filter_type', $filter_type, 'group_id', $filter_groups)) as $class}{$class.id}{delimiter},{/delimiter}{/foreach}]{rdelim};

    document.writeln( '<ul id="content_tree_menu">' );
    document.writeln( treeMenu.generateEntry( rootNode, false, true ) );
    document.writeln( '</ul>' );

    treeMenu.load( false, {$root_node.node_id}, {$root_node.modified_subnode} );
{/cache-block}

// -->
</script>
