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

/*!
  \class eZMysqlSchema ezmysqlschema.php
  \ingroup eZDBSchema
  \brief Handles schemas for MySQL

*/

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

        if ( is_subclass_of( $this->DBInstance, 'ezdbinterface' ) )
        {
            $tableArray = $this->DBInstance->arrayQuery( "SHOW TABLES" );

            foreach( $tableArray as $tableNameArray )
            {
                $table_name = current( $tableNameArray );
                $schema_table['name'] = $table_name;
                $schema_table['fields'] = $this->fetchTableFields( $table_name );
                $schema_table['indexes'] = $this->fetchTableIndexes( $table_name );

                $schema[$table_name] = $schema_table;
            }
            ksort( $schema );
        }
        else
        {
            $schema = $this->DBInstance['schema'];
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
        ksort( $fields );

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
        ksort( $indexes );

		return $indexes;
	}

	function parseType( $type_info, &$length_info )
	{
		preg_match( "@([a-z]*)(\(([0-9]*)\))?@", $type_info, $matches );
		if ( isset ( $matches[3] ) )
		{
			$length_info = $matches[3];
            if ( is_numeric( $length_info ) )
                $length_info = (int)$length_info;
		}
		return $matches[1];
	}

	/*!
	 * \private
	 */
	function generateAddIndexSql( $table_name, $index_name, $def, $params )
	{
        $diffFriendly = $params['diff_friendly'];
        $sql = '';
		$sql .= "ALTER TABLE $table_name ADD";

        $sql .= " ";
		switch ( $def['type'] )
		{
            case 'primary':
            {
                $sql .= 'PRIMARY KEY';
            } break;

            case 'non-unique':
            {
                $sql .= "INDEX $index_name";
            } break;

            case 'unique':
            {
                $sql .= "UNIQUE $index_name";
            } break;
		}
        $sql .= ( $diffFriendly ? " (\n    " : " ( " );
		$sql .= join( ( $diffFriendly ? ",\n    " : ', ' ), $def['fields'] );
        $sql .= ( $diffFriendly ? "\n)" : " )" );

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateDropIndexSql( $table_name, $index_name, $def, $params )
	{
        $sql = '';
		$sql .= "ALTER TABLE $table_name DROP ";

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
	function generateFieldDef( $field_name, $def, &$skip_primary, $params )
	{
        $diffFriendly = $params['diff_friendly'];
		$sql_def = $field_name . ' ';

		if ( $def['type'] != 'auto_increment' )
		{
			$sql_def .= $def['type'];
			if ( isset( $def['length'] ) )
			{
				$sql_def .= "({$def['length']})";
			}
			if ( isset( $def['not_null'] ) && ( $def['not_null'] ) )
            {
                $sql_def .= $diffFriendly ? "\n    " : " ";
				$sql_def .= 'NOT NULL';
			}
            if ( array_key_exists( 'default', $def ) )
            {
                $sql_def .= $diffFriendly ? "\n    " : " ";
                if ( $def['default'] === null )
                {
                    $sql_def .= "DEFAULT NULL";
                }
                else if ( $def['default'] !== false )
                {
                    $sql_def .= "DEFAULT '{$def['default']}'";
                }
			}
			else if ( $def['type'] == 'varchar' )
			{
                $sql_def .= $diffFriendly ? "\n    " : " ";
				$sql_def .= "DEFAULT ''";
			}
			$skip_primary = false;
		}
		else
		{
            if ( $diffFriendly )
            {
                $sql_def .= "int(11)\n    NOT NULL\n    AUTO_INCREMENT\n    PRIMARY KEY";
            }
            else
            {
                $sql_def .= 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY';
            }
			$skip_primary = true;
		}
		return $sql_def;
	}

	/*!
	 * \private
	 */
	function generateAddFieldSql( $table_name, $field_name, $def, $params )
	{
		$sql = "ALTER TABLE $table_name ADD COLUMN ";
		$sql .= eZMysqlSchema::generateFieldDef ( $field_name, $def, $dummy, $params );

		return $sql . ";\n";
	}

	/*!
	 * \private
	 */
	function generateAlterFieldSql( $table_name, $field_name, $def = array() )
	{
		$sql = "ALTER TABLE $table_name CHANGE COLUMN $field_name ";
		$sql .= eZMysqlSchema::generateFieldDef ( $field_name, $def, $dummy, $params );

		return $sql . ";\n";
	}

	/*!
	 \private
    */
	function generateTableSchema( $tableName, $tableDef, $params )
	{
        $diffFriendly = $params['diff_friendly'];
		$sql = '';
        $skip_pk = false;
        $sql_fields = array();
        $sql .= "CREATE TABLE $tableName (\n";
        foreach ( $tableDef['fields'] as $field_name => $field_def )
        {
            $sql_fields[] = '  ' . eZMysqlSchema::generateFieldDef( $field_name, $field_def, $skip_pk_flag, $params );
            if ( $skip_pk_flag )
            {
                $skip_pk = true;
            }
        }
        $sql .= join( ",\n", $sql_fields ) . "\n);\n";

        foreach ( $tableDef['indexes'] as $index_name => $index_def )
        {
            if ( $index_def['type'] != 'primary' or
                 ( $index_def['type'] == 'primary' ) && ( !$skip_pk ) )
            {
                $sql .= eZMysqlSchema::generateAddIndexSql( $tableName, $index_name, $index_def, $params );
            }
        }
		return $sql;
	}

    /*!
     * \private
     */
    function generateDropTable( $table, $params )
    {
        return "DROP TABLE $table;\n";
    }

    /*!
     \reimp
     MySQL 3.22.5 and higher support multi-insert queries so if the current
     database has sufficient version we return \c true.
     If no database is connected we return \true.
    */
    function isMultiInsertSupported()
    {
        if ( is_subclass_of( $this->DBInstance, 'ezdbinterface' ) )
        {
            $versionInfo = $this->DBInstance->databaseServerVersion();

            // We require MySQL 3.22.5 to use multi-insert queries
            // http://dev.mysql.com/doc/mysql/en/INSERT.html
            return ( version_compare( $versionInfo['string'], '3.22.5' ) >= 0 );
        }
        return true;
    }
}
?>
