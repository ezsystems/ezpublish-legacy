<?php
//
// Created on: <21-Apr-2004 11:04:30 kk>
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

/*! \file ezdbschemainterface.php
 Database schema abstraction layer.
*/

/*! \defgroup eZDBSchema Database schema abstraction layer */

/*!
  \class eZDBSchemaInterface ezdbschemainterface.php
  \ingroup eZDBSchema
  \brief This class provide interface for DB schema library

  Schema structure an array with Table structures, each key is the name of the
  table.

  Table structure:
  - name - Name of table
  - fields - Array of Field structures, each key is the field name
  - indexes - Array of Index structures, each key is the index name
  - removed - Contains whether the table has been removed or not (Optional)
  - comments - An array with comment strings (Optional)

  The \c removed entry will only be used when some comments have been added
  to the table. That way the comments can be added to the DROP TABLE statements.

  Field structure:
  - length - A number which defines the length/size of the type or \c false
  - type - String containing the identifier of the Type, see Types below.
  - not_null - Is 1 if the type cannot be null, if 0 or not defined it can be null
  - default - The default value, the value depends on the type, \c false means no default value.

  Index structure:
  - type - What kind of index, see Index Types.
  - fields - Array of field names the index is made on

  Index Types:
  - primary - A primary key, there can only be one primary key. This key will be named PRIMARY.
  - non-unique - A standard index
  - unique - A unique index

  Field Types:
  - int - Integer, uses \c length to define number of digits.
  - float - Float, uses \c length to define number of digits.
  - auto_increment - Integer that auto increments (uses sequence+trigger).
  - varchar - String with variable length, uses \c length for max length.
  - char - String with fixed length, uses \c length.
  - longtext - String with 2^32 number of possible characters
  - mediumtext - String with 2^24 number of possible characters
  - shorttext - String with 2^16 number of possible characters

  When stored as a PHP array the schema structure will be placed in a variable
  called $schema. The data structure will be placed in $data.

*/

class eZDBSchemaInterface
{
    /*!
     Constructor

     \param eZDB instance

     \sa eZDB
     */
    function eZDBSchemaInterface( $dbInstance )
    {
        $this->DBInstance = $dbInstance;
    }

    /*!
     \pure
     Get SQL db schema

     \return DB schema array
    */
    function schema()
    {
    }

    /*!
     \virtual
     Fetches the data for all tables and returns an array containing the data.

     \param $schema A schema array which defines tables to fetch from.
                    If \c false it will call schema() to fetch it.
     \param $tableNameList An array with tables to include, will further narrow
                           tables in \a $scema. Use \c false to fetch all tables.

     \note You shouldn't need to reimplement this method unless since the default
           code will do simple SELECT queries
     \sa fetchTableData()
    */
    function data( $schema = false, $tableNameList = false )
    {
        if ( $schema === false )
            $schema = $this->schema();
        $data = array();
        foreach ( $schema as $tableName => $tableInfo )
        {
            if ( is_array( $tableNameList ) and
                 !in_array( $tableName, $tableNameList ) )
                continue;

            $tableEntry = $this->fetchTableData( $tableInfo );
            if ( count( $tableEntry['rows'] ) > 0 )
                $data[$tableName] = $tableEntry;
        }

        return $data;
    }

    /*!
     \virtual
     Validates the current schema and returns \c true if it is correct or
     \c false if something must be fixed.
     \note This should not be reimplemented by normal handlers, only schema
           checkers.
    */
    function validate()
    {
        return false;
    }

