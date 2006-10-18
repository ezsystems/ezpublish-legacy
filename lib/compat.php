<?php
//
// Custom versions of PHP functions for older PHP versions
//
// Created on: <15-Nov-2004 10:05:41 dr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

if ( version_compare( phpversion(), '4.3.10', '<' ) )
{
    if ( !function_exists( 'ezprintf_format_f' ) )
    {
        function ezprintf_format_f()
        {
            $params = func_get_args();
            $decimals = 6;
            if ( count( $params[0] ) == 3 )
            {
                $decimals = $params[0][2];
            }
            return number_format( $GLOBALS['printf_parameter'], $decimals, '.', '' );
        }
    }

    if ( !function_exists( 'ezsprintf' ) )
    {
        function ezsprintf()
        {
            $params = func_get_args();
            preg_match_all( '/%[-+.0-9]*[A-Za-z]/', $params[0], $m );
            $format_strings = $m[0];
            foreach ( $format_strings as $id => $fmt )
            {
                if ( $fmt[strlen($fmt) - 1] == 'F' )
                {
                    $GLOBALS['printf_parameter'] = $params[$id + 1];
                    $params[0] = preg_replace_callback( '/%[-+]?[0-9]?(\.([0-9]))?F/', 'ezprintf_format_f', $params[0], 1 );
                }
            }
            return call_user_func_array('sprintf', $params);
        }
    }

    if ( !function_exists( 'ezprintf' ) )
    {
        function ezprintf()
        {
            $params = func_get_args();
            echo call_user_func_array('ezsprintf', $params);
        }
    }
}
else
{
    if ( !function_exists( 'ezprintf' ) )
    {
        function ezprintf()
        {
            $params = func_get_args();
            return call_user_func_array( 'printf', $params );
        }
    }

    if ( !function_exists( 'ezsprintf' ) )
    {
        function ezsprintf()
        {
            $params = func_get_args();
            return call_user_func_array( 'sprintf', $params );
        }
    }
}

?>
