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
    
    Functions which works direct with cookie:
        ezcst_setCookie,
        ezcst_getCookie.
        
   Functions which works with cookie but adapted to tree menu:
        ezcst_cookie_restoreUnfoldedNodesList,
        ezcst_cookie_saveUnfoldedNodesList,
        ezcst_cookie_addNode,
        ezcst_cookie_addNodesList,
        ezcst_cookie_removeNode.
        
   Functions which works with HTMLElements:
        ezcst_findNodeText,
        ezcst_setNodeText,
        ezcst_removeNodeText,
        ezcst_createChildTextNode,
        ezcst_getHTMLNodeById,
        ezcst_getHTMLChildNodeByTag.
        
    Functions which change state of node(folded/unfolded):        
        ezcst_changeState,
        ezcst_foldUnfold,
        ezcst_foldUnfoldSubtree.
        
    Functions which initializes menu:        
        ezcst_initializeMenuState
        ezcst_resetMenuState,
        ezcst_restoreMenuState.
*/

/*! IE 5.0 support 
    Functions: Array.push, Array.pop, Array.splice(REMOVE elements only)
*/
if ( ![].push )
{
    Array.prototype.push=function(i)
    {
        this[this.length] = i;
    }
}

if ( ![].pop )
{
    Array.prototype.pop=function()
    {
        var last      = this[this.length];
        this.length   = this.length - 1;
        return last;
    }
}

if ( ![].splice )
{
    // ONLY REMOVES 
    Array.prototype.splice=function(startIdx, count)
    {
        for ( var i = startIdx; i < this.length - count; i++ )
            this[i] = this[i + count];

        this.length = this.length - count;
    }
}
/* end of IE 5.0 */


/*!
    Sets cookie with \a name, \a value and \a expires date.
*/
function ezcst_setCookie( name, value, expires ) 
{
    document.cookie = name + '=' + escape(value) + (( !expires ) ? "" : ('; expires=' + expires.toUTCString())) + '; path=/';
} 

/*!
    \return a value of cookie with name \a name.
*/
function ezcst_getCookie( name ) 
{
    var cookie  = document.cookie;
    
    var startPos = cookie.indexOf( name );
    if ( startPos != -1 )
    {
        startPos += name.length + 1;

        var endPos = cookie.indexOf( ";", startPos );
        if ( endPos == -1 )
            endPos = cookie.length;

        return unescape( cookie.substring(startPos, endPos) );
    }

    return null;
} 
                    
/*!
    Global array of unfolded nodes 
*/
var gUnfoldedNodesList = new Array(0);

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
    var strNodesList = ezcst_getCookie( "ezcst_unfolded_node_list" );
    if ( strNodesList != null )
    {
        gUnfoldedNodesList = strNodesList.split( "," );
        return true;
    }

    return false;
}

/*!
     Stores values from \a gUnfoldedNodesList to cookie
*/
function ezcst_cookie_saveUnfoldedNodesList()
{
    var strNodesList = gUnfoldedNodesList.join( "," );
    ezcst_setCookie( "ezcst_unfolded_node_list", strNodesList );
}

