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
            
/*! \file ezjslibcookiesupport.js 
*/

/*!    
    \brief
    Functions which works direct with cookie:
        ezjslib_setCookie,
        ezjslib_getCookie,
        ezjslib_setCookieFromArray,
        ezjslib_getCookieToArray.
        
*/


/*!
    Sets cookie with \a name, \a value and \a expires date.
*/
function ezjslib_setCookie( name, value, expires ) 
{
    document.cookie = name + '=' + escape(value) + (( !expires ) ? "" : ('; expires=' + expires.toUTCString())) + '; path=/';
} 

/*!
    \return a value of cookie with name \a name.
*/
function ezjslib_getCookie( name ) 
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
    Converts array \a valueArray to string using as delimiter \a delimiter.
    Resulting string stores in the cookie with name \a name and expire date
    \a expires.
*/
function ezjslib_setCookieFromArray( name, valuesArray, expires, delimiter )
{
    var strCookie = valuesArray.join( delimiter );
    ezjslib_setCookie( name, strCookie, expires );
}

/*!
    Retrieves string from cookie \a name and converts it to array using
    \a delimiter as delimiter.
*/
function ezjslib_getCookieToArray( name, delimiter )
{
    var valuesArray = new Array( 0 );
    var strCookie = ezjslib_getCookie( name );
    if ( strCookie )
    {
        valuesArray = strCookie.split( delimiter );
    }

    return valuesArray;
}

