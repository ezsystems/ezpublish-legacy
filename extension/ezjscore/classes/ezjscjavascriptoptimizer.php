<?php
//
// Definition of ezjscJavascriptOptimizer class
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

class ezjscJavascriptOptimizer
{
    /**
     * 'compress' javascript code by removing whitespace
     *
     * @param string $script Concated JavaScript string
     * @param int $packLevel Level of packing, values: 2-3
     * @return string
     */
    public static function optimize( $script, $packLevel = 2 )
    {
        // Normalize line feeds
        $script = str_replace( array( "\r\n", "\r" ), "\n", $script );

        // Remove whitespace from start & end of line + singelline comment + multiple linefeeds
        $script = preg_replace( array( '/\n\s+/', '/\s+\n/', '#\n\s*//.*#', '/\n+/' ), "\n", $script );

        // Remove multiline comments
        $script = preg_replace( '!(?:\n|\s|^)/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $script );
        $script = preg_replace( '!(?:;)/\*[^*]*\*+([^/][^*]*\*+)*/!', ';', $script );

        return $script;
    }
}
?>
