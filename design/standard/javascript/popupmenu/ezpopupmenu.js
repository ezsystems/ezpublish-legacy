//
// Created on: <1-Aug-2002 16:45:00 fh>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezpopupmenu.js
*/


/*!
  \brief
  This file contains the methods needed to create very cool and flexible javascript
  menus for eZ publish.

 How to use this script:
 You need to do two things in order to use this script:

 1. Set up the HTML/CSS structures for your menus
 Each menu must be defined. The outer element should be a "div". The clickable menuitems should be of type "a".
 Both the menu and the menuitems must be given and "id". This id is used in the menuArray to set up that menu/item.
 Also each item in the menu must have its onmouseover activate the ezpopmnu_mouseOver method with the name of the menu
 as parameter.
 To open a submenu use the ezpopmnu_ShowSubLevel method. It takes the name of the menu as parameter.

 example with a main menu and a submenu:
 <div class="menu" id="ContextMenu">
  <a id="menu-view" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">Edit</a>
  <a id="menu-edit" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">View</a>
  <hr />
  <a id="menu-new" href="#" onmouseover="ezpopmnu_showSubLevel( 'submenu' )" >Create new</a>
 </div>
 The submenu basically works the same way.
 <div class="menu" id="submenu">
  <a id="menu-new-article" href="#" onmouseover="ezpopmnu_mouseOver( 'submenu')">Article</a>
  <a id="menu-new-folder" href="#" onmouseover="ezpopmnu_mouseOver( 'submenu')">Folder</a>
 </div>

 To activate the first menu call the method ezpopmnu_showTopLevel. It takes the name of the menu
 and the node id as parameter.

 example:
 <a href="javascript: ezpopmnu_showTopLevel( 'ContextMenu', 42 )"
   onmouseover="ezpopmnu_showTopLevel( 'ContextMenu', '42' )">show</a><br />


  Note that the menus need to have the following css properties set:
  position: absolute;
  z-index: +1;
  visibility: hidden;

 2. Set up the menuArray javascript array.
 The example demonstrates how to configure the menuArray. Noteworthy features are:
 elements: An array of element "id" tags. Items present here will be processed. Items left out
 will just be shown without any manipulation.
 url: You can put a "%" in the url. It will be exchanged with the node id.
 disabled_for: List the elements you want to be shown with a special class.

 example:
 var menuArray = new Array();
 <!-- Context Menu -->
 menuArray['ContextMenu'] = new Array();
 menuArray['ContextMenu']['depth'] = 0;  // depth of menu. Toplevel = 0.
 menuArray['ContextMenu']['elements'] = new Array();
 menuArray['ContextMenu']['elements']['menu-view'] = new Array();
 menuArray['ContextMenu']['elements']['menu-view']['url'] = '/content/view/%';

 menuArray['ContextMenu']['elements']['menu-edit'] = new Array();
 menuArray['ContextMenu']['elements']['menu-edit']['url'] = '/content/edit/%';
 menuArray['ContextMenu']['elements']['menu-view']['disabled_for'] = new Array();
 menuArray['ContextMenu']['elements']['menu-view']['disabled_for'][35] = 'yes';
 menuArray['ContextMenu']['elements']['menu-view']['disabled_for'][55] = 'yes';

 <!-- New menu -->
 menuArray['submenu'] = new Array();
 menuArray['submenu']['depth'] = 1; // this is a first level submenu of ContextMenu

 Note: in order to use this script you must include ezlib.js

 Devloper info:
 This script defines the following global constants/variables:
 EZPOPMNU_OFFSET - Added to the x,y position of the mouse when showing the menu.
 CurrentNodeID - Used to remember the node we are currently showing the menu for. Used by submenus.
 VisibleMenus - An array containing the currently visible menus.
 */

//Global CONSTANS
EZPOPMNU_OFFSET = 8;