    /*!
     \virtual
     \protected
     Fetches all rows for table defined in \a $tableInfo and returns this structure:
     - fields - Array with fields that were fetched from table, the order of the fields
                are the same as the order of the data
     - rows - Array with all rows, each row is an indexed array with the data.

     \param $tableInfo Table structure from schema.
     \param $offset Which offset to start from or \c false to start at top
     \param $limit How many rows to fetch or \c false for no limit.

     \note You shouldn't need to reimplement this method unless since the default
           code will do simple SELECT queries
     \sa data()
    */
    function fetchTableData( $tableInfo, $offset = false, $limit = false )
    {
        if ( count( $tableInfo['fields'] ) == 0 )
            return false;

        $tableName = $tableInfo['name'];
        $fieldText = '';
        $i = 0;
        $fields = array();
        foreach ( $tableInfo['fields'] as $fieldName => $field )
        {
            if ( $i > 0 )
                $fieldText .= ', ';
            $fieldText .= $fieldName;
            $fields[] = $fieldName;
            ++$i;
        }
        $rows = $this->DBInstance->arrayQuery( "SELECT $fieldText FROM $tableName" );
        $resultArray = array();
        foreach ( $rows as $row )
        {
            $resultArray[] = array_values( $row );
        }
        return array( 'fields' => $fields,
                      'rows' => $resultArray );
    }

    /*!
     \pure
     Write upgrade sql to file

     \param difference array
     \param filename
    */
	function writeUpgradeFile( $differences, $filename, $params = array() )
    {
        $params = array_merge( array( 'schema' => true,
                                      'data' => false,
                                      'allow_multi_insert' => false,
                                      'diff_friendly' => false ),
                               $params );
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
			fputs( $fp, $this->generateUpgradeFile( $differences, $params ) );
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
    }

    /*!
      Write SQL schema definition to file

      \param filename
    */
    function writeSQLSchemaFile( $filename, $params = array() )
    {
        $params = array_merge( array( 'schema' => true,
                                      'data' => false,
                                      'allow_multi_insert' => false,
                                      'diff_friendly' => false ),
                               $params );
        $includeSchema = $params['schema'];
        $includeData = $params['data'];
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
            $schema = $this->schema();
            if ( $includeSchema )
            {
                fputs( $fp, $this->generateSchemaFile( $schema, $params ) );
            }
            if ( $includeData )
            {
                fputs( $fp, $this->generateDataFile( $schema, $this->data( $schema ), $params ) );
            }
			fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
    }

