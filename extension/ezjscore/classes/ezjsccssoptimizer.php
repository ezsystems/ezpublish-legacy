<?php
//
// Definition of ezjscCssOptimizer class
//
// Created on: <26-Sep-2011 00:00:00 dj>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2013 eZ Systems AS
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

class ezjscCssOptimizer
{
    /**
     * 'compress' css code by removing whitespace
     *
     * @param string $css Concated Css string
     * @param int $packLevel Level of packing, values: 2-3
     * @return string
     */
    public static function optimize( $css, $packLevel = 2 )
    {
        // Normalize line feeds
        $css = str_replace( array( "\r\n", "\r" ), "\n", $css );

        // Remove multiline comments
        $css = preg_replace( '!(?:\n|\s|^)/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
        $css = preg_replace( '!(?:;)/\*[^*]*\*+([^/][^*]*\*+)*/!', ';', $css );

        // Remove whitespace from start and end of line + multiple linefeeds
        $css = preg_replace( array( '/\n\s+/', '/\s+\n/', '/\n+/' ), "\n", $css );

        if ( $packLevel > 2 )
        {
            // Remove space around ':' and ','
            $css = preg_replace( array( '/:\s+/', '/\s+:/' ), ':', $css );
            $css = preg_replace( array( '/,\s+/', '/\s+,/' ), ',', $css );

            // Remove unnecessary line breaks...
            $css = str_replace( array( ";\n", '; ' ), ';', $css );
            $css = str_replace( array( "}\n", "\n}", ';}' ), '}', $css );
            $css = str_replace( array( "{\n", "\n{", '{;' ), '{', $css );
            // ... and spaces as well
            $css = str_replace(array('\s{\s', '\s{', '{\s' ), '{', $css );
            $css = str_replace(array('\s}\s', '\s}', '}\s' ), '}', $css );

            // Optimize css
            $css = str_replace( array( ' 0em', ' 0px', ' 0pt', ' 0pc' ), ' 0', $css );
            $css = str_replace( array( ':0em', ':0px', ':0pt', ':0pc' ), ':0', $css );
            $css = str_replace( ' 0 0 0 0;', ' 0;', $css );
            $css = str_replace( ':0 0 0 0;', ':0;', $css );

            // Optimize hex colors from #bbbbbb to #bbb
            $css = preg_replace( "/color:#([0-9a-fA-F])\\1([0-9a-fA-F])\\2([0-9a-fA-F])\\3/", "color:#\\1\\2\\3", $css );
        }
        return $css;
    }
}
?>