/*!
     Adds \a node_id to \a gUnfoldedNodesList and store it in cookie
*/
function ezcst_cookie_addNode( node_id )
{
    if ( node_id && (node_id != null) )
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
    Finds the text of \a node
*/
function ezcst_findNodeText( node )
{
    if( node )
    {
        for ( var i = 0; i < node.childNodes.length; i++ ) 
        {
            if ( node.childNodes[i].nodeType == 3 )
            {
                return node.childNodes[i];
            }
        }
    }

    return null;
}

/*! 
    Finds the text of \a node and replaces it with \a text
*/
function ezcst_setNodeText( node, text )
{
    var textNode = ezcst_findNodeText( node );
    if( textNode != null )
    {
        textNode.data = text;
    }
}

/*! 
    Finds text of \a node and removes it
*/
function ezcst_removeNodeText( node )
{
    var textNode = ezcst_findNodeText( node );
    if( textNode != null )
    {
        node.removeChild( textNode );
    }
}

/*! 
    Creates and appends child text node with text \a text to node \a node
*/
function ezcst_createChildTextNode( node, text )
{
    if ( node != null )
    {
        var textNode = document.createTextNode( text );
        node.appendChild( textNode );
    }
}

/*! 
    Changes state(folded/unfolded) of node.
    Saves \a node_id in cookie,
    changes display status of \a ul_node,
    changes text of \a link_node
*/                                                   
function ezcst_changeState( node_id, ul_node, link_node )
{
    // change display state of ul_node and label for link_node
    if ( ul_node && link_node )
    {
        if ( ul_node.style.display == "none" )
        { 
            // fold state => make it unfold
                ul_node.style.display = "";
            // change label
                ezcst_setNodeText( link_node, "[-]" );
            // update cookie
                ezcst_cookie_addNode( node_id );
        }
        else 
        { 
            // unfold state => make it fold
                ul_node.style.display = "none";
            //Change label
                ezcst_setNodeText( link_node, "[+]" );
            // update cookie
                ezcst_cookie_removeNode( node_id );
        } 
    }
}

/*! 
    \return HTMLElement with id \a node_id
*/
function ezcst_getHTMLNodeById( node_id )
{
    return document.getElementById( node_id );
}

/*! 
    \return a child HTMLElement of \a node by tag \a tag
*/
function ezcst_getHTMLChildNodeByTag( node, tag )
{
    for ( var i = 0; i < node.childNodes.length; ++i )
    {
        var child = node.childNodes[i];

        if ( child["tagName"] && child.tagName.toLowerCase() == tag )
        {
            return child;
        }
    }

    return null;
}

/*! 
    onClick handler for \a node
*/
function ezcst_onFoldClicked( node )
{
    ezcst_foldUnfold( node, true, false );
}

/*! 
    Fold/unfold \a node. If \a bUpdateCookie sets to 
    \a true then cookie will be updated.
    \a bInitNodesText - initialize HTML nodes( e.g sets text [-]/[ ])
*/
function ezcst_foldUnfold( node, bUpdateCookie, bInitNodesText ) 
{ 
    for ( var i = 0; i < node.childNodes.length; ++i )
    {
        var child = node.childNodes[i];

        if ( child["tagName"] && child.tagName.toLowerCase() == "ul" )
        {
            var node_id     = bUpdateCookie ? node.getAttribute( "id" ) : null;
            var link_node   = ezcst_getHTMLChildNodeByTag( node, "a" );

            if( bInitNodesText == true)
                ezcst_createChildTextNode( link_node, "[-]" );

            ezcst_changeState( node_id, child, link_node );
            break;
        }
        else if ( bInitNodesText && child["tagName"] && child.tagName.toLowerCase() == "span" ) 
        {
            ezcst_createChildTextNode( child, "[ ]" );
        }
    }
}   

/*! 
    Fold/unfold subtree by recursive calls.
    \a root_node is a root node of subtree.
    \a bUpdateCookie - if sets to \a true, then cookie will be updated.
    \a bInitNodesText - initialize HTML nodes( e.g sets text [-]/[ ])
*/
function ezcst_foldUnfoldSubtree( rootNode, bUpdateCookie, bInitNodesText )
{
    var root_ul_node = ezcst_getHTMLChildNodeByTag( rootNode, "ul" );

    if ( root_ul_node != null )
    {
        // search subtrees by looping through child LI tags.
        for ( var i = 0; i < root_ul_node.childNodes.length; i++ )
        {
            var li_node = root_ul_node.childNodes[i];
            if ( li_node["tagName"] && li_node.tagName.toLowerCase() == "li" )
            {
                ezcst_foldUnfoldSubtree( li_node, bUpdateCookie, bInitNodesText );
            }
        }
    }

    // fold/unfold current subtree.
    ezcst_foldUnfold( rootNode, bUpdateCookie, bInitNodesText );
}

/*!
    Default menu state: all 'container' nodes(except root node) are folded 
*/
function ezcst_resetMenuState( rootNode )
{   
    if ( rootNode != null )
    {
        // Fold all 'container' nodes.
        ezcst_foldUnfoldSubtree( rootNode, false, true );

        // Remove [-]/[+] text of root node.
        var root_link_node = ezcst_getHTMLChildNodeByTag( rootNode, "a" );
        ezcst_removeNodeText( root_link_node );                
    
        // Since all nodes are folded, need to unfold root node.
        ezcst_foldUnfold       ( rootNode, true, false );
    }
}

/*!
    Restores menu state from \a gUnfoldedNodesList.
*/
function ezcst_restoreMenuState( rootNode )
{
    if ( rootNode != null )
    {
        // Fold all 'container' nodes.
        ezcst_foldUnfoldSubtree( rootNode, false, true );

        // Remove [-]/[+] text of root node.
        var root_link_node = ezcst_getHTMLChildNodeByTag( rootNode, "a" );
        ezcst_removeNodeText( root_link_node );                
        
        // unfold nodes which are where stored in cookies.
        for ( var i = 0; i < gUnfoldedNodesList.length; ++i )
        {
            var li_node = ezcst_getHTMLNodeById( gUnfoldedNodesList[i] );
            if ( li_node )
            {
                ezcst_foldUnfold( li_node, false, false );
            }
        }
    }
}

/*!
    Restores menu state from cookie, adds current location from
    \a additionalNodesList.
*/
function ezcst_initializeMenuState( additionalNodesList, menuNodeID)
{
    var menu = ezcst_getHTMLNodeById( menuNodeID );
    
    if ( menu != null )
    {
        // restore unfolded nodes ids from cookies
        ezcst_cookie_restoreUnfoldedNodesList();
        
        // add path to current node to unfolded list
        ezcst_cookie_addNodesList( additionalNodesList );
        
        var rootNode = ezcst_getHTMLChildNodeByTag( menu, "li" );
        if ( rootNode != null )
        {
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
        }

        // show menu
        menu.style.display="";
    }
}

