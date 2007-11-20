<?php
//
// Created on: <28-Jan-2004 16:10:44 dr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  \class eZDbSchemaChecker ezdbschemachecker.php
  \ingroup eZDbSchema
  \brief Checks differences between schemas

*/

class eZDbSchemaChecker
{
    /*!
     \static
     Finds the difference between the scemas \a $schema1 and \a $schema2.
     \return An array containing:
             - new_tables - A list of new tables that have been added
             - removed_tables - A list of tables that have been removed
             - table_changes - Changes in table definition
               - added_fields - A list of new fields in the table
               - removed_fields - A list of removed fields in the table
               - changed_fields - A list of fields that have changed definition
               - added_indexes - A list of new indexes in the table
               - removed_indexes - A list of removed indexes in the table
               - changed_indexes - A list of indexes that have changed definition
    */
    static function diff( $schema1, $schema2 = array(), $schema1Type = false, $schema2Type = false )
    {
        if ( !is_array( $schema1 ) )
        {
            return false;
        }
        $diff = array();

        foreach ( $schema2 as $name => $def )
        {
            // Skip the info structure, this is not a table
            if ( $name == '_info' )
                continue;

            if ( !isset( $schema1[$name] ) )
            {
                $diff['new_tables'][$name] = $def;
            }
            else
            {
                $table_diff = eZDbSchemaChecker::diffTable( $schema1[$name], $def, $schema1Type, $schema2Type );
                if ( count( $table_diff ) )
                {
                    $diff['table_changes'][$name] = $table_diff;
                }
            }
        }

        /* Check if there are tables removed */
        foreach ( $schema1 as $name => $def )
        {
            // Skip the info structure, this is not a table
            if ( $name == '_info' )
                continue;

            if ( !isset( $schema2[$name] ) )
            {
                $diff['removed_tables'][$name] = $def;
            }
            else if ( isset( $schema2[$name]['removed'] ) and
                      isset( $schema2[$name]['removed'] ) )
            {
                $diff['removed_tables'][$name] = $def;
            }
        }

        return $diff;
    }

    /*!
     \static
     Finds the difference between the tables \a $table1 and \a $table2 by looking
     at the fields and indexes.

     \return An array containing:
             - added_fields - A list of new fields in the table
             - removed_fields - A list of removed fields in the table
             - changed_fields - A list of fields that have changed definition
             - added_indexes - A list of new indexes in the table
             - removed_indexes - A list of removed indexes in the table
             - changed_indexes - A list of indexes that have changed definition
    */
    static function diffTable( $table1, $table2, $schema1Type, $schema2Type )
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
            else if ( isset( $table2['fields'][$name]['removed'] ) and
                      $table2['fields'][$name]['removed'] )
            {
                $table_diff['removed_fields'][$name] = true;
            }
        }
        /* See if there are any changed definitions */
        foreach ( $table1['fields'] as $name => $def )
        {
            if ( isset( $table2['fields'][$name] ) )
            {
                if ( is_array( $field_diff = eZDbSchemaChecker::diffField( $def, $table2['fields'][$name], $schema1Type, $schema2Type ) ) )
                {
                    $table_diff['changed_fields'][$name] = $field_diff;
                }
            }
        }

        $table1Indexes = $table1['indexes'];
        $table2Indexes = $table2['indexes'];

        /* See if all the indexes in table 1 exist in table 2 */
        foreach ( $table2Indexes as $name => $def )
        {
            if ( !isset( $table1Indexes[$name] ) )
            {
                $table_diff['added_indexes'][$name] = $def;
            }
        }
        /* See if there are any removed indexes in table 2 */
        foreach ( $table1Indexes as $name => $def )
        {
            if ( !isset( $table2Indexes[$name] ) )
            {
                $table_diff['removed_indexes'][$name] = $def;
            }
            else if ( isset( $table2Indexes[$name]['removed'] ) and
                      $table2Indexes[$name]['removed'] )
            {
                if ( isset( $table2Indexes[$name]['comments'] ) )
                    $def['comments'] = array_merge( isset( $def['comments'] ) ? $def['comments'] : array(),
                                                    $table2Indexes[$name]['comments'] );
                $table_diff['removed_indexes'][$name] = $def;
            }
        }
        /* See if there are any changed definitions */
        foreach ( $table1Indexes as $name => $def )
        {
            if ( isset( $table2Indexes[$name] ) )
            {
                if ( is_array( $index_diff = eZDbSchemaChecker::diffIndex( $def, $table2Indexes[$name], $schema1Type, $schema2Type ) ) )
                {
                    $table_diff['changed_indexes'][$name] = $index_diff;
                }
            }
        }

        return $table_diff;
    }

    /*!
     \static
     Finds the difference between the field \a $field1 and \a $field2.

     \return The field definition of the changed field or \c false if there are no changes.
    */
    static function diffField( $field1, $field2, $schema1Type, $schema2Type )
    {
        /* Type is always available */
        if ( $field1['type'] != $field2['type'] )
        {
            return array( 'different-options' => array( 'type' ), 'field-def' => $field2 );
            return $field2;
        }

        $test_fields = array( 'length', 'default', 'not_null' );
        $different_options = array();

        foreach ( $test_fields as $test_field )
        {
            if ( isset( $field1[$test_field] ) )
            {
                if ( !isset( $field2[$test_field] ) ||
                     ( $field1[$test_field] != $field2[$test_field] ) )
                {
                    $different_options[] = $test_field;
                }
            }
            else
            {
                if ( isset( $field2[$test_field] ) )
                {
                    $different_options[] = $test_field;
                }
            }
        }

        if ( $different_options )
            return array( 'different-options' => $different_options, 'field-def' => $field2 );
        else
            return false;
    }

    /*!
     \static
     Finds the difference between the indexes \a $index1 and \a $index2.

     \return The index definition of the changed index or \c false if there are no changes.
    */
    static function diffIndex( $index1, $index2, $schema1Type, $schema2Type )
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
?>
