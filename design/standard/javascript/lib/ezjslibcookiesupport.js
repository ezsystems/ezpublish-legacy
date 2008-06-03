//
// Created on: <14-Jul-2004 14:18:58 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

