//
// Created on: <14-Jul-2004 14:18:58 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file contentstructuremenu.js
*/

/*!
    \brief
    This is a set of functions which helps to organize a work of
    tree-menu: fold/unfold nodes, save/restore state of menu
    to/from cookie, some helpers.


   Functions which works with cookie but adapted to tree menu:
        ezcst_cookie_restoreUnfoldedNodesList,
        ezcst_cookie_saveUnfoldedNodesList,
        ezcst_cookie_addNode,
        ezcst_cookie_addNodesList,
        ezcst_cookie_removeNode.

    Functions which change state of node(folded/unfolded):
        ezcst_changeState,
        ezcst_setFoldedState,
        ezcst_setUnfoldedState,
        ezcst_foldUnfold,
        ezcst_foldUnfoldSubtree,
        ezcst_unfoldNode.

    Functions which initializes menu:
        ezcst_createUnfoldedLabel,
        ezcst_createEmptyLabel,
        ezcst_setFoldUnfoldIcons,
        ezcst_initializeMenuState,
        ezcst_resetMenuState,
        ezcst_restoreMenuState.

    Event handlers:
        ezcst_onFoldClicked,
        ezcst_onItemClicked.

*/

/*!
    Global array of unfolded nodes
*/
var gUnfoldedNodesList                          = new Array(0);

/*!
    Pathes to fold/unfold icons
*/
var gUseFoldUnfoldIcons                         = false;
var gFoldIcon                                   = '';
var gUnfoldIcon                                 = '';
var gEmptyIcon                                  = '';
var gFoldUnfoldIconsWidth                       = 16;
var gFoldUnfoldIconsHeight                      = 16;

/*!
    Global identifier of the Root Node
*/
var gRootNodeID                                 = 0;

/*!
    CSS class names for current of node
*/
var  EZCST_HIGHLIGHTED_NODE_CLASS_NAME          = "currentnode";

/*!
    Cookie name where unfolded nodes list is stored
*/
var  EZCST_UNFOLDED_LIST_COOKIE_NAME            = "ezcst_unfolded_node_list";
var  EZCST_UNFOLDED_LIST_VALUES_DELIMITER       = ",";

/*!
    Default url to redirect when user clicks on menu item.
*/
var  gItemClickAction                           = "";

/*!
    \return size of \a gUnfoldedNodesList.
*/
function ezcst_getUnfoldedNodesListSize()
{
    return gUnfoldedNodesList.length;
}

/*!
    Searches a node \a node_id in \a gUnfoldedNodesList
    \return if found nothing returns -1, else index of node.
*/
function ezcst_findNodeIDInList( node_id )
{
    var len = gUnfoldedNodesList.length;
    for ( var i = 0; i < len; ++i )
    {
        if( gUnfoldedNodesList[i] == node_id )
            return i;
    }

    return -1;
}

/*!
    cookie wrappers
*/
/*!
     Initializes \a gUnfoldedNodesList with values stored in cookie
*/
function ezcst_cookie_restoreUnfoldedNodesList()
{
    gUnfoldedNodesList = ezjslib_getCookieToArray( EZCST_UNFOLDED_LIST_COOKIE_NAME, EZCST_UNFOLDED_LIST_VALUES_DELIMITER );
}

/*!
     Stores values from \a gUnfoldedNodesList to cookie
*/
function ezcst_cookie_saveUnfoldedNodesList()
{
    ezjslib_setCookieFromArray( EZCST_UNFOLDED_LIST_COOKIE_NAME, gUnfoldedNodesList, null, EZCST_UNFOLDED_LIST_VALUES_DELIMITER );
}

/*!
     Adds \a node_id to \a gUnfoldedNodesList and store it in cookie
*/
function ezcst_cookie_addNode( node_id )
{
    if ( node_id )
    {
        if ( ezcst_findNodeIDInList( node_id ) == -1 )
        {
            gUnfoldedNodesList[gUnfoldedNodesList.length] = node_id;
            ezcst_cookie_saveUnfoldedNodesList();
        }
    }
}

