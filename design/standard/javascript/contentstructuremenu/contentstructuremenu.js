//
// Created on: <14-Jul-2004 14:18:58 dl>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
    \a bInitNodesText - initialize HTML nodes( e.g sets text [-]/[ ])
*/
function ezcst_foldUnfold( node, bUpdateCookie, bInitNodesText, bForceFold, bForceUnfold )
{
    for ( var i = 0; i < node.childNodes.length; ++i )
    {
        var child = node.childNodes[i];

        if ( child["tagName"] && child.tagName.toLowerCase() == "ul" )
        {
            var node_id     = bUpdateCookie ? node.getAttribute( "id" ) : null;
            var link_node   = ezjslib_getHTMLChildNodeByTag( node, "a" );

            if( bInitNodesText == true)
                ezjslib_createHTMLChildTextNode( link_node, "[-]" );

            ezcst_changeState( node_id, child, link_node, bForceFold, bForceUnfold );
            break;
        }
        else if ( bInitNodesText && child["tagName"] && child.tagName.toLowerCase() == "span" )
        {
            ezjslib_createHTMLChildTextNode( child, "[ ]" );
        }
    }
}

/*!
    Fold/unfold subtree by recursive calls.
    \a root_node is a root node of subtree.
    \a bUpdateCookie - if sets to \a true, then cookie will be updated.
    \a bInitNodesText - initialize HTML nodes( e.g sets text [-]/[ ])
    \a bForceFold - if node was folded then its state will be unchanged
    \a bForceUnfold - if node was unfolded then its state will be unchanged
    \a bExludeRootNode - If true then state of root node will be unchanged.
*/
function ezcst_foldUnfoldSubtree( rootNode, bUpdateCookie, bInitNodesText, bForceFold, bForceUnfold, bExcludeRootNode )
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
                ezcst_foldUnfoldSubtree( li_node, bUpdateCookie, bInitNodesText, bForceFold, bForceUnfold, false );
            }
        }
    }

    // fold/unfold current subtree.
    if ( !bExcludeRootNode )
    {
        ezcst_foldUnfold( rootNode, bUpdateCookie, bInitNodesText, bForceFold, bForceUnfold );
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
    Restores menu state from cookie, adds current location from
    \a additionalNodesList.
*/
function ezcst_initializeMenuState( additionalNodesList, menuNodeID, autoopenCurrentNode )
{
    var menu          = ezjslib_getHTMLNodeById( menuNodeID );
    var currentNodeID = additionalNodesList.pop();           // remove current node from list.

    if ( menu != null )
    {
        // restore unfolded nodes ids from cookies
        ezcst_cookie_restoreUnfoldedNodesList();

        // add path to current node to unfolded nodes list
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
            ezjslib_setHTMLNodeClassStyle( currentNode, EZCST_HIGHLIGHTED_NODE_CLASS_NAME );

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

