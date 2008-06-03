<?php
//
// Definition of eZTextTool class
//
// Created on: <04-Jun-2002 09:12:36 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*!
  \class eZTextTool eztexttool.php
  \ingroup eZUtils
  \brief eZTextTool is a class with different useful text functions

*/

class eZTextTool
{
    /*!
     \static
     Returns an HTML highlighted and displayable formatted HTML from the
     input text. < and > are converted to &lt; and &gt;
    */
    function highlightHTML( $input )
    {
        $input = str_replace( "<", "&lt;", $input );
        $input = str_replace( ">", "&gt;", $input );

        $input = preg_replace( "#&lt;(.*?)&gt;#m", "<font color='red'>&lt;$1&gt;</font>", $input );

        return $input;
    }

    function highlightPHP()
    {

    }

    function concatDelimited()
    {
        $numargs = func_num_args();
        $argList = func_get_args();
        $text = null;
        if ( $numargs > 1 )
        {
            $delimit = $argList[0];
            $text = implode( $delimit, eZTextTool::arrayFlatten( array_splice( $argList, 1 ) ) );
        }
        return $text;
    }

    function concat()
    {
        $numargs = func_num_args();
        $argList = func_get_args();
        $text = null;
        if ( $numargs > 0 )
        {
            $text = implode( '', eZTextTool::arrayFlatten( $argList ) );
        }
        return $text;
    }

    function arrayFlatten( $array )
    {
        $flatArray = array();
        $expandItems = $array;
        $done = false;
        while ( !$done )
        {
            $checkList = $expandItems;
            $leftOvers = array();
            $foundArray = false;
            foreach ( array_keys( $checkList ) as $key )
            {
                $item = $checkList[$key];
                if ( is_array ( $item ) )
                {
                    $leftOvers = array_merge( $leftOvers, $item );
                    $foundArray = true;
                }
                else
                {
                    if ( $foundArray )
                        $leftOvers[] = $item;
                    else
                        $flatArray[] = $item;
                }
            }
            $expandItems = $leftOvers;
            if ( count( $expandItems ) == 0 )
            {
                $done = true;
            }
        }
        return $flatArray;
    }
}
?>