/*!
     Adds \a nodesList to \a gUnfoldedNodesList and store it in cookie
*/
function ezcst_cookie_addNodesList( nodesList )
{
    if ( nodesList )
    {
        var len = nodesList.length;
        for ( var i = 0; i < len; ++i )
        {
            ezcst_cookie_addNode( nodesList[i] );
        }
    }
}

/*!
     Removes \a node_id from \a gUnfoldedNodesList and cookie
*/
function ezcst_cookie_removeNode( node_id )
{
    if ( node_id && (node_id != null) )
    {
        var idx = ezcst_findNodeIDInList( node_id );
        if ( idx != -1 )
        {
            gUnfoldedNodesList.splice( idx, 1 );
            ezcst_cookie_saveUnfoldedNodesList();
        }
    }
}

/*!
    Changes state(folded/unfolded) of node.
    Saves \a node_id in cookie,
    changes display status of \a ul_node,
    changes text of \a link_node
*/
function ezcst_changeState( node_id, ul_node, link_node, bForceFold, bForceUnfold )
{
    // change display state of ul_node and label for link_node
    if ( ul_node )
    {
        if ( bForceFold )
        {
            ezcst_setFoldedState( node_id, ul_node, link_node );
            return;
        }

        if ( bForceUnfold )
        {
            ezcst_setUnfoldedState( node_id, ul_node, link_node );
            return;
        }

        if ( ul_node.style.display == "none" )
        {
            ezcst_setUnfoldedState( node_id, ul_node, link_node );
        }
        else
        {
            ezcst_setFoldedState( node_id, ul_node, link_node );
        }
    }
}

/*!
    Sets unfolded state of node.
    Saves \a node_id in cookie,
    changes display status of \a ul_node,
    changes text of \a link_node
*/
function ezcst_setUnfoldedState( node_id, ul_node, link_node )
{
    if ( ul_node && ul_node.style.display == "none" )
    {
        // fold state => make it unfold
            ul_node.style.display = "";

       // change label
            if ( gUseFoldUnfoldIcons )
                ezjslib_setImageSourceToHTMLChildImageNode( link_node, gUnfoldIcon );
            else
                ezjslib_setTextToHTMLChildTextNode( link_node, "[-]" );

        // update cookie
            ezcst_cookie_addNode( node_id );
    }
}

/*!
    Sets folded state of node.
    Saves \a node_id in cookie,
    changes display status of \a ul_node,
    changes text of \a link_node
*/
function ezcst_setFoldedState( node_id, ul_node, link_node )
{
    if ( ul_node && ul_node.style.display != "none" )
    {
        // unfold state => make it fold
            ul_node.style.display = "none";

       //Change label
            if ( gUseFoldUnfoldIcons )
                ezjslib_setImageSourceToHTMLChildImageNode( link_node, gFoldIcon );
            else
                ezjslib_setTextToHTMLChildTextNode( link_node, "[+]" );

        // update cookie
            ezcst_cookie_removeNode( node_id );
    }
}

/*!
    onClick handler for \a node
*/
function ezcst_onFoldClicked( node )
{
    ezcst_foldUnfold( node, true, false, false, false );
}

/*!
    onClick handler for menu item.
    \a ezpublish_nodeID is a id of the node
    \a defaultItemClickAction default redirect url
*/
function ezcst_onItemClicked( ezpublish_nodeID, defaultItemClickAction )
{
    var redirectURL = ( gItemClickAction != '' ) ? ( gItemClickAction + '/' + ezpublish_nodeID ) : defaultItemClickAction;
    location.href = redirectURL;
}

