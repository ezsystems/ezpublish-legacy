<?php
//
// Created on: <30-Jan-2004 10:14:58 dr>
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

class eZMysqlSchema
{

	function read( $con )
	{
		$schema = array();
		$res = mysql_query( "SHOW TABLES", $con );

		while ( $row = mysql_fetch_row ( $res ) )
		{
			$table_name = $row[0];
			$schema_table['fields'] = $this->fetchTableFields( $table_name, $con );
			$schema_table['indexes'] = $this->fetchTableIndexes( $table_name, $con );

			$schema[$table_name] = $schema_table;
		}
		$this->schema = $schema;
		return $this->schema;
	}

	/*!
	 * \private
	 */
	function fetchTableFields($table, $con)
	{
		$fields = array();

		$res = mysql_query( "DESCRIBE $table", $con );

		while ( $row = mysql_fetch_assoc ( $res ) )
		{
			$field = array();
			$field['type'] = $this->parseType ( $row['Type'], $field['length'] );
			if ( !$field['length'] )
			{
				unset( $field['length'] );
			}
			if ( $row['Null'] != 'YES' )
			{
				$field['not_null'] = '1';
			}
			if ( ( !empty( $row['Default'] ) ) || ( $field['type'] == 'varchar' ) )
			{
				$field['default'] = $row['Default'];
			}
			if ( ( empty( $row['Default'] ) ) && ( $field['type'] == 'float' ) )
			{
				$field['default'] = '0';
			}

			if ( substr ( $row['Extra'], 'auto_increment' ) !== false )
			{
				unset( $field['length'] );
				unset( $field['not_null'] );
				$field['type'] = 'auto_increment';
			}
			$fields[$row['Field']] = $field;
		}

		return $fields;
	}

	/*!
	 * \private
	 */
	function fetchTableIndexes( $table, $con )
	{
		$indexes = array();

		$res = mysql_query( "SHOW INDEX FROM $table", $con );

		while ( $row = mysql_fetch_assoc ( $res ) )
		{
			$kn = $row['Key_name'];

			if ( $kn == 'PRIMARY' )
			{
				$indexes[$kn]['type'] = 'primary';
			}
			else
			{
				$indexes[$kn]['type'] = $row['Non_unique'] ? 'non-unique' : 'unique';
			}
			$indexes[$kn]['fields'][$row['Seq_in_index'] - 1] = $row['Column_name'];
		}

		return $indexes;
	}

	function parseType( $type_info, &$length_info )
	{
		preg_match( "@([a-z]*)(\(([0-9]*)\))?@", $type_info, $matches );
		if ( isset ( $matches[3] ) )
		{
			$length_info = $matches[3];
		}
		return $matches[1];
	}

	/*!
	 * \private
	 */
	function generateAddIndexSql( $table_name, $index_name, $def )
	{
		$sql = "ALTER TABLE $table_name ADD ";

		switch ( $def['type'] )
		{
            case 'primary':
            {
                $sql .= 'PRIMARY KEY ';
            } break;

            case 'non-unique':
            {
                $sql .= "INDEX $index_name ";
            } break;

            case 'unique':
            {
                $sql .= "UNIQUE $index_name ";
            } break;
		}
		$sql .= '( ' . join ( ', ', $def['fields'] ) . ' )';

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateDropIndexSql( $table_name, $index_name )
	{
		$sql = "ALTER TABLE $table_name DROP ";

		if ( $def['type'] == 'primary' )
		{
			$sql .= 'PRIMARY KEY';
		}
		else
		{
			$sql .= "INDEX $index_name";
		}
		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateFieldDef( $field_name, $def, &$skip_primary )
	{
		$sql_def = $field_name . ' ';

		if ( $def['type'] != 'auto_increment' )
		{
			$sql_def .= $def['type'];
			if ( isset( $def['length'] ) )
			{
				$sql_def .= "({$def['length']})";
			}
			$sql_def .= ' ';
			if ( isset( $def['not_null'] ) && ( $def['not_null'] ) )
            {
				$sql_def .= 'NOT NULL ';
			}
			if ( isset( $def['default'] ) )
            {
				$sql_def .= "DEFAULT '{$def['default']}' ";
			}
			else if ( $def['type'] == 'varchar' )
			{
				$sql_def .= "DEFAULT '' ";
			}
			$skip_primary = false;
		}
		else
		{
			$sql_def .= 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY';
			$skip_primary = true;
		}
		return $sql_def;
	}

	/*!
	 * \private
	 */
	function generateAddFieldSql( $table_name, $field_name, $def )
	{
		$sql = "ALTER TABLE $table_name ADD COLUMN ";
		$sql .= eZMysqlSchema::generateFieldDef ( $field_name, $def, $dummy );

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateAlterFieldSql( $table_name, $field_name, $def )
	{
		$sql = "ALTER TABLE $table_name CHANGE COLUMN $field_name ";
		$sql .= eZMysqlSchema::generateFieldDef ( $field_name, $def, $dummy );

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateDropFieldSql( $table_name, $field_name )
	{
		$sql = "ALTER TABLE $table_name DROP COLUMN $field_name";

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateSchemaFile( $schema )
	{
		$sql = '';

		foreach ( $schema as $table => $table_def )
		{
            $sql .= eZMysqlSchema::generateTableSchema( $table, $table_def );
			$sql .= "\n\n";
		}

		return $sql;
	}

	/*!
	 * \private
	 */
	function generateTableSchema( $table, $table_def )
	{
		$sql = '';
        $skip_pk = false;
        $sql_fields = array();
        $sql .= "CREATE TABLE $table (\n";
        foreach ( $table_def['fields'] as $field_name => $field_def )
        {
            $sql_fields[] = "\t". eZMysqlSchema::generateFieldDef( $field_name, $field_def, $skip_pk_flag );
            if ( $skip_pk_flag )
            {
                $skip_pk = true;
            }
        }
        $sql .= join ( ",\n", $sql_fields ) . "\n);\n";

        foreach ( $table_def['indexes'] as $index_name => $index_def )
        {
            if ( ( $index_def['type'] == 'primary' ) && ( !$skip_pk ) )
            {
                $sql .= eZMysqlSchema::generateAddIndexSql( $table, $index_name, $index_def );
            }
        }
		return $sql;
	}

	/*!
	 * \private
	 */
	function generateUpgradeFile( $differences )
	{
		$sql = '';

		/* Loop over all 'table_changes' */
		if ( isset( $differences['table_changes'] ) )
		{
			foreach ( $differences['table_changes'] as $table => $table_diff )
			{
				if ( isset( $table_diff['added_fields'] ) )
				{
					foreach ( $table_diff['added_fields'] as $field_name => $added_field )
					{
						$sql .= ezMysqlSchema::generateAddFieldSql( $table, $field_name, $added_field );
					}
				}

				if ( isset( $table_diff['changed_fields'] ) )
				{
					foreach ( $table_diff['changed_fields'] as $field_name => $changed_field )
					{
						$sql .= ezMysqlSchema::generateAlterFieldSql( $table, $field_name, $changed_field );
					}
				}
				if ( isset( $table_diff['removed_fields'] ) )
				{
					foreach ( $table_diff['removed_fields'] as $field_name => $removed_field )
					{
						$sql .= ezMysqlSchema::generateDropFieldSql( $table, $field_name );
					}
				}

				if ( isset( $table_diff['added_indexes'] ) )
				{
					foreach ( $table_diff['added_indexes'] as $index_name => $added_index )
					{
						$sql .= ezMysqlSchema::generateAddIndexSql( $table, $index_name, $added_index );
					}
				}

				if ( isset( $table_diff['changed_indexes'] ) )
				{
					foreach ( $table_diff['changed_indexes'] as $index_name => $changed_index )
					{
						$sql .= ezMysqlSchema::generateDropIndexSql( $table, $index_name );
						$sql .= ezMysqlSchema::generateAddIndexSql( $table, $index_name, $changed_index );
					}
				}
				if ( isset( $table_diff['removed_indexes'] ) )
				{
					foreach ( $table_diff['removed_indexes'] as $index_name => $removed_index )
					{
						$sql .= ezMysqlSchema::generateDropIndexSql( $table, $index_name );
					}
				}
			}
		}
		if ( isset( $differences['new_tables'] ) )
		{
			foreach ( $differences['new_tables'] as $table => $table_def )
			{
                $sql .= eZMySQLSchema::generateTableSchema( $table, $table_def );
            }
        }
        return $sql;
	}

	function writeUpgradeFile( $differences, $filename )
	{
		$fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, eZMysqlSchema::generateUpgradeFile( $differences ) );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
	}

	function writeSchemaFile( $schema, $filename )
	{
		$fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, eZMysqlSchema::generateSchemaFile( $schema ) );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
	}
}
?>
