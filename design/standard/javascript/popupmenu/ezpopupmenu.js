//
// Created on: <1-Aug-2002 16:45:00 fh>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
  This javascript provides a very cool and flexible DHTML popupmenu.
  In order to use this javascript you must also include ezlibdomsupport.js.


  Features:
  - Multilevel popupmenu
  - Supports all major browsers
  - All HTML code in template
  - Supports context sensitivity for eZ publish through:
     - String substitution in the href attribute of menuitems.
     - Form submital with string substitution.

  Currenty not supported but possible if requested:
  - Dynamic building of menus based on eZ publish content.

 Public interface:
ezpopmenu_showTopLevel - This method opens a new top-level popupmenu.
ezpopmenu_showSubLevel - This menu opens a sublevel popupmenu. It does not hide any parent menus.
ezpopmenu_submitForm - This method is used to activate a form.
ezpopmenu_mouseOver - This method should be called by all menuitems when a mouseover event occurs. It currently hides and submenus that should not be shown.
ez_createAArray - Helper method to create associative arrays. It takes an array with an even number of elements and makes a associate element out of every two array elements.

In order to use the popupmenu javascript you need to provide:
1. The HTML/CSS structure for you menu(s)
2. The menuArray javascript array containing the configuration of your popupmenu(s).

eZ publish provides a default template for this purpose. It is located in popupmenu/popup_menu.tpl. You are encouraged to override this file in the siteacess where you want to use the menu.

 1. Setting up the HTML/CSS structure for your popup menu(s).
 Your menu should be set up completely with all elements. The following requirements apply:
- The outer element should be a "div".
- The css of the outer div must have the following CSS attributes set:
  position: absolute;
  z-index: +1;
  visibility: hidden;
- The menuitems must be of type "a".
- Both the menu and the menuitems must be given and "id". This id is used in the menuArray to set up that menu/item.
- Each menuitem must call ezpopmenu_mouseOver or one of the methods spawning a metnu on the mouseover event. The name of the enclosing menu must be given as parameter.
- The menus should be defined in the order they will be shown. In other words, the mainmenu first and then any submenus. This is to ensure the correct visible stack order.

An example example with a menu and a submenu.

 example with a main menu and a submenu:
 -------------------------------------------------------------------------------
<!-- The main menu -->
 <div class="menu" id="mainmenu">
  <a id="menu-main" href="http://www.ez.no" onmouseover="ezpopmenu_mouseOver( 'mainmenu' )" >ez.no</a>
  <a id="menu-substitute" href="#" onmouseover="ezpopmenu_mouseOver( 'mainmenu' )" >Dynamic node view</a>
  <a id="menu-spawn" href="#" onmouseover="ezpopmenu_showSubLevel( 'submenu', 'menu-spawn' )" >Show submenu</a>
 </div>
<!-- The submenu -->
 <div class="menu" id="submenu">
  <a id="submenu-item" href="#" onmouseover="ezpopmenu_submitForm( 'myform' )">Form submitter</a>
 </div>
--------------------------------------------------------------------------------


 2. Setting up the menuArray array containing the popupmenu configuration.
 The menuArray is a multilevel array describing the features of your menus. The structure of the
 menuArray array is flat. This means that each menu is described in the toplevel array.
 Each menu can have the following properties set:
  - "depth" [required]: The depth of the menu. Toplevel menus have depth 0. Menus appearing from a toplevel menu
    have depth 1 etc.
  - "elements": The elements property must be yet another array containing all the menuitems that require string substitution.
    Each item can contain the following properties:
      + "url": The URL that this element should point at. Put the part that should be substituted within "%" symbols.

The following example configures the menu created in step 1.
--------------------------------------------------------------------------------
<script language="JavaScript1.2" type="text/javascript">

 var menuArray = new Array();
 <!-- main menu -->
 menuArray['mainmenu'] = new Array();
 menuArray['mainmenu']['depth'] = 0;
 menuArray['mainmenu']['elements'] = new Array();
 menuArray['mainmenu']['elements']['menu-substitute'] = new Array();
 menuArray['mainmenu']['elements']['menu-substitute']['url'] = {'/content/view/%nodeID%'|ezurl};

 <!-- submenu -->
 menuArray['submenu'] = new Array();
 menuArray['submenu']['depth'] = 1; // this is a first level submenu of ContextMenu

</script>

 <!-- The form submitted by the submenu -->
 {* Remove a node. *}
<form id="myform" method="post" action="someactionhere">
  <!-- Notice that there is autoamatic string translation for the contents of value -->
  <input type="hidden" name="example" value="%nodeID%" />
</form>

--------------------------------------------------------------------------------


 Finally you will need to activate the "mainmenu" menu somehow. This is achieved through a call to ezpopmenu_showToplevel. In the eaxample case links in the setup array containing the string %nodeID% will be substituted by 42.

 example:
 <a onmouseclick="ezpopmenu_showTopLevel( event, 'ContextMenu', ez_createAArray( array( %nodeID%, 42) ) ); return false;">show</a><br />


#################### Developer info ##############################
 This script defines the following global constants/variables:
 EZPOPMENU_OFFSET - Added to the x,y position of the mouse when showing the menu.
 CurrentNodeID - Used to remember the node we are currently showing the menu for. Used by submenus.
 VisibleMenus - An array containing the currently visible menus.
 */

//Global CONSTANTS
var EZPOPMENU_OFFSET = 8;
var EZPOPMENU_SUBTOPOFFSET = 4;

// Global VARS
// CurrentNodeID holds id of current node to edit for submenu's
var CurrentSubstituteValues = -1;
var CurrentDisableID = -1;
// VisibleMenus is an array that holds the names of the currently visible menus
var VisibleMenus = new Array();

/*!
  Controls the popup offsets of the menu relative to the mouse position.
  Default values are offsetX = 8 and offsetY = 4.
 */
function ezpopmenu_initOffsets( offsetX, offsetY )
{
    EZPOPMENU_OFFSET = offsetX;
    EZPOPMENU_SUBTOPOFFSET = offsetY;
}

/*!
 Sets an element of the substitute array.
 This function can be used if you want to change some substitution values dynamically,
 E.g based on the element you chose in the menu.
*/
function ezpopupmenu_setSubstituteValue( key, value )
{
  if( CurrentSubstituteValues != -1 )
  {
    CurrentSubstituteValues[key] = value;
  }
}

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

    ezpopmenu_doItemSubstitution( menuID, menuHeader );

    // make menu visible
    ezpopmenu_moveTopLevelOnScreen( menuID );
    ezpopmenu_makeVisible( menuID );
}

/*!
  Show sublevel menu. The current substitute values are remembered from the last call to
  ezpopmenu_showTopLevel().
  Params:
  event - Just pass the event causing the script to popup.
  menuName - The name of the menu to popup
  overItem - The id of the item that caused the popup. This is used to reposition the menu correctly.
 */
function ezpopmenu_showSubLevel( event, menuID, overItem )
{
    if( !document.getElementById( menuID ) ) return;
    ezjslib_mouseHandler( event ); // register new mouse position
    //    ezpopmenu_showTopLevel( event, menuName, -1 );
    ezpopmenu_doItemSubstitution( menuID );

    // make menu visible
    ezpopmenu_moveSubLevelOnScreen( menuID, overItem );
    ezpopmenu_makeVisible( menuID );
}

/*!
  Makes a window visible for the user.
  This method also sets the necessary variables in order to make the menu
  disappear when appropriate.
 */
function ezpopmenu_makeVisible( menuID )
{
    var styleObject = ezjslib_getStyleObject( menuID, document );
    if( styleObject ) styleObject.visibility = 'visible';
    VisibleMenus[menuArray[menuID]['depth']] = menuID;

    document.getElementById( menuID ).onmouseover = function() { document.onmousedown = null; }
    document.getElementById( menuID ).onmouseout = function() { document.onmousedown = ezpopmenu_hideAll; }
    document.onmousedown = ezpopmenu_hideAll;
}

/*!
  Substitute the values of the items in the menu with the items given to the first
  showTopLEvel call.
 */
function ezpopmenu_doItemSubstitution( menuID, menuHeader )
{
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
    if ( menuHeader && typeof( menuArray[menuID]['headerID'] ) != 'undefined' )
    {
        var header = document.getElementById( menuArray[menuID]['headerID'] );
        if ( header ) header.innerHTML = menuHeader;
    }
}

/*!
  Reposition a toplevel menu according to the mouse position.
  Makes sure the complete menu is visible in the viewing area.
  The menu is repositioned like most OS's do if it doesn't fit at the normal position: is moved
  to the opposite side of the mouse pointer/menu.
*/
function ezpopmenu_moveTopLevelOnScreen( menuID )
{
    menuElement = document.getElementById( menuID );
    screenData = ezjslib_getScreenProperties();
    var newX = 0; var newY = 0;

    // compensate if we are below the screen
    if( (screenData.ScrollY + screenData.Height) < ( MouseY + EZPOPMENU_OFFSET + menuElement.offsetHeight ) )
        newY = MouseY - EZPOPMENU_OFFSET - menuElement.offsetHeight;
    // compensate if we are above the top of the screen
    else if( screenData.ScrollY > EZPOPMENU_OFFSET + MouseY )
        newY = screenData.ScrollY;
    else
        newY = MouseY + EZPOPMENU_OFFSET;

    // compensate if we are to the right of the screen
    if( (screenData.ScrollX + screenData.Width) < ( MouseX + EZPOPMENU_OFFSET + menuElement.offsetWidth ) )
        newX = MouseX - EZPOPMENU_OFFSET - menuElement.offsetWidth;
    // compensate if we are to the left
    else if( screenData.ScrollX > EZPOPMENU_OFFSET + MouseX )
        newX = screenData.ScrollX;
    else
        newX = MouseX + EZPOPMENU_OFFSET;
    // reposition menu
    menuElement.style.left = newX + "px";
    menuElement.style.top = newY + "px";
}


/*!
  Reposition a toplevel menu according to parent window.
  Makes sure the complete menu is visible in the viewing area.
  The menu is repositioned like most OS's do if it doesn't fit at the normal position: is moved
  to the opposite side of the mouse pointer/menu.
  TODO: If you have several submenus we should store any side adjustment in order to
  always adjust to the same side
*/
function ezpopmenu_moveSubLevelOnScreen( menuID, alignItem )
{
    menuElement = document.getElementById( menuID );
    screenData = ezjslib_getScreenProperties();
    var newX = 0; var newY = 0;

    alignElement = document.getElementById( alignItem );
    parentElement = document.getElementById( VisibleMenus[menuArray[menuID]['depth'] - 1] );

    if( alignElement && parentElement )
    {
        newX = parseInt( parentElement.style.left ) + menuElement.offsetWidth - EZPOPMENU_OFFSET;
        newY = parseInt( parentElement.style.top ) + alignElement.offsetTop + EZPOPMENU_SUBTOPOFFSET;;
    }
    // compensate if we are below the screen
    if( ( screenData.ScrollY + screenData.Height ) < ( newY + menuElement.offsetHeight ) )
        newY = screenData.ScrollY + screenData.Height - menuElement.offsetHeight;
    // compensate if above the screen
    else if( screenData.ScrollY > newY )
        newY = screenData.ScrollY;

    // compensate if we are to the right of the screen
    if( ( screenData.ScrollX + screenData.Width ) < ( newX + menuElement.offsetWidth ) )
    {
        newX = parseInt( parentElement.style.left ) + EZPOPMENU_OFFSET - menuElement.offsetWidth;
    }
    // to the left is impossible

    // reposition menu
    menuElement.style.left = newX + "px";
    menuElement.style.top = newY + "px";
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
    for ( var i = 0; i <= flat.length; i += 2 )
        resultArray[flat[i]] = flat[i+1];

   return resultArray;
}

/*
 * Perform subsitution in 'href' and redirect browser to
 * newly created link.
 */
function ezpopup_SubstituteAndRedirect( href )
{
    // loop though substitute values and substitute for each of them
    for ( var substItem in CurrentSubstituteValues )
    {
        href = href.replace( substItem, CurrentSubstituteValues[substItem] );
    }
    location.href = href;
}

