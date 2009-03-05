//
// Created on: <18-Nov-2004 10:54:01 bh>
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
            
/*! \file eziepngfix.js
*/


/*!
  Forces use of DirectX transparency filter for image tags with
  "transparent-png-icon" as class. The result: correct alpha 
  blending for normal (32x32) PNG icons in Internet Explorer.
*/
function useDirectXAlphaBlender()
{
    var images = document.getElementsByTagName( "img" );

    for( var i=0; i<images.length; i++ )
    {
        var image = images[i];
        if( image.className == "transparent-png-icon" )
        {
            image.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + image.src + "', sizingMethod='scale')";
            
            if( image.width == 16 )
            {           
                image.src = emptyIcon16;
            }
            else
            {
                image.src = emptyIcon32;
            }

            image.className = "transparent-png-icon-fixed";
        }
    }
}

window.attachEvent( "onload", useDirectXAlphaBlender );
