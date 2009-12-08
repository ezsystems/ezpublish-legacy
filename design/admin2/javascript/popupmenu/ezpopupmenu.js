//
// Created on: <1-Aug-2002 16:45:00 fh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  - Supports context sensitivity for eZ Publish through:
     - String substitution in the href attribute of menuitems.
     - Form submital with string substitution.

  Currenty not supported but possible if requested:
  - Dynamic building of menus based on eZ Publish content.

 Public interface:
ezpopmenu_showTopLevel - This method opens a new top-level popupmenu.
ezpopmenu_showSubLevel - This menu opens a sublevel popupmenu. It does not hide any parent menus.
ezpopmenu_submitForm - This method is used to activate a form.
ezpopmenu_mouseOver - This method should be called by all menuitems when a mouseover event occurs. It currently hides and submenus that should not be shown.
ez_createAArray - Helper method to create associative arrays. It takes an array with an even number of elements and makes a associate element out of every two array elements.

In order to use the popupmenu javascript you need to provide:
1. The HTML/CSS structure for you menu(s)
2. The menuArray javascript array containing the configuration of your popupmenu(s).

eZ Publish provides a default template for this purpose. It is located in popupmenu/popup_menu.tpl. You are encouraged to override this file in the siteacess where you want to use the menu.

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
 menuArray['mainmenu'] = {};
 menuArray['mainmenu']['depth'] = 0;
 menuArray['mainmenu']['elements'] = {};
 menuArray['mainmenu']['elements']['menu-substitute'] = new Array();
 menuArray['mainmenu']['elements']['menu-substitute']['url'] = {'/content/view/%nodeID%'|ezurl};

 <!-- submenu -->
 menuArray['submenu'] = {};
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

// Global vars used in templates
var CurrentSubstituteValues = -1;

