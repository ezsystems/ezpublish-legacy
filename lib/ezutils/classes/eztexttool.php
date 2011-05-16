<?php
/**
 * File containing the eZTextTool class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

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