// Global VARS
// CurrentNodeID holds id of current node to edit for submenu's
var CurrentNodeID = -1;
var CurrentObjectID = -1;
// VisibleMenus is an array that holds the names of the currently shown menu's
// for each level.
var VisibleMenus = new Array();


/*!
   Shows toplevel menu at the current mouseposition + EZPOPMNU_OFFSET.
   
 */
function ezpopmnu_showTopLevel( menuID, nodeID, objectID, menuHeader )
{
    if( !document.getElementById( menuID ) )return;

    if ( nodeID >= 0 )
    { 
        CurrentNodeID = nodeID;
        CurrentObjectID = objectID;
    }

    // reposition menu
    document.getElementById( menuID ).style.left = MouseX + EZPOPMNU_OFFSET;
    document.getElementById( menuID ).style.top = MouseY + EZPOPMNU_OFFSET;

    // Do URL replace for all items in that menu
    for ( var i in menuArray[menuID]['elements'] )
    {
        var hrefElement = document.getElementById( i );

        // href replacement
        var replaceString = menuArray[menuID]['elements'][i]['url'];
        replaceString = replaceString.replace( '%nodeID%', CurrentNodeID );
	replaceString = replaceString.replace( '%objectID%', CurrentObjectID );
        hrefElement.setAttribute( "href", replaceString );

        // enabled/disabled
        if( typeof( menuArray[menuID]['elements'][i]['disabled_class'] ) != 'undefined' &&
            menuArray[menuID]['elements'][i]['disabled_for'][CurrentNodeID] == 'yes' )
        {
            hrefElement.className = menuArray[menuID]['elements'][i]['disabled_class'];
        }
    }

    // set header
    if( menuHeader && typeof( menuArray[menuID]['headerID'] ) != 'undefined' )
    {
        var header = document.getElementById( menuArray[menuID]['headerID'] );
        if( header ) header.innerHTML = menuHeader;
    }

    // make menu visible
    var styleObject = ezjslib_getStyleObject( menuID, document );
    if( styleObject ) styleObject.visibility = 'visible';
    VisibleMenus[menuArray[menuID]['depth']] = menuID;
}

/*!
  Show sublevel menu. The current nodeid is remembered from the last call to
  ezpopmnu_showTopLevel()
 */
function ezpopmnu_showSubLevel( menuName )
{
    ezpopmnu_showTopLevel( menuName, -1 );
}

/*!
  Submit the form with id formID. All fields of type hidden will have the texts %nodeID%
  and %objectID% replaced with the corresponding real values.
*/
function ezpopmnu_submitForm( formID )
{
    var formElement = document.getElementById( formID );
    if( formElement )
    {
        // for all children do replacement
        var children = formElement.childNodes; 
        for( var i = 0; i < children.length; i++) 
        {
	    if( children[i].type == 'hidden' )
            {
                children[i].value = children[i].value.replace( '%nodeID%', CurrentNodeID );
                children[i].value = children[i].value.replace( '%objectID%', CurrentObjectID );
            }
        }
        formElement.submit();
    }
}

/*!
  Hide menu id and all menu's beneath it
 */
function ezpopmnu_hide( id )
{
    var level = menuArray[id]['depth'];
    ezpopmnu_hideHigher( level - 1 );
}

/*!
  Hide all menus
*/
function ezpopmnu_hideAll()
{
    ezpopmnu_hideHigher( -1 );
}

/*
 *Hide all menu's above level
 */
function ezpopmnu_hideHigher( level )
{
    for ( var i = level + 1; i < VisibleMenus.length && VisibleMenus[i] != 'none' ; i++ )
    {
        var styleObject = ezjslib_getStyleObject( VisibleMenus[i], document );
        if( styleObject ) styleObject.visibility = 'hidden';
        VisibleMenus[i] = 'none';
    }
}

/*
 * This method should be called by mouseover for all items in the implementation.
 * The method makes sure that no menus on a lower level are shown.
 */
function ezpopmnu_mouseOver( id )
{
    ezpopmnu_hideHigher( menuArray[id]['depth'] );
}