    /*!
      Write PHP schema definition to file using PHP serialized format.

      \param filename
    */
    function writeSerializedSchemaFile( $filename, $params = array() )
    {
        $params = array_merge( array( 'schema' => true,
                                      'data' => false ),
                               $params );
        $includeSchema = $params['schema'];
        $includeData = $params['data'];
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
            $schema = $this->schema();
            if ( $includeSchema and $includeData )
            {
                fputs( $fp, serialize( array( 'schema' => $schema,
                                              'data' => $this->data( $schema ) ) ) );
            }
            else if ( $includeSchema )
            {
                fputs( $fp, serialize( $schema ) );
            }
            else if ( $includeData )
            {
                fputs( $fp, serialize( $this->data( $schema ) ) );
            }
            fclose( $fp );
			return true;
		}
        else
        {
			return false;
		}
    }

    /*!
      Write PHP schema definition to file using PHP array structures.

      \param filename
    */
    function writeArraySchemaFile( $filename, $params = array() )
    {
        $params = array_merge( array( 'schema' => true,
                                      'data' => false ),
                               $params );
        $includeSchema = $params['schema'];
        $includeData = $params['data'];
        $fp = @fopen( $filename, 'w' );
		if ( $fp )
		{
            $schema = $this->schema();
            fputs( $fp, '<?' . 'php' . "\n" );
            if ( $includeSchema )
            {
                fputs( $fp, "// This array contains the database schema\n" );
                fputs( $fp, '$schema = ' . var_export( $schema, true ) . ";\n" );
            }
            if ( $includeData )
            {
                fputs( $fp, "// This array contains the database data\n" );
                fputs( $fp, '$data = ' . var_export( $this->data( $schema ), true ) . ";\n" );
            }
            fputs( $fp, "\n" . '?>' );
            fclose( $fp );
            return true;
		}
        else
        {
			return false;
		}
    }

    /*!
     \private
     \param database schema
     \return schema for file output
    */
    function generateDataFile( $schema, $data, $params )
	{
        $params = array_merge( array( 'allow_multi_insert' => false,
                                      'diff_friendly' => false ),
                               $params );
		$sql = '';

        $i = 0;
		foreach ( $schema as $tableName => $tableDef )
		{
            if ( !isset( $data[$tableName] ) )
                continue;
            if ( $i > 0 )
                $sql .= "\n\n";
            $dataEntries = $data[$tableName];
            $sql .= $this->generateTableInsert( $tableName, $tableDef, $dataEntries, $params );
            ++$i;
		}

		return $sql;
	}

    /*!
     \private
     \param database schema
     \return schema for file output
    */
    function generateSchemaFile( $schema, $params = array() )
	{
		$sql = '';

        $i = 0;
		foreach ( $schema as $table => $tableDef )
		{
            if ( $i > 0 )
                $sql .= "\n\n";
            $sql .= $this->generateTableSchema( $table, $tableDef, $params );
            ++$i;
		}

		return $sql;
	}

	/*!
	 * \private
	 */
	function generateUpgradeFile( $differences, $params = array() )
	{
        $params = array_merge( array( 'schema' => true,
                                      'data' => false,
                                      'allow_multi_insert' => false,
                                      'diff_friendly' => false ),
                               $params );
		$sql = '';

		/* Loop over all 'table_changes' */
		if ( isset( $differences['table_changes'] ) )
		{
			foreach ( $differences['table_changes'] as $table => $table_diff )
			{
				if ( isset ( $table_diff['added_fields'] ) )
				{
					foreach ( $table_diff['added_fields'] as $field_name => $added_field )
					{
                        $this->appendSQLComments( $added_field, $sql );
						$sql .= $this->generateAddFieldSql( $table, $field_name, $added_field, $params );
					}
				}

				if ( isset ( $table_diff['changed_fields'] ) )
				{
					foreach ( $table_diff['changed_fields'] as $field_name => $changed_field )
					{
                        $this->appendSQLComments( $changed_field, $sql );
						$sql .= $this->generateAlterFieldSql( $table, $field_name, $changed_field, $params );
					}
				}
				if ( isset ( $table_diff['removed_fields'] ) )
				{
					foreach ( $table_diff['removed_fields'] as $field_name => $removed_field)
					{
                        $this->appendSQLComments( $removed_field, $sql );
						$sql .= $this->generateDropFieldSql( $table, $field_name, $params );
					}
				}

				if ( isset ( $table_diff['removed_indexes'] ) )
				{
					foreach ( $table_diff['removed_indexes'] as $index_name => $removed_index)
					{
                        $this->appendSQLComments( $removed_index, $sql );
                        $sql .= $this->generateDropIndexSql( $table, $index_name, $removed_index, $params );
					}
				}
				if ( isset ( $table_diff['added_indexes'] ) )
				{
					foreach ( $table_diff['added_indexes'] as $index_name => $added_index)
					{
                        $this->appendSQLComments( $added_index, $sql );
						$sql .= $this->generateAddIndexSql( $table, $index_name, $added_index, $params );
					}
				}

				if ( isset ( $table_diff['changed_indexes'] ) )
				{
					foreach ( $table_diff['changed_indexes'] as $index_name => $changed_index )
					{
                        $this->appendSQLComments( $changed_index, $sql );
						$sql .= $this->generateDropIndexSql( $table, $index_name, $params );
						$sql .= $this->generateAddIndexSql( $table, $index_name, $changed_index, $params );
					}
				}
			}
		}
		if ( isset( $differences['new_tables'] ) )
		{
			foreach ( $differences['new_tables'] as $table => $table_def )
			{
                $this->appendSQLComments( $table_def, $sql );
                $sql .= $this->generateTableSchema( $table, $table_def, $params );
            }
        }
        if ( isset( $differences['removed_tables'] ) )
        {
            foreach ( $differences['removed_tables'] as $table => $table_def )
            {
                $this->appendSQLComments( $table_def, $sql );
                $sql .= $this->generateDropTable( $table, $params );
            }
        }
        return $sql;
    }

    /*!
     \pure
     \protected
	 */
	function generateTableSchema( $table, $table_def )
    {
    }

    /*!
     \virtual
     \protected
    */
    function generateTableInsert( $tableName, $tableDef, $dataEntries, $params )
    {
        $diffFriendly = $params['diff_friendly'];
        $multiInsert = $params['allow_multi_insert'] ? $this->isMultiInsertSupported() : false;

        $sql = '';
        $defText = '';
        $entryIndex = 0;
        foreach ( $dataEntries['fields'] as $fieldName )
        {
            if ( !isset( $tableDef['fields'][$fieldName] ) )
                continue;
            if ( $entryIndex > 0 )
            {
                if ( $diffFriendly )
                {
                    $defText .= ",\n  ";
                }
                else
                {
                    $defText .= ", ";
                }
            }
            $defText .= $fieldName;
            ++$entryIndex;
        }

        if ( $multiInsert )
        {
            if ( $diffFriendly )
            {
                $sql .= "INSERT INTO $tableName (\n  $defText\n)\nVALUES\n";
            }
            else
            {
                $sql .= "INSERT INTO $tableName ($defText) VALUES ";
            }
        }
        $insertIndex = 0;
        foreach ( $dataEntries['rows'] as $row )
        {
            if ( $multiInsert and $insertIndex > 0 )
            {
                if ( $diffFriendly )
                    $sql .= "\n,\n";
                else
                    $sql .= ", ";
            }
            $dataText = '';
            $entryIndex = 0;
            foreach ( $dataEntries['fields'] as $fieldName )
            {
                if ( !isset( $tableDef['fields'][$fieldName] ) )
                    continue;
                if ( $entryIndex > 0 )
                {
                    if ( $diffFriendly )
                    {
                        $dataText .= ",\n  ";
                    }
                    else
                    {
                        $dataText .= ",";
                    }
                }
                $dataText .= $this->generateDataValueTextSQL( $tableDef['fields'][$fieldName], $row[$entryIndex] );
                ++$entryIndex;
            }
            if ( $multiInsert )
            {
                if ( $diffFriendly )
                {
                    $sql .= "(\n  $dataText\n)";
                }
                else
                {
                    $sql .= "($dataText)";
                }
                ++$insertIndex;
            }
            else
            {
                if ( $diffFriendly )
                {
                    $sql .= "INSERT INTO $tableName (\n$defText\n) VALUES (\n$dataText\n);\n";
                }
                else
                {
                    $sql .= "INSERT INTO $tableName ($defText) VALUES ($dataText);\n";
                }
            }
        }
        if ( $multiInsert )
        {
            $sql .= "\n;\n";
        }
        return $sql;
    }

    /*!
     \virtual
     \protected
    */
    function generateDataValueTextSQL( $fieldDef, $value )
    {
        if ( $fieldDef['type'] == 'auto_increment' or
             $fieldDef['type'] == 'int' or
             $fieldDef['type'] == 'float' )
            return (string)$value;
        else if ( is_string( $value ) )
            return "'" . $this->DBInstance->escapeString( $value ) . "'";
        else
            return (string)$value;
    }

    /*!
     \pure
     \protected
	 */
	function generateAlterFieldSql( $table_name, $field_name, $def )
	{
    }

    /*!
     \pure
     \protected
	 */
	function generateAddFieldSql( $table_name, $field_name, $def )
	{
    }

    /*!
	 \private
    */
	function generateDropFieldSql( $table_name, $field_name )
	{
		$sql = "ALTER TABLE $table_name DROP COLUMN $field_name";

		return $sql . ";\n";
	}

    /*!
     Appends any comments found in \a $def to SQL text \a $sql as SQL comments.
     \return \c true if any comments were added.
    */
    function appendSQLComments( $def, &$sql )
    {
        if ( isset( $def['comments'] ) )
        {
            if ( count( $def['comments'] ) > 0 )
                $sql .= "\n";
            foreach ( $def['comments'] as $comment )
            {
                $commentLines = explode( "\n", $comment );
                foreach ( $commentLines as $commentLine )
                {
                    $sql .= '-- ' . $commentLine . "\n";
                }
            }
            return true;
        }
        return false;
    }

    /*!
     \virtual
     \protected
     \return \c true if the schema system supports multi inserts.
             The default is to return \c false.
    */
    function isMultiInsertSupported()
    {
        return false;
    }

    /// eZDB instance
    var $DBInstance;
}

?>
