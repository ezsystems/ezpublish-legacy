<?php
//
// Created on: <28-Jan-2004 16:10:44 dr>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

class eZDbSchemaChecker {

	function diff($schema1, $schema2 = array())
	{
		if (!is_array( $schema1 ))
		{
			return false;
		}
		$diff = array();

		/* Loop through all tables and see if they exist in the compared
		 * schema */
		foreach ($schema1 as $name => $def) {
			if (!isset ($schema2[$name])) {
				$diff['new_tables'][$name] = $def;
			} else {
				$table_diff = eZDbSchemaChecker::diffTable($def, $schema2[$name]);
				if (count($table_diff)) {
					$diff['table_changes'][$name] = $table_diff;
				}
			}
		}
		
		return $diff;
	}

	function diffTable($table1, $table2)
	{
		return array();
	}
}