/*!
    Fold/unfold \a node. If \a bUpdateCookie sets to
    \a true then cookie will be updated.
    \a bInitFoldUnfoldLabels - initialize HTML nodes( e.g sets text [-]/[ ])
*/
function ezcst_foldUnfold( node, bUpdateCookie, bInitFoldUnfoldLabels, bForceFold, bForceUnfold )
{
    if( node )
    {
        for ( var i = 0; i < node.childNodes.length; ++i )
        {
            var child = node.childNodes[i];

            if ( child["tagName"] && child.tagName.toLowerCase() == "ul" )
            {
                var node_id     = bUpdateCookie ? node.getAttribute( "id" ) : null;
                var link_node   = ezjslib_getHTMLChildNodeByTag( node, "a" );

                if( bInitFoldUnfoldLabels == true)
                    ezcst_createUnfoldedLabel( link_node );

                ezcst_changeState( node_id, child, link_node, bForceFold, bForceUnfold );
                break;
            }
            else if ( bInitFoldUnfoldLabels && child["tagName"] && child.tagName.toLowerCase() == "span" )
            {
                ezcst_createEmptyLabel( child );
            }
        }
    }
}

/*!
    Fold/unfold subtree by recursive calls.
    \a root_node is a root node of subtree.
    \a bUpdateCookie - if sets to \a true, then cookie will be updated.
    \a bInitFoldUnfoldLabels - initialize HTML nodes( e.g sets text [-]/[ ])
    \a bForceFold - if node was folded then its state will be unchanged
    \a bForceUnfold - if node was unfolded then its state will be unchanged
    \a bExludeRootNode - If true then state of root node will be unchanged.
*/
function ezcst_foldUnfoldSubtree( rootNode, bUpdateCookie, bInitFoldUnfoldLabels, bForceFold, bForceUnfold, bExcludeRootNode )
{
    var root_ul_node = ezjslib_getHTMLChildNodeByTag( rootNode, "ul" );

    if ( root_ul_node != null )
    {
        // search subtrees by looping through child LI tags.
        for ( var i = 0; i < root_ul_node.childNodes.length; i++ )
        {
            var li_node = root_ul_node.childNodes[i];
            if ( li_node["tagName"] && li_node.tagName.toLowerCase() == "li" )
            {
                ezcst_foldUnfoldSubtree( li_node, bUpdateCookie, bInitFoldUnfoldLabels, bForceFold, bForceUnfold, false );
            }
        }
    }

    // fold/unfold current subtree.
    if ( !bExcludeRootNode )
    {
        ezcst_foldUnfold( rootNode, bUpdateCookie, bInitFoldUnfoldLabels, bForceFold, bForceUnfold );
    }
}

/*!
    Just unfold node.
*/
function ezcst_unfoldNode( li_node )
{
    var ul_node = ezjslib_getHTMLChildNodeByTag( li_node, "ul" );
    ezcst_setUnfoldedState( null, ul_node, null );
}

/*!
    Expands subtree(node and all its children and children of their children
    and so on...) with root \a rootNodeID.
*/
function ezcst_expandSubtree( rootNodeID )
{
    ezcst_collapseExpandSubtree( rootNodeID, false, false );
}

/*!
    Collapses subtree(node and all its children and children of their children
    and so on...) with root \a rootNodeID.
*/
function ezcst_collapseSubtree( rootNodeID )
{
    ezcst_collapseExpandSubtree( rootNodeID, true, true );
}

/*!
    Collapses/expands subtree(node and all its children and children of their children
    and so on...) with root \a rootNodeID.
*/
function ezcst_collapseExpandSubtree( rootNodeID, bCollapse, bExcludeRootNode )
{
    if ( rootNodeID )
    {
        var liTagID = 'n' + rootNodeID;
        var liTag   = ezjslib_getHTMLNodeById( liTagID );
        if ( liTag )
        {
            ezcst_foldUnfoldSubtree( liTag, true, false, bCollapse, !bCollapse, ( bExcludeRootNode && ( gRootNodeID == liTagID ) ) );
        }
    }
}

/*!
    Default menu state: all 'container' nodes(except root node) are folded
*/
function ezcst_resetMenuState( rootNode )
{
    if ( rootNode != null )
    {
        // Since all nodes are folded, need to unfold root node.
        ezcst_foldUnfold( rootNode, true, false, false, false );
    }
}

