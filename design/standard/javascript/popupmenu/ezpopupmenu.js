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
 Also each item in the menu must have its onmouseover activate the ezpopmenu_mouseOver method with the name of the menu
 as parameter.
 To open a submenu use the ezpopmenu_ShowSubLevel method. It takes the name of the menu as parameter.

 example with a main menu and a submenu:
 <div class="menu" id="ContextMenu">
  <a id="menu-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">Edit</a>
  <a id="menu-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">View</a>
  <hr />
  <a id="menu-new" href="#" onmouseover="ezpopmenu_showSubLevel( 'submenu' )" >Create new</a>
 </div>
 The submenu basically works the same way.
 <div class="menu" id="submenu">
  <a id="menu-new-article" href="#" onmouseover="ezpopmenu_mouseOver( 'submenu')">Article</a>
  <a id="menu-new-folder" href="#" onmouseover="ezpopmenu_mouseOver( 'submenu')">Folder</a>
 </div>

 To activate the first menu call the method ezpopmenu_showTopLevel. It takes the name of the menu
 and the node id as parameter.

 example:
 <a href="javascript: ezpopmenu_showTopLevel( 'ContextMenu', 42 )"
   onmouseover="ezpopmenu_showTopLevel( 'ContextMenu', '42' )">show</a><br />


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
 EZPOPMENU_OFFSET - Added to the x,y position of the mouse when showing the menu.
 CurrentNodeID - Used to remember the node we are currently showing the menu for. Used by submenus.
 VisibleMenus - An array containing the currently visible menus.
 */

//Global CONSTANS
EZPOPMENU_OFFSET = 8;

// Global VARS
// CurrentNodeID holds id of current node to edit for submenu's
var CurrentSubstituteValues = -1;
var CurrentDisableID = -1;
// VisibleMenus is an array that holds the names of the currently shown menu's
// for each level.
var VisibleMenus = new Array();


/*!
   Shows toplevel menu at the current mouseposition + EZPOPMENU_OFFSET.
   'event' This parameter is for the mouse event.
   'menuID' is the identification of the menu to use.
   'substituteValues' is an associative array. The string value of each item in the menu will have they keys, substituted by the value in this array.
   'menuHeader' If the menu has a header it is replaced with this value.
   'disableID' If this id is found in the list of known disabled for this menu the item is disabled.
 */
function ezpopmenu_showTopLevel( event, menuID, substituteValues, menuHeader, disableID )
{
    if( !document.getElementById( menuID ) ) return;
    ezjslib_mouseHandler( event ); // register new mouse position

    if ( substituteValues != -1 ) // new topmenu
    { 
	ezpopmenu_hideAll();
	CurrentSubstituteValues = substituteValues;
    }

    if( disableID != -1 )
    {
	CurrentDisableID = disableID;
    }

    // Do URL replace for all items in that menu
    for ( var i in menuArray[menuID]['elements'] )
    {
        var hrefElement = document.getElementById( i );

        // href replacement
        var replaceString = menuArray[menuID]['elements'][i]['url'];
	// loop though substitute values and substitute for each of them
	for ( var substItem in CurrentSubstituteValues )
        {
		replaceString = replaceString.replace( substItem, CurrentSubstituteValues[substItem] );
        }
	hrefElement.setAttribute( "href", replaceString );

        // enabled/disabled
        if( typeof( menuArray[menuID]['elements'][i]['disabled_class'] ) != 'undefined' &&
            menuArray[menuID]['elements'][i]['disabled_for'][CurrentDisableID] == 'yes' )
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
    ezpopmenu_moveOnScreen( menuID );
    var styleObject = ezjslib_getStyleObject( menuID, document );
    if( styleObject ) styleObject.visibility = 'visible';
    VisibleMenus[menuArray[menuID]['depth']] = menuID;

    document.getElementById( menuID ).onmouseover = function() { document.onmousedown = null; }
    document.getElementById( menuID ).onmouseout = function() { document.onmousedown = ezpopmenu_hideAll; }
    document.onmousedown = ezpopmenu_hideAll;
}

/*!
  Makes sure the complete menu is visible in the viewing area.
  The menu is repositioned like most OS's do. If it doesn't fit it is moved
  to the opposite side of the mouse pointer.
*/
function ezpopmenu_moveOnScreen( menuID )
{
    menuElement = document.getElementById( menuID );
    screenData = ezjslib_getScreenProperties();
    newX = 0; newY = 0;
    if( (screenData.ScrollY + screenData.Height) < ( MouseY + EZPOPMENU_OFFSET + menuElement.offsetHeight ) )
	newY = MouseY - EZPOPMENU_OFFSET - menuElement.offsetHeight; // compensate if we are below the screen
    else if( screenData.ScrollY > EZPOPMENU_OFFSET + MouseY )
	 newY = screenData.ScrollY;  // compensate if we are above the top of the screen
    else
	newY = MouseY + EZPOPMENU_OFFSET;
        
    if( (screenData.ScrollX + screenData.Width) < ( MouseX + EZPOPMENU_OFFSET + menuElement.offsetWidth ) )
	newX = MouseX - EZPOPMENU_OFFSET - menuElement.offsetWidth;     // compensate if we are to the right of the screen
    else if( screenData.ScrollX > EZPOPMENU_OFFSET + MouseX )
	 newX = screenData.ScrollX;  // compensate if we are to the left
    else
	newX = MouseX + EZPOPMENU_OFFSET;

    // reposition menu
    menuElement.style.left = newX + "px";
    menuElement.style.top = newY + "px";
}

/*!
  Show sublevel menu. The current nodeid is remembered from the last call to
  ezpopmenu_showTopLevel()
 */
function ezpopmenu_showSubLevel( event, menuName )
{
    ezpopmenu_showTopLevel( event, menuName, -1 );
}

/*!
  Submit the form with id formID. All fields of type hidden will have the texts %nodeID%
  and %objectID% replaced with the corresponding real values.
*/
function ezpopmenu_submitForm( formID )
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
	    	for ( var substItem in CurrentSubstituteValues )
                    children[i].value = children[i].value.replace( substItem, CurrentSubstituteValues[substItem] );
            }
        }
        formElement.submit();
    }
}

/*!
  Hide menu id and all menu's beneath it
 */
function ezpopmenu_hide( id )
{
    var level = menuArray[id]['depth'];
    ezpopmenu_hideHigher( level - 1 );
}

/*!
  Hide all menus
*/
function ezpopmenu_hideAll()
{
    document.onmousedown = null;
    ezpopmenu_hideHigher( -1 );
}

/*
 * Hide all menus above 'level'
 */
function ezpopmenu_hideHigher( level )
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
function ezpopmenu_mouseOver( id )
{
    ezpopmenu_hideHigher( menuArray[id]['depth'] );
}

/*
 * Helper function to create an associative array. Every two items will be paired as a key and a value.
 */
function ez_createAArray( flat )
{
    var resultArray = new Array();
    if( flat.length % 2 != 0 ) return resultArray;
    
    var len = flat.length / 2;
    for ( var i = 0; i <= len; i += 2 )
	resultArray[flat[i]] = flat[i+1];

   return resultArray;
}