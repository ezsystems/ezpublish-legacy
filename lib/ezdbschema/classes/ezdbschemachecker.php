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

class eZDbSchemaChecker
{

	function diff( $schema1, $schema2 = array() )
	{
		if ( !is_array( $schema1 ) )
		{
			return false;
		}
		$diff = array();

		/* Loop through all tables and see if they exist in the compared
		 * schema */
		foreach ( $schema2 as $name => $def )
        {
			if ( !isset( $schema1[$name] ) )
            {
				$diff['new_tables'][$name] = $def;
			}
            else
            {
				$table_diff = eZDbSchemaChecker::diffTable( $schema1[$name], $def );
				if ( count( $table_diff ) )
                {
					$diff['table_changes'][$name] = $table_diff;
				}
			}
		}

		/* Check if there are tables removed */
		foreach ( $schema1 as $name => $def )
        {
			if ( !isset( $schema2[$name] ) )
            {
				$diff['removed_tables'][$name] = true;
			}
		}

		return $diff;
	}

	function diffTable( $table1, $table2 )
	{
		$table_diff = array();

		/* See if all the fields in table 1 exist in table 2 */
		foreach ( $table2['fields'] as $name => $def )
        {
			if ( !isset( $table1['fields'][$name] ) )
            {
				$table_diff['added_fields'][$name] = $def;
			}
		}
		/* See if there are any removed fields in table 2 */
		foreach ( $table1['fields'] as $name => $def )
        {
			if ( !isset( $table2['fields'][$name] ) )
            {
				$table_diff['removed_fields'][$name] = true;
			}
		}
		/* See if there are any changed definitions */
		foreach ( $table1['fields'] as $name => $def )
        {
			if ( isset( $table2['fields'][$name] ) )
            {
				if ( is_array( $field_diff = eZDbSchemaChecker::diffField( $def, $table2['fields'][$name] ) ) )
                {
					$table_diff['changed_fields'][$name] = $field_diff;
				}
			}
		}

		/* See if all the indexes in table 1 exist in table 2 */
		foreach ( $table2['indexes'] as $name => $def )
        {
			if ( !isset( $table1['indexes'][$name] ) )
            {
				$table_diff['added_indexes'][$name] = $def;
			}
		}
		/* See if there are any removed indexes in table 2 */
		foreach ( $table1['indexes'] as $name => $def )
        {
			if ( !isset( $table2['indexes'][$name] ) )
            {
				$table_diff['removed_indexes'][$name] = true;
			}
		}
		/* See if there are any changed definitions */
		foreach ( $table1['indexes'] as $name => $def )
        {
			if ( isset($table2['indexes'][$name] ) )
            {
				if ( is_array( $index_diff = eZDbSchemaChecker::diffIndex( $def, $table2['indexes'][$name] ) ) )
                {
					$table_diff['changed_indexes'][$name] = $index_diff;
				}
			}
		}

		return $table_diff;
	}

	function diffField( $field1, $field2 )
	{
		/* Type is always available */
		if ( $field1['type'] != $field2['type'] )
        {
			return $field2;
		}

		$test_fields = array( 'length', 'default', 'not_null' );
		foreach ( $test_fields as $test_field )
        {
			if ( isset( $field1[$test_field] ) )
            {
				if ( !isset( $field2[$test_field] ) ||
                     ( $field1[$test_field] != $field2[$test_field] ) )
                {
					return $field2;
				}
			}
            else
            {
				if ( isset( $field2[$test_field] ) )
                {
					return $field2;
				}
			}
		}
		return false;
	}

	function diffIndex( $index1, $index2 )
	{
		if ( ( $index1['type'] != $index2['type'] ) ||
             count( array_diff( $index1, $index2 ) ) )
		{
			return $index2;
		}

		$test_fields = array( 'link_table' );
		foreach ( $test_fields as $test_field )
        {
			if ( isset($index1[$test_field] ) )
            {
				if ( !isset( $index2[$test_field] ) ||
                     ( $index1[$test_field] != $index2[$test_field] ) )
                {
					return $index2;
				}
			}
            else
            {
				if ( isset($index2[$test_field] ) )
                {
					return $index2;
				}
			}
		}

		$test_fields = array( 'fields', 'link_fields' );
		foreach ( $test_fields as $test_field )
        {
			if ( isset( $index1[$test_field] ) )
            {
				if ( !isset( $index2[$test_field] ) ||
                     !( $index1[$test_field] == $index2[$test_field] ) )
                {
					return $index2;
				}
			}
            else
            {
				if ( isset( $index2[$test_field] ) )
                {
					return $index2;
				}
			}
		}
	}
}
