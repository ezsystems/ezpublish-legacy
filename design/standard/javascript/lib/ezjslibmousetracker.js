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
    if ( !e )
    {
        e = window.event;
    }
    if( e.pageX || e.pageY )
    {
        MouseX = e.pageX;
        MouseY = e.pageY;
    }
    else if ( e.clientX || e.clientY ) // IE needs special treatment
    {
        MouseX = e.clientX + document.documentElement.scrollLeft;
        MouseY = e.clientY + document.documentElement.scrollTop;
    }
}


// Uncomment the following lines if you want to use the mouseHandler function
// for tracing. Note that this can be slow on IE.
//document.onmousemove = ezjslib_mouseHandler;
//if ( document.captureEvents ) document.captureEvents( Event.MOUSEMOVE ); // NN4