// Create scope to avoid filling global scope more then needed
(function()
{
//POPUPMENU CONSTANTS
var EZPOPMENU_OFFSET = 8, EZPOPMENU_SUBOFFSET = 4, EZPOPMENU_SUBTOPOFFSET = 4;

// POPUPMENU VARS
var CurrentDisableIDList = [];
// Which Menu should be disabled
var CurrentDisableMenuID = -1;

var CurrentDisabledMenusItems = {}, DefaultDisabledMenuItemCSSClass = 'menu-item-disabled';;
// VisibleMenus is an array that holds the names of the currently visible menus
var VisibleMenus = [];

/*!
  Controls the popup offsets of the menu relative to the mouse position.
  Default values are offsetX = 8 and offsetY = 4.
 */
function _initOffsets( offsetX, offsetY )//not
{
    EZPOPMENU_OFFSET = offsetX;
    EZPOPMENU_SUBTOPOFFSET = offsetY;
}

/*!
 Sets an element of the substitute array.
 This function can be used if you want to change some substitution values dynamically,
 E.g based on the element you chose in the menu.
*/
function _setSubstituteValue( key, value )//not
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
function _showTopLevel( event, menuID, substituteValues, menuHeader, disableIDList, disableMenuID )
{
    if( !document.getElementById( menuID ) ) return;
    var mousePos = _mouseHandler( event ); // register new mouse position

    if ( substituteValues != -1 ) // new topmenu
    {
        _hideAll();
        CurrentSubstituteValues = substituteValues;
    }

    if( disableIDList != -1 )
    {
    	CurrentDisableIDList = disableIDList.push !== undefined ? disableIDList : [disableIDList];
    }

    CurrentDisableMenuID = disableMenuID;

    _doItemSubstitution( menuID, menuHeader );

    // make menu visible
    _moveTopLevelOnScreen( menuID, mousePos );
    _makeVisible( menuID );
}

/*!
  Show sublevel menu. The current substitute values are remembered from the last call to
  ezpopmenu_showTopLevel().
  Params:
  event - Just pass the event causing the script to popup.
  menuName - The name of the menu to popup
  overItem - The id of the item that caused the popup. This is used to reposition the menu correctly.
 */
function _showSubLevel( event, menuID, overItem )
{
    if( !document.getElementById( menuID ) ) return;
    //var mousePos = _mouseHandler( event ); // register new mouse position
    //    ezpopmenu_showTopLevel( event, menuName, -1 );
    _doItemSubstitution( menuID );

    _hideHigher( menuArray[menuID]['depth'] - 1 ); //hide all other submenus

    // make menu visible
    _moveSubLevelOnScreen( menuID, overItem );
    _makeVisible( menuID );
}

/*!
  Makes a window visible for the user.
  This method also sets the necessary variables in order to make the menu
  disappear when appropriate.
 */
function _makeVisible( menuID )//not
{
    var el = document.getElementById( menuID );
    if( el ) el.style.visibility = 'visible';
    VisibleMenus[menuArray[menuID]['depth']] = menuID;

    document.getElementById( menuID ).onmouseover = function() { document.onmousedown = null; }
    document.getElementById( menuID ).onmouseout = function() { document.onmousedown = _hideAll; }
    document.onmousedown = _hideAll;
}

/*!
  Substitute the values of the items in the menu with the items given to the first
  showTopLEvel call.
 */
function _doItemSubstitution( menuID, menuHeader )//not
{
    // Do URL replace for all items in that menu
    for ( var i in menuArray[menuID]['elements'] )
    {
        var hrefElement = document.getElementById( i );

        if ( !hrefElement )
        {
            continue;
        }

        // href replacement
        var replaceString = menuArray[menuID]['elements'][i]['url'];

        if ( replaceString )
        {
            replaceString = _substituteString( replaceString, CurrentSubstituteValues );
            hrefElement.setAttribute( "href", replaceString );
        }

        // dynamic generation
        var loopingVariable = menuArray[menuID]['elements'][i]['variable'];

        if ( loopingVariable )
        {
            var content = '';

            for ( var localVariableIndex in CurrentSubstituteValues[loopingVariable] )
            {
                var localVariable = CurrentSubstituteValues[loopingVariable][localVariableIndex];
                if ( typeof localVariable != 'object' )
                    continue;

                var partialContent = menuArray[menuID]['elements'][i]['content'];
                for ( var substItem in CurrentSubstituteValues )
                {
                    if ( typeof CurrentSubstituteValues[substItem] != 'object' && typeof CurrentSubstituteValues[substItem] != 'function' )
                    {
                        partialContent = partialContent.replace( substItem, CurrentSubstituteValues[substItem] );
                    }
                }
                for ( var localItem in localVariable )
                {
                    partialContent = partialContent.replace( '%' + localItem + '%', localVariable[localItem] );
                }
                content += partialContent;
            }

            hrefElement.innerHTML = content;
        }

        var disabledForElement = false;
        if ( typeof( menuArray[menuID]['elements'][i]['disabled_for'] ) != 'undefined' && CurrentDisableIDList )
        {
        	for ( var disI = 0, disL = CurrentDisableIDList.length; disI < disL; disI++ )
        	{
        		if ( disabledForElement = menuArray[menuID]['elements'][i]['disabled_for'][ CurrentDisableIDList[disI] ] === 'yes'  )
        			break;
        	}
        }

        if ( typeof( menuArray[menuID]['elements'][i]['disabled_class'] ) != 'undefined' &&
              ( disabledForElement ||
              ( CurrentDisableMenuID && hrefElement.id == CurrentDisableMenuID ) ) )
        {
            CurrentDisabledMenusItems[hrefElement.id] = new Array();
            CurrentDisabledMenusItems[hrefElement.id]['className'] = hrefElement.className;
            CurrentDisabledMenusItems[hrefElement.id]['href'] = hrefElement.href;
            CurrentDisabledMenusItems[hrefElement.id]['onmouseover'] = hrefElement.onmouseover;

            hrefElement.className = menuArray[menuID]['elements'][i]['disabled_class'];
            hrefElement.setAttribute( "href", '#' );
            hrefElement.onmouseover = "";

        }
        else if ( typeof( menuArray[menuID]['elements'][i]['disabled_class'] ) != 'undefined' &&
                  hrefElement.className == menuArray[menuID]['elements'][i]['disabled_class'] )
        {
            // Restore(enable) menu item
            if ( typeof( CurrentDisabledMenusItems[hrefElement.id] ) != 'undefined' )
            {
                hrefElement.className = CurrentDisabledMenusItems[hrefElement.id]['className'];
                hrefElement.href = CurrentDisabledMenusItems[hrefElement.id]['href'];
                hrefElement.onmouseover = CurrentDisabledMenusItems[hrefElement.id]['onmouseover'];
            }
        }
    }

    // set header
    if ( menuHeader && typeof( menuArray[menuID]['headerID'] ) != 'undefined' )
    {
        var header = document.getElementById( menuArray[menuID]['headerID'] );
        if ( header ) header.innerHTML = menuHeader;
    }
}

function _substituteString( replaceString, substituteValues )//not
{
    // loop though substitute values and substitute for each of them
    for ( var substItem in substituteValues )
    {
        if ( typeof substituteValues[substItem] != 'object' && typeof substituteValues[substItem] != 'function' )
        {
            replaceString = replaceString.replace( substItem, substituteValues[substItem] );
        }
    }

    return replaceString;
}

/*!
  Reposition a toplevel menu according to the mouse position.
  Makes sure the complete menu is visible in the viewing area.
  The menu is repositioned like most OS's do if it doesn't fit at the normal position: is moved
  to the opposite side of the mouse pointer/menu.
*/
function _moveTopLevelOnScreen( menuID, mousePos )//not
{
    var menuElement = document.getElementById( menuID ), screenData = _getScreenProperties();
    var newX = 0; var newY = 0;

    // compensate if we are below the screen
    if( (screenData.ScrollY + screenData.Height) < ( mousePos.y + EZPOPMENU_OFFSET + menuElement.offsetHeight ) )
        newY = mousePos.y - EZPOPMENU_OFFSET - menuElement.offsetHeight;
    // compensate if we are above the top of the screen
    else if( screenData.ScrollY > EZPOPMENU_OFFSET + mousePos.y )
        newY = screenData.ScrollY;
    else
        newY = mousePos.y + EZPOPMENU_OFFSET;

    // compensate if we are to the right of the screen
    if( (screenData.ScrollX + screenData.Width) < ( mousePos.x + EZPOPMENU_OFFSET + menuElement.offsetWidth ) )
        newX = mousePos.x - EZPOPMENU_OFFSET - menuElement.offsetWidth;
    // compensate if we are to the left
    else if( screenData.ScrollX > EZPOPMENU_OFFSET + mousePos.x )
        newX = screenData.ScrollX;
    else
        newX = mousePos.x + EZPOPMENU_OFFSET;
    // reposition menu
    menuElement.style.left = newX + "px";
    menuElement.style.top = newY + "px";
}

function _mouseHandler( e )
{
    if ( !e )e = window.event;

    if( e.pageX || e.pageY )//DOM
        return { 'x': e.pageX, 'y': e.pageY };
    else if ( e.clientX || e.clientY ) // IE needs special treatment
        return { 'x': e.clientX + document.documentElement.scrollLeft, 'y': e.clientY + document.documentElement.scrollTop };
    return { 'x': 0, 'y': 0 };
}


/*!
  Reposition a toplevel menu according to parent window.
  Makes sure the complete menu is visible in the viewing area.
  The menu is repositioned like most OS's do if it doesn't fit at the normal position: is moved
  to the opposite side of the mouse pointer/menu.
  TODO: If you have several submenus we should store any side adjustment in order to
  always adjust to the same side
*/
function _moveSubLevelOnScreen( menuID, alignItem )//not
{
    var menuElement = document.getElementById( menuID ), screenData = _getScreenProperties();
    var newX = 0; var newY = 0;

    alignElement = document.getElementById( alignItem );
    parentElement = document.getElementById( VisibleMenus[menuArray[menuID]['depth'] - 1] );

    if( alignElement && parentElement )
    {
        newX = parseInt( parentElement.style.left ) + menuElement.offsetWidth - EZPOPMENU_SUBOFFSET;
        newY = parseInt( parentElement.style.top ) + alignElement.offsetTop + EZPOPMENU_SUBTOPOFFSET;
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
        newX = parseInt( parentElement.style.left ) + EZPOPMENU_SUBOFFSET - menuElement.offsetWidth;
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
function _submitForm( formID, customSubstitute )
{
    var formElement = document.getElementById( formID );
    if( formElement )
    {
        // for all children do replacement
        var children = formElement.childNodes;
        for( var i = 0, l = children.length; i < l; i++)
        {
            if( children[i].type == 'hidden' )
            {
                for ( var substItem in CurrentSubstituteValues )
                {
                    children[i].value = children[i].value.replace( substItem, CurrentSubstituteValues[substItem] );
                    if ( customSubstitute )
                    {
                        for( var j = 0, jl = customSubstitute.length; j < jl; j += 2 )
                        {
                            children[i].value = children[i].value.replace( '%'+customSubstitute[j]+'%', customSubstitute[j+1] );
                        }
                    }
                }
            }
        }

        formElement.submit();
    }
}

/*!
  Hide menu id and all menu's beneath it
 */
function _hide( id )
{
    var level = menuArray[id]['depth'];
    _hideHigher( level - 1 );
}

/*!
  Hide all menus
*/
function _hideAll()
{
    document.onmousedown = null;
    _hideHigher( -1 );
}

/*
 * Hide all menus above 'level'
 */
function _hideHigher( level, el )//not
{
    for ( var i = level + 1, l = VisibleMenus.length; i < l && VisibleMenus[i] != 'none' ; i++ )
    {
        el = document.getElementById( VisibleMenus[i] );
        if( el ) el.style.visibility = 'hidden';
        VisibleMenus[i] = 'none';
    }
}

/*
 * This method should be called by mouseover for all items in the implementation.
 * The method makes sure that no menus on a lower level are shown.
 */
function _mouseOver( id )
{
	_hideHigher( menuArray[id]['depth'] );
}

/*
 * Helper function to create an associative object. Every two items will be paired as a key and a value.
 */
function _createAArray( flat )
{
    var resultArray = {};

    if( flat.length % 2 != 0 ) return resultArray;

    for ( var i = 0, l = flat.length; i < l; i += 2 )
    {
        resultArray[flat[i]] = flat[i+1];
    }

    return resultArray;
}

/*
 * Perform subsitution in 'href' and redirect browser to
 * newly created link.
 */
function _SubstituteAndRedirect( href )
{
    // loop though substitute values and substitute for each of them
    for ( var substItem in CurrentSubstituteValues )
    {
        href = href.replace( substItem, CurrentSubstituteValues[substItem] );
    }
    location.href = href;
}

function _getScreenProperties()
{
  // client width and height
  var result = { 'ScrollX': 0, 'ScrollY': 0, 'Height': 0, 'Width': 0 };
  if( typeof( window.innerWidth ) == 'number' )// all but IE
  {
    result.Width = window.innerWidth;
    result.Height = window.innerHeight;
  }
  else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )// IE 6
  {
    result.Width = document.documentElement.clientWidth;
    result.Height = document.documentElement.clientHeight;
  }
  else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) )// IE 4
  {
    result.Width = document.body.clientWidth;
    result.Height = document.body.clientHeight;
  }

  if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) )// DOM
  {
    result.ScrollY = document.body.scrollTop;
    result.ScrollX = document.body.scrollLeft;
  }
  else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) )// IE6
  {
    result.ScrollY = document.documentElement.scrollTop;
    result.ScrollX = document.documentElement.scrollLeft;
  }
  else if( typeof( window.pageYOffset ) == 'number' )// Netscape compliant
  {
    result.ScrollY = window.pageYOffset;
    result.ScrollX = window.pageXOffset;
  }

  return result;
}

// Register some functions on global scope
window.ezpopup_SubstituteAndRedirect = _SubstituteAndRedirect;
window.ez_createAArray = _createAArray;
window.ezpopmenu_mouseOver = _mouseOver;
window.ezpopmenu_showTopLevel = _showTopLevel;
window.ezpopmenu_hideAll = _hideAll;
window.ezpopmenu_hide = _hide;
window.ezpopmenu_submitForm = _submitForm;
window.ezpopmenu_showSubLevel = _showSubLevel;

})();// Scope end
