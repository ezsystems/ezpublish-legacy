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
    function schema( $params = array() )
    {
        $params = array_merge( array( 'meta_data' => false,
                                      'format' => 'generic' ),
                               $params );
        $schema = array();

        if ( is_subclass_of( $this->DBInstance, 'ezdbinterface' ) )
        {
            $tableArray = $this->DBInstance->arrayQuery( "SHOW TABLES" );

            foreach( $tableArray as $tableNameArray )
            {
                $table_name = current( $tableNameArray );
                $schema_table['name'] = $table_name;
                $schema_table['fields'] = $this->fetchTableFields( $table_name, $params );
                $schema_table['indexes'] = $this->fetchTableIndexes( $table_name, $params );

                $schema[$table_name] = $schema_table;
            }
            ksort( $schema );
            $this->transformSchema( $schema, $params['format'] == 'local' );
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
	function fetchTableFields( $table, $params )
	{
		$fields = array();

        $resultArray = $this->DBInstance->arrayQuery( "DESCRIBE $table" );

        $i = 0;
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
            $charTypes = array( 'varchar', 'char' );
            if ( in_array( $field['type'], $charTypes ) )
            {
                if ( !$field['not_null'] )
                {
                    if ( $field['default'] === null )
                    {
                        $field['default'] = null;
                    }
                    else if ( $field['default'] === false )
                    {
                        $field['default'] = '';
                    }
                }
            }
            else if ( in_array( $field['type'], $numericTypes ) )
            {
                if ( !$field['not_null'] and $field['default'] === null )
                {
                }
                else if ( $field['default'] == false )
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
            $field['offset'] = $i;
			$fields[$row['Field']] = $field;
            ++$i;
		}
        ksort( $fields );

		return $fields;
	}

	/*!
	 * \private
	 */
	function fetchTableIndexes( $table, $params )
	{
        $metaData = $params['meta_data'];
		$indexes = array();

        $resultArray = $this->DBInstance->arrayQuery( "SHOW INDEX FROM $table" );

        $i = 0;
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
            $indexes[$kn]['offset'] = $i;

            $indexFieldDef = array( 'name' => $row['Column_name'] );

            // Include length if one is defined
            if ( $row['Sub_part'] )
            {
                $indexFieldDef['mysql:length'] = (int)$row['Sub_part'];
            }

            // Check if we have any entries other than 'name', if not we skip the array definition
            if ( count( array_diff( array_keys( $indexFieldDef ), array( 'name' ) ) ) == 0 )
            {
                $indexFieldDef = $indexFieldDef['name'];
            }
			$indexes[$kn]['fields'][$row['Seq_in_index'] - 1] = $indexFieldDef;
            ++$i;
		}
        ksort( $indexes );

		return $indexes;
	}

	function parseType( $type_info, &$length_info )
	{
		preg_match( "@([a-z]*)(\(([0-9]*)\))?@", $type_info, $matches );
		if ( isset( $matches[3] ) )
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
	function generateAddIndexSql( $table_name, $index_name, $def, $params, $isEmbedded = false )
	{
        $diffFriendly = $params['diff_friendly'];
        // If the output should compatible with existing MySQL dumps
        $mysqlCompatible = isset( $params['compatible_sql'] ) ? $params['compatible_sql'] : false;
        $sql = '';

        // Will be set to true when primary key is inside CREATE TABLE
        if ( !$isEmbedded )
        {
            $sql .= "ALTER TABLE $table_name ADD";
            $sql .= " ";
        }

		switch ( $def['type'] )
		{
            case 'primary':
            {
                $sql .= 'PRIMARY KEY';
                if ( $mysqlCompatible )
                    $sql .= " ";
            } break;

            case 'non-unique':
            {
                if ( $isEmbedded )
                {
                    $sql .= "KEY $index_name";
                }
                else
                {
                    $sql .= "INDEX $index_name";
                }
            } break;

            case 'unique':
            {
                if ( $isEmbedded )
                {
                    $sql .= "UNIQUE KEY $index_name";
                }
                else
                {
                    $sql .= "UNIQUE $index_name";
                }
            } break;
		}

        $sql .= ( $diffFriendly ? " (\n    " : ( $mysqlCompatible ? " (" : " ( " ) );
        $fields = $def['fields'];
        $i = 0;
        foreach ( $fields as $fieldDef )
        {
            if ( $i > 0 )
            {
                $sql .= $diffFriendly ? ",\n    " : ( $mysqlCompatible ? ',' : ', ' );
            }
            if ( is_array( $fieldDef ) )
            {
                $sql .= $fieldDef['name'];
                if ( isset( $fieldDef['mysql:length'] ) )
                {
                    if ( $diffFriendly )
                    {
                        $sql .= "(\n";
                        $sql .= "    " . str_repeat( ' ', strlen( $fieldDef['name'] ) );
                    }
                    else
                    {
                        $sql .= $mysqlCompatible ? "(" : "( ";
                    }
                    $sql .= $fieldDef['mysql:length'];
                    if ( $diffFriendly )
                    {
                        $sql .= ")";
                    }
                    else
                    {
                        $sql .= $mysqlCompatible ? ")" : " )";
                    }
                }
            }
            else
            {
                $sql .= $fieldDef;
            }
            ++$i;
        }
        $sql .= ( $diffFriendly ? "\n)" : ( $mysqlCompatible ? ")" : " )" ) );

        if ( !$isEmbedded )
        {
            return $sql . ";\n";
        }
        return $sql;
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
            $defList = array();
            $type = $def['type'];
			if ( isset( $def['length'] ) )
			{
				$type .= "({$def['length']})";
			}
			$defList[] = $type;
			if ( isset( $def['not_null'] ) && ( $def['not_null'] ) )
            {
				$defList[] = 'NOT NULL';
			}
            if ( array_key_exists( 'default', $def ) )
            {
                if ( $def['default'] === null )
                {
                    $defList[] = "default NULL";
                }
                else if ( $def['default'] !== false )
                {
                    $defList[] = "default '{$def['default']}'";
                }
			}
			else if ( $def['type'] == 'varchar' )
			{
				$defList[] = "default ''";
			}
            $sql_def .= join( $diffFriendly ? "\n    " : " ", $defList );
			$skip_primary = false;
		}
		else
		{
            if ( $diffFriendly )
            {
                $sql_def .= "int(11)\n    NOT NULL\n    auto_increment";
            }
            else
            {
                $sql_def .= 'int(11) NOT NULL auto_increment';
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

        $fields = $tableDef['fields'];
        uasort( $fields, create_function( '$a, $b', 'return ( $a["offset"] == $b["offset"]) ? 0 : ( $a["offset"] > $b["offset"] ? 1 : -1 );' ) );

        foreach ( $fields as $field_name => $field_def )
        {
            $sql_fields[] = '  ' . eZMysqlSchema::generateFieldDef( $field_name, $field_def, $skip_pk_flag, $params );
            if ( $skip_pk_flag )
            {
                $skip_pk = true;
            }
        }

        $embeddedIndexList = array();

        // Make sure the order is as defined by 'offset'
        $indexes = $tableDef['indexes'];
        uasort( $indexes, create_function( '$a, $b', 'return ( $a["offset"] == $b["offset"]) ? 0 : ( $a["offset"] > $b["offset"] ? 1 : -1 );' ) );

        // We need to add primary key in table definition
//        if ( $skip_pk )
        {
            foreach ( $indexes as $index_name => $index_def )
            {
                $embeddedIndexList[] = $index_name;
                $sql_fields[] = ( $diffFriendly ? '' : '  ' ) . eZMysqlSchema::generateAddIndexSql( $tableName, $index_name, $index_def, $params, true );
            }
        }
        $sql .= join( ",\n", $sql_fields );
        $sql .= "\n)";

        $extraOptions = array();
        if ( isset( $params['table_type'] ) and $params['table_type'] )
        {
            $typeName = $this->tableStorageTypeName( $params['table_type'] );
            if ( $typeName )
            {
                $extraOptions[] = "TYPE=" . $typeName;
            }
        }
        if ( isset( $params['table_charset'] ) and $params['table_charset'] )
        {
            $charsetName = $this->tableCharsetName( $params['table_charset'] );
            if ( $charsetName )
            {
                $extraOptions[] = "DEFAULT CHARACTER SET " . $charsetName;
            }
        }
        if ( count( $extraOptions ) > 0 )
        {
            $sql .= " " . implode( $diffFriendly ? "\n" : " ", $extraOptions );
        }
        $sql .= ";\n";

        foreach ( $indexes as $index_name => $index_def )
        {
//             if ( $index_def['type'] != 'primary' or
//                  ( $index_def['type'] == 'primary' ) && ( !$skip_pk ) )
            if ( !in_array( $index_name, $embeddedIndexList ) )
            {
                $sql .= eZMysqlSchema::generateAddIndexSql( $tableName, $index_name, $index_def, $params );
            }
        }
		return $sql;
	}

    /*!
      \return The name of the charset \a $charset in a format MySQL understands.
    */
    function tableCharsetName( $charset )
    {
        include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
        $charset = eZCharsetInfo::realCharsetCode( $charset );
        // Convert charset names into something MySQL will understand
        $charsetMapping = array( 'iso-8859-1' => 'latin1',
                                 'iso-8859-2' => 'latin2',
                                 'iso-8859-8' => 'hebrew',
                                 'iso-8859-7' => 'greek',
                                 'iso-8859-9' => 'latin5',
                                 'iso-8859-13' => 'latin7',
                                 'windows-1250' => 'cp1250',
                                 'windows-1251' => 'cp1251',
                                 'windows-1256' => 'cp1256',
                                 'windows-1257' => 'cp1257',
                                 'utf-8' => 'utf8',
                                 'koi8-r' => 'koi8r' );
        $charset = strtolower( $charset );
        if ( isset( $charsetMapping ) )
            return $charsetMapping[$charset];
        return $charset;
    }

    /*!
      \return The name of storage type \a $type or \c false if not supported.

      \note Currently supports \c bdb, \c myisam and \c innodb.

      See http://dev.mysql.com/doc/mysql/en/CREATE_TABLE.html for overview of the types MySQL supports
    */
    function tableStorageTypeName( $type )
    {
        $type = strtolower( $type );
        switch ( $type )
        {
            case 'bdb':
            {
                return 'BDB';
            }

            case 'myisam':
            {
                return 'MyISAM';
            }

            case 'innodb':
            {
                return 'InnoDB';
            }
        }
        return false;
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

    /*!
     \reimp
    */
    function schemaType()
    {
        return 'mysql';
    }

}
?>
