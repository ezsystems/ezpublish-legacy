<?php
//
// Custom versions of PHP functions for older PHP versions
//
// Created on: <15-Nov-2004 10:05:41 dr>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

if ( version_compare( phpversion(), '4.3.10', '<' ) )
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

	function ezprintf()
	{
		$params = func_get_args();
		echo call_user_func_array('ezsprintf', $params);
	}
}
else
{
	function ezprintf()
	{
		$params = func_get_args();
		return call_user_func_array( 'printf', $params );
	}

	function ezsprintf()
	{
		$params = func_get_args();
		return call_user_func_array( 'sprintf', $params );
	}
}

?>
