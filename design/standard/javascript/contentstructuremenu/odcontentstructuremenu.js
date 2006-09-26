//
// Created on: <20-Jul-2005 10:36:29 ymc-dabe>
//
// Copyright (C) 2005 Young Media Concepts GmbH. All rights reserved.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// If you wish to use this extension under conditions others than the
// GPL you need to contact Young MediaConcepts firtst (licence@ymc.ch).
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ymc.ch if any conditions of this licencing isn't clear to
// you.
//



/*! \file odcontentstructuremenu.js
*/

/*!
    \brief
    This is a set of functions which helps the original content structure
    menu to get elements of its tree on demand.
*/

/*!
    Global variables needed by the on demand content structure menu
*/

var odcsm_standby_message = '<li class="lastli"><span class="openclose"></span><span class="node-name-normal">&nbsp;&nbsp;&nbsp<b>'+ODtextStrings["PleaseWait"]+'</b></span></li>';
var odcsm_expand_element;
var odcsm_expand_element_parent;

function checkXajaxRequest( )
{
    if ( xajax.getRequestObject.readyState != 4 && xajax.getRequestObject.readyState != 0 )
        return true;
    else
        return false;
}


/*!
    Handles opening a node of the tree menu
*/
function ezodcst_onFoldClicked( node, url, ui_context )
{
    var check_node_id = node.getAttribute( "id" );
    var check_node = document.getElementById(check_node_id);
    
    var foldUnfoldAllowed = true;
    
    for ( var i = 0; i < check_node.childNodes.length; ++i )
    {
        if (check_node.childNodes[i].nodeName == "UL" )
        {
            var check_child = check_node.childNodes[i];
            if (check_child.childNodes.length <= 1 )
            {
                if ( xajax.getRequestObject == null )
                {
                    odcsm_expand_element = check_child;
                    odcsm_expand_element_parent = check_node;
                    
                    odcsm_expand_element.innerHTML = odcsm_standby_message;
                    ezcst_foldUnfoldSubtree( odcsm_expand_element_parent, false, true, false, false, true );
                    ezcst_foldUnfold( node, true, false, false, false );
                    
                    url = url.replace(/ajax/, "content");
                    url = url.replace(/call/, "view/full");
//                    url = url.replace(/navigation/, "full");
                    window.location.href = url+'/'+check_node_id.replace(/n/, "");
                    return;
                }
                
                if ( checkXajaxRequest() )
                {
                    odcsm_expand_element = check_child;
                    odcsm_expand_element_parent = check_node;
                    odcsm_expand_element.innerHTML = odcsm_standby_message;
                    ezcst_foldUnfoldSubtree( odcsm_expand_element_parent, false, true, false, false, true );
                    foldUnfoldAllowed=true;
                    xajax_expandTreeNode( check_node_id.replace(/n/, ""), ui_context );
                }
                else
                {
                    foldUnfoldAllowed=false;
                }
            }
        }
    }
    
    if ( foldUnfoldAllowed )
    {
        ezcst_foldUnfold( node, true, false, false, false );
    }
}

