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

/*! \file ezjslibmousetracker.js 
*/


/*!
  \brief
  This library contains a mouse tracker. Simply include it and the current mouse
  position will be in MouseX and MouseY.
*/

// Global VARS
var MouseX = 0; // Track mouse position
var MouseY = 0;


/**
 * mouseHandler is called each time the mouse is moved within the document. We use the
 * mouse position to popup the menus where the mouse is located.
 */
function ezjslib_mouseHandler( e )
{
    if ( document.all ) // IE needs special treatment
    {
        MouseX = window.event.x + document.body.scrollLeft;
        MouseY = window.event.y + document.body.scrollTop;
    }
    else
    {
        MouseX = e.clientX; //+ document.body.scrollLeft;
        MouseY = e.clientY; //+ document.body.scrollTop;
    }
}

// install the mouse handler
document.onmousemove = ezjslib_mouseHandler;
if ( document.captureEvents ) document.captureEvents( Event.MOUSEMOVE ); // NN4