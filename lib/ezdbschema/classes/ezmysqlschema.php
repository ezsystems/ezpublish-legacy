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

include_once( 'lib/ezdbschema/classes/ezdbschemainterface.php' );

class eZMysqlSchema extends eZDBSchemaInterface
{

    /*!
     \reimp
     Constructor

     \param db instance
    */
    function eZMysqlSchema( $db )
    {
        $this->eZDBSchemaInterface( $db );
    }

    /*!
     \reimp
    */
    function schema()
    {
        $schema = array();

        $tableArray = $this->DBInstance->arrayQuery( "SHOW TABLES" );

        foreach( $tableArray as $tableNameArray )
        {
			$table_name = current( $tableNameArray );
            $schema_table['name'] = $table_name;
			$schema_table['fields'] = $this->fetchTableFields( $table_name );
			$schema_table['indexes'] = $this->fetchTableIndexes( $table_name );

			$schema[$table_name] = $schema_table;
		}

		return $schema;
    }

	/*!
	 \private

     \param table name
	 */
	function fetchTableFields( $table )
	{
		$fields = array();

        $resultArray = $this->DBInstance->arrayQuery( "DESCRIBE $table" );

        foreach( $resultArray as $row )
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
            $field['default'] = false;
            if ( !isset( $field['not_null'] ) )
            {
                if ( $row['Default'] === null )
                    $field['default'] = null;
                else
                    $field['default'] = (string)$row['Default'];
            }
            else
			{
				$field['default'] = (string)$row['Default'];
			}
            $numericTypes = array( 'float', 'int' );
            $blobTypes = array( 'tinytext', 'text', 'mediumtext', 'longtext' );
            if ( $field['type'] == 'varchar' )
            {
                if ( $field['default'] === false or
                     $field['default'] === null )
                {
                    $field['default'] = '';
                }
            }
            else if ( in_array( $field['type'], $numericTypes ) )
            {
                if ( $field['default'] == false )
                {
                    $field['default'] = 0;
                }
                else if ( $field['type'] == 'integer' )
                {
                    $field['default'] = (int)$field['default'];
                }
                else if ( $field['type'] == 'float' )
                {
                    $field['default'] = (float)$field['default'];
                }
            }
            else if ( in_array( $field['type'], $blobTypes ) )
            {
                // We do not want default for blobs.
                $field['default'] = false;
            }

			if ( substr ( $row['Extra'], 'auto_increment' ) !== false )
			{
				unset( $field['length'] );
				$field['default'] = false;
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
	function fetchTableIndexes( $table )
	{
		$indexes = array();

        $resultArray = $this->DBInstance->arrayQuery( "SHOW INDEX FROM $table" );

        foreach( $resultArray as $row )
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
            if ( array_key_exists( 'default', $def ) )
            {
                if ( $def['default'] === null )
                {
                    $sql_def .= "DEFAULT NULL ";
                }
                else if ( $def['default'] !== false )
                {
                    $sql_def .= "DEFAULT '{$def['default']}' ";
                }
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
}
?>
