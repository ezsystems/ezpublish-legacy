<?php
//
// Definition of eZTextTool class
//
// Created on: <04-Jun-2002 09:12:36 bf>
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
