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
    function schema( $params = array() )
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
    function data( $schema = false, $tableNameList = false, $params = array() )
    {
        if ( $schema === false )
            $schema = $this->schema( $params );
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
            $schema = $this->schema( $params );
            $this->transformSchemaToLocal( $schema );
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
            $schema = $this->schema( $params );
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
            $schema = $this->schema( $params );
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
            // Skip the info structure, this is not a table
            if ( $table == '_info' )
                continue;

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
     Generates the necessary SQLs to create the table and returns them all in an array.

     \param $tableName The table name
     \param $table The table structure, see class definition for more details
     \param $params An associative array with optional parameters which controls the output of SQLs
     \param $separateTypes If \c true then the returned array must be an associative array
                           containing the SQL arrays split into multiple groups.
                           The groups are:
                           - sequences - List of sequences
                           - tables - List of tables
                           - indexes - List of indexes
                           - constraints - List of constraints/primary keys
                           - other - Other SQLs that doesn't fit into the above
                           .
                           Each group can be omitted and will be run in order.

     \note Each SQL in the array will be without a semi-colon
     \sa generateTableSchema()
	 */
	function generateTableSQLList( $tableName, $table, $params, $separateTypes )
    {
        return false;
    }

    /*!
     \pure
     \protected
     Generates the necessary SQLs to create the table and returns them all in a string.

     \param $tableName The table name
     \param $table The table structure, see class definition for more details

     \note The SQLs will be ended with a semi-colon.
     \sa generateTableSQLList()
    */
	function generateTableSchema( $tableName, $table, $params )
    {
        return false;
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

    /*!
     \pure

     \return Identifier for schema type as string.
     Examples: 'mysql', 'postgresql', 'oracle'
     \sa schemaName()
    */
    function schemaType()
    {
    }

    /*!
     \pure

     \return Displayable name for schema type as string.
     Examples: 'MySQL', 'PostgreSQL', 'Oracle'
     \sa schemaType()
    */
    function schemaName()
    {
    }

    /*!
     \private
     \static
     \return array of transformation rules on success, false otherwise
     */
    function loadSchemaTransformationRules( $schemaType )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance( 'dbschema.ini' );

        if ( !$ini )
        {
            eZDebug::writeError( "Error loading $schemaType schema transformation rules" );
            return false;
        }

        $transformationRules = array();

        if ( $ini->hasVariable( $schemaType, 'ColumnNameTranslation' ) )
            $transformationRules['column-name'] =& $ini->variable( $schemaType, 'ColumnNameTranslation' );

        if ( $ini->hasVariable( $schemaType, 'ColumnTypeTranslation' ) )
        {
            $transformationRules['column-type'] =& $ini->variable( $schemaType, 'ColumnTypeTranslation' );

            // substitute values like "type1;type2" with an appropriate arrays
            if ( is_array( $transformationRules['column-type'] ) )
            {
                foreach ( $transformationRules['column-type'] as $key => $val )
                {
                    $types = explode( ';', $val );
                    $transformationRules['column-type'][$key] = $types;
                }
            }
        }

        $indexTranslations = array();
        if ( $ini->hasVariable( $schemaType, 'IndexTranslation' ) )
        {
            $translations = $ini->variable( $schemaType, 'IndexTranslation' );
            foreach ( $translations as $combinedName => $translation )
            {
                list( $tableName, $indexName ) = explode( '.', $combinedName );
                $indexTranslations[$tableName][$indexName] = array();
                $fields = explode( ';', $translation );
                foreach ( $fields as $field )
                {
                    $entries = explode( '.', $field );
                    $fieldName = $entries[0];
                    $fieldData = array();
                    for ( $i = 1; $i < count( $entries ); ++$i )
                    {
                        list( $metaName, $metaValue ) = explode( '/', $entries[$i], 2 );
                        if ( is_numeric( $metaValue ) )
                            $metaValue = (int)$metaValue;
                        $fieldData[$metaName] = $metaValue;
                    }
                    $indexTranslations[$tableName][$indexName][$fieldName] = $fieldData;
                }
            }
        }
        $transformationRules['index-field'] = $indexTranslations;

        $tableTranslations = array();
        if ( $ini->hasVariable( $schemaType, 'TableOptionTranslation' ) )
        {
            $translations = $ini->variable( $schemaType, 'TableOptionTranslation' );
            foreach ( $translations as $tableName => $optionTexts )
            {
                $tableTranslations[$tableName] = array();
                $options = explode( ';', $optionTexts );
                $optionData = array();
                foreach ( $options as $option )
                {
                    list( $metaName, $metaValue ) = explode( '/', $option, 2 );
                    if ( is_numeric( $metaValue ) )
                        $metaValue = (int)$metaValue;
                    $optionData[$metaName] = $metaValue;
                    $tableTranslations[$tableName] = $optionData;
                }
            }
        }
        $transformationRules['table-option'] = $tableTranslations;

        if ( $ini->hasVariable( $schemaType, 'FieldsWithoutDefaultValue' ) )
            $transformationRules['column-empty-default'] =& $ini->variable( $schemaType, 'FieldsWithoutDefaultValue' );

        if ( $ini->hasVariable( $schemaType, 'IndexNameTranslation' ) )
        {
            //$transformationRules['index-name'] =& $ini->variable( $schemaType, 'IndexNameTranslation' );
            $tmpIdxNameTranslations =& $ini->variable( $schemaType, 'IndexNameTranslation' );

            if ( is_array( $tmpIdxNameTranslations ) )
            {
                foreach ( $tmpIdxNameTranslations as $key => $val )
                {
                    list( $tableName, $genericIdxName ) = explode( '.', $key );
                    $localIdxName = $val;
                    if ( !$tableName || !$genericIdxName || !$localIdxName )
                    {
                        eZDebug::writeWarning( "Malformed index name translation rule: $key => $val" );
                        continue;
                    }
                    /*$transformationRules['index-name'][] = array( 'table-name' => $tableName,
                                                                  'generic-idx-name' => $genericIdxName,
                                                                  'local-idx-name' => $localIdxName );*/
                    $transformationRules['index-name'][] = array( $tableName, $genericIdxName, $localIdxName );

                }
            }
            unset( $tmpIdxNameTranslations );
        }

        // prevent PHP warnings when cycling through the rules
        foreach ( array( 'column-name', 'column-type', 'column-empty-default', 'index-name' ) as $rulesType )
        {
            if( !isset( $transformationRules[$rulesType] ) )
                $transformationRules[$rulesType] = array();
        }

        return $transformationRules;
    }

    /*!
    \virtual
    \protected
    */
    function transformSchemaToGeneric( &$schema )
    {
        return $this->transformSchema( $schema, false );
    }

    /*!
    \virtual
    \protected
    */
    function transformSchemaToLocal( &$schema )
    {
        return $this->transformSchema( $schema, true );
    }

    /*!
    \private
    \return true on success, false otherwise

    Transforms database schema to the given direction, either applying local hacks or removing them.
    */
    function transformSchema( &$schema, /* bool */ $toLocal )
    {
        // Check if it is already in correct format
        if ( isset( $schema['_info']['format'] ) )
        {
            if ( $schema['_info']['format'] == ( $toLocal ? 'local' : 'generic' ) )
                return true;
        }

        // Set the new format it will get
        $schema['_info']['format'] = $toLocal ? 'local' : 'generic';

        /* replaces array key $oldKey with $newKey, preserving keys order in array */
        if ( !function_exists( 'arrayreplacekey' ) )
        {
            function arrayReplaceKey( &$a, $oldKey, $newKey )
            {
                $tmpArray = array();
                foreach ( $a as $key => $val )
                {
                    if ( $key == $oldKey )
                        $tmpArray[$newKey] =& $a[$key];
                    else
                        $tmpArray[$key]    =& $a[$key];
                }
                $a = $tmpArray;
            }
        }

        // load the schema transformation rules
        $schemaType = $this->schemaType();
        $schemaTransformationRules =& eZDBSchemaInterface::loadSchemaTransformationRules( $schemaType );
        if ( $schemaTransformationRules === false )
            return false;

        // transform column names
        foreach ( $schemaTransformationRules['column-name'] as $key => $val )
        {
            list( $tableName, $genericColName ) = explode( '.', $key );
            $localColName = $val;

            if ( $toLocal )
            {
                $searchColName =& $genericColName;
                $replacementColName =& $localColName;
            }
            else
            {
                $searchColName =& $localColName;
                $replacementColName =& $genericColName;
            }

            if ( !isset( $schema[$tableName] ) )
                continue;

            // transform column names in tables
            $fieldsSchema =& $schema[$tableName]['fields'];
            if ( isset( $fieldsSchema[$searchColName] )  )
            {
                arrayReplaceKey( $schema[$tableName]['fields'], $searchColName, $replacementColName );
                //eZDebug::writeDebug( "transformed table column name $tableName.$searchColName to $replacementColName" );
            }

            // transform column names in indexes
            $indexesSchema =& $schema[$tableName]['indexes'];
            foreach ( $indexesSchema as $indexName => $indexSchema )
            {
                if ( ( $key = array_search( $searchColName, $indexSchema['fields'] ) ) !== false )
                {
                    $indexesSchema[$indexName]['fields'][$key] = $replacementColName;
                    //eZDebug::writeDebug( "transformed index field $schemaType:$indexName.$searchColName to $replacementColName" );
                }
            }
        }

        // tranform columns types
        foreach ( $schemaTransformationRules['column-type'] as $key => $val )
        {
            list( $tableName, $colName ) = explode( '.', $key );
            list( $genericType, $localType ) = $val;

            if ( !isset( $schema[$tableName] ) )
                continue;

            preg_match( '/(\w+)\((\d+)\)/', $localType, $matches );
            $localLength = ( count($matches) == 2 ) ? $matches[2] : null;
            if ( count($matches) == 2 )
                $localType = $matches[1];

            preg_match( '/(\w+)\((\d+)\)/', $genericType, $matches );
            $genericLength = ( count($matches) == 2 ) ? $matches[2] : null;
            if ( count($matches) == 2 )
                $genericType = $matches[1];

            $fieldsSchema =& $schema[$tableName]['fields'];
            if ( !isset( $schema[$tableName]['fields'][$colName] ) )
                continue;

            $fieldSchema =& $fieldsSchema[$colName];

            if ( $toLocal )
            {
                $searchType        =& $genericType;
                $searchLength      =& $genericLength;
                $replacementType   =& $localType;
                $replacementLength =& $localLength;
            }
            else // to generic
            {
                $searchType        =& $localType;
                $searchLength      =& $localLength;
                $replacementType   =& $genericType;
                $replacementLength =& $genericLength;
            }

            $fieldSchema['type'] = $replacementType;
            if ( $replacementLength !== null )
                $fieldSchema['length'] = $replacementLength;
            else
                unset( $fieldSchema['length'] );
            //eZDebug::writeDebug( "transformed table column type $schemaType:$tableName.$colName from $searchType to $replacementType" );
        }

        // remove default field values (which are supposed to be empty due to bug in mysql)
        // FIXME: works only $toLocal == false
        foreach ( $schemaTransformationRules['column-empty-default'] as $tableCol )
        {
            list( $tableName, $colName ) = explode( '.', $tableCol );
            if ( !$tableName || !$colName ||
                 !array_key_exists( $tableName, $schema ) ||
                 !array_key_exists( $colName, $schema[$tableName]['fields'] ) )
            {
                continue;
            }

            unset( $schema[$tableName]['fields'][$colName]['default'] );
            //eZDebug::writeDebug( "removed default value from $schemaType:$tableName.$colName" );
        }

        // Find indexes that needs to be fixed
        foreach ( $schemaTransformationRules['index-field'] as $tableName => $indexes )
        {
            foreach ( $indexes as $indexName => $fields )
            {
                if ( !isset( $schema[$tableName]['indexes'][$indexName]['fields'] ) )
                    continue;

                $newFields = array();
                foreach ( $schema[$tableName]['indexes'][$indexName]['fields'] as $indexField )
                {
                    if ( !is_array( $indexField ) )
                    {
                        $indexField = array( 'name' => $indexField );
                    }
                    $fieldName = $indexField['name'];
                    if ( isset( $fields[$fieldName] ) )
                    {
                        if ( $toLocal )
                        {
                            $indexField = array_merge( $indexField,
                                                       $fields[$fieldName] );
                        }
                        else
                        {
                            foreach ( $fields[$fieldName] as $removeName => $removeValue )
                            {
                                unset( $indexField[$removeName] );
                            }
                        }
                    }

                    // Check if we have any entries other than 'name', if not we skip the array definition
                    if ( count( array_diff( array_keys( $indexField ), array( 'name' ) ) ) == 0 )
                    {
                        $indexField = $indexField['name'];
                    }
                    $newFields[] = $indexField;
                }
                $schema[$tableName]['indexes'][$indexName]['fields'] = $newFields;
            }
        }

        // Find tables that needs to fix their options
        foreach ( $schemaTransformationRules['table-option'] as $tableName => $options )
        {
            if ( !isset( $schema[$tableName] ) )
                continue;

            if ( !isset( $schema[$tableName]['options'] ) )
            {
                if ( $toLocal )
                    $schema[$tableName]['options'] = $options;
            }
            else
            {
                if ( $toLocal )
                {
                    $schema[$tableName]['options'] = array_merge( $schema[$tableName]['options'], $options );
                }
                else
                {
                    foreach ( $options as $optionName => $optionValue )
                    {
                        unset( $schema[$tableName]['options'][$optionName] );
                    }
                }
            }
        }

        // Transform index names
        foreach ( $schemaTransformationRules['index-name'] as $idxTransRule )
        {
            list( $tableName, $genericIdxName, $localIdxName ) = $idxTransRule;

            if ( $toLocal )
            {
                $searchIdxName      =& $genericIdxName;
                $replacementIdxName =& $localIdxName;
            }
            else
            {
                $searchIdxName      =& $localIdxName;
                $replacementIdxName =& $genericIdxName;
            }

            if ( !isset( $schema[$tableName] ) )
                continue;

            $fieldsSchema =& $schema[$tableName]['indexes'];
            if ( isset( $fieldsSchema[$searchIdxName] )  )
            {
                //eZDebug::writeDebug( "replaced $tableName.$searchIdxName => $replacementIdxName" );
                arrayReplaceKey( $schema[$tableName]['indexes'], $searchIdxName, $replacementIdxName );
            }

        }

        return true;
    }

    /// eZDB instance
    var $DBInstance;
}

?>