/*!
    Restores menu state from \a gUnfoldedNodesList.
*/
function ezcst_restoreMenuState( rootNode )
{
    if ( rootNode != null )
    {
        // unfold nodes which were stored in cookies.
        for ( var i = 0; i < gUnfoldedNodesList.length; ++i )
        {
            var li_node = ezjslib_getHTMLNodeById( gUnfoldedNodesList[i] );
            if ( li_node )
            {
                ezcst_foldUnfold( li_node, false, false, false, false );
            }
        }

        ezcst_unfoldNode( rootNode );
    }
}

/*!
    Creates initial text for [-] label.
*/
function ezcst_createUnfoldedLabel( node )
{
    if ( node )
    {
        if ( gUseFoldUnfoldIcons )
            ezjslib_createHTMLChildImageNode( node, gUnfoldIcon, gFoldUnfoldIconsWidth, gFoldUnfoldIconsHeight );
        else
            ezjslib_createHTMLChildTextNode( node, "[-]" );
    }
}

/*!
    Create an empty text( '[ ]' ) label
*/
function ezcst_createEmptyLabel( node )
{
    if ( node )
    {
        if ( gUseFoldUnfoldIcons )
            ezjslib_createHTMLChildImageNode( node, gEmptyIcon, gFoldUnfoldIconsWidth, gFoldUnfoldIconsHeight );
        else
            ezjslib_createHTMLChildTextNode( node, "[ ]" );
        //ezjslib_createHTMLChildTextNode( node, "" );
    }
}

/*!
    Sets icons instead of text labels [-]/[+]/[ ]
*/
function ezcst_setFoldUnfoldIcons( foldIcon, unfoldIcon, emptyIcon )
{
    if ( foldIcon && unfoldIcon )
    {
        gFoldIcon = foldIcon;
        gUnfoldIcon = unfoldIcon;
        gEmptyIcon = emptyIcon;
        gUseFoldUnfoldIcons = true;
    }
}

/*!
    Restores menu state from cookie, adds current location from
    \a additionalNodesList.
*/
function ezcst_initializeMenuState( additionalNodesList, menuNodeID, autoopenCurrentNode )
{
    var menu          = ezjslib_getHTMLNodeById( menuNodeID );
    if ( menu != null )
    {
        // restore unfolded nodes ids from cookies
        ezcst_cookie_restoreUnfoldedNodesList();

        // add current node's path to unfolded nodes list
        var currentNodeID = additionalNodesList.pop();           // remove current node from list.
        ezcst_cookie_addNodesList( additionalNodesList );

        var rootNode = ezjslib_getHTMLChildNodeByTag( menu, "li" );
        if ( rootNode != null )
        {
            // remember id of Root Node.
            gRootNodeID = rootNode.getAttribute( "id" );

            // Fold all 'container' nodes.
            ezcst_foldUnfoldSubtree( rootNode, false, true, false, false, false );

            // Remove [-]/[+] text of root node.
            var root_link_node = ezjslib_getHTMLChildNodeByTag( rootNode, "a" );
            if ( gUseFoldUnfoldIcons )
                ezjslib_removeHTMLChildImageNode( root_link_node );
            else
                ezjslib_removeHTMLChildTextNode( root_link_node );

            if ( ezcst_getUnfoldedNodesListSize() > 0 )
            {
                // unfolde nodes which are stored in gUnfoldedNodesList
                ezcst_restoreMenuState( rootNode );
            }
            else
            {
                // reset to default view
                // probably we never get into this "else".
                ezcst_resetMenuState  ( rootNode );
            }

            // Highlight current node
            var currentNode = ezjslib_getHTMLNodeById( currentNodeID );
            while( !currentNode && currentNodeID )
            {
                // if current viewing node is not in the tree menu(is invesible)
                // then try to find the nearest visible parent node
                currentNodeID = additionalNodesList.pop();
                currentNode = ezjslib_getHTMLNodeById( currentNodeID );
            }
            ezjslib_appendHTMLNodeClassStyle( currentNode, EZCST_HIGHLIGHTED_NODE_CLASS_NAME );

            if( autoopenCurrentNode == "enabled" )
            {
                // unfold current node.
                ezcst_foldUnfold( currentNode, true, false, false, true );
            }
        }

        // show menu
        menu.style.display="";
    }
}

