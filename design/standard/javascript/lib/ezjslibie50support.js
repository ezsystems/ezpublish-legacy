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
            
/*! \file ezjslibie50support.js 
*/

/*!    
    \brief
    
    Some functions which are not built-in IE 5.0.
    
    Functions: 
        Array.push,
        Array.pop, 
        Array.splice(REMOVE elements only).
*/

if ( !Array.push )
{
    Array.prototype.push=function(i)
    {
        this[this.length] = i;
    }
}

if ( !Array.pop )
{
    Array.prototype.pop=function()
    {
        var last = null;
        if ( this.length > 0 )
        {
            last = this[this.length - 1 ];
            this.length = this.length - 1;
        }
        return last;
    }
}

if ( !Array.splice )
{
    // ONLY REMOVES 
    Array.prototype.splice=function(startIdx, count)
    {
        for ( var i = startIdx; i < this.length - count; i++ )
            this[i] = this[i + count];

        this.length = this.length - count;
    }
}


