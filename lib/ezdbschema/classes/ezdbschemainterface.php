<?php
//
// Created on: <21-Apr-2004 11:04:30 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
 Database schema abstraction layer.
*/

/*! \defgroup eZDbSchema Database schema abstraction layer */

/*!
  \class eZDBSchemaInterface ezdbschemainterface.php
  \ingroup eZDbSchema
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

     \sa eZDB
     */
    function eZDBSchemaInterface( $params )
    {
        $this->DBInstance = $params['instance'];
        $this->Schema = false;
        $this->Data = false;
        if ( isset( $params['schema'] ) )
            $this->Schema = $params['schema'];
        if ( isset( $params['data'] ) )
            $this->Data = $params['data'];
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
        $params = array_merge( array( 'meta_data' => false,
                                      'format' => 'generic' ),
                               $params );

        if ( $this->Data === false )
        {
            if ( $schema === false )
                $schema = $this->schema( $params );

            // We need to transform schema to local format for data to be fetched correctly.
            if ( $schema['_info']['format'] == 'generic' )
                $this->transformSchema( $schema, true );

            $data = array();
            foreach ( $schema as $tableName => $tableInfo )
            {
                // Skip the information array, this is not a table
                if ( $tableName == '_info' )
                    continue;

                if ( is_array( $tableNameList ) and
                     !in_array( $tableName, $tableNameList ) )
                    continue;

                $tableEntry = $this->fetchTableData( $tableInfo );
                if ( count( $tableEntry['rows'] ) > 0 )
                    $data[$tableName] = $tableEntry;
            }
            $this->transformData( $data, $params['format'] == 'local' );
            ksort( $data );
            $this->Data = $data;
        }
        else
        {
            $this->transformData( $this->Data, $params['format'] == 'local' );
            $data = $this->Data;
        }

        return $data;
    }

    /*!
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
            $rowData = array();
            foreach ( $tableInfo['fields'] as $fieldName => $field )
            {
                if ( $field['type'] == 'char' )
                {
                    $rowData[$fieldName] = str_pad( $row[$fieldName], $field['length'], ' ' );
                }
                else
                {
                    $rowData[$fieldName] = $row[$fieldName];
                }
            }
            $resultArray[] = array_values( $rowData );
        }
        return array( 'fields' => $fields,
                      'rows' => $resultArray );
    }

    /*!
     \pure
     Write upgrade sql to file

     \param differences array
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
            $this->transformSchema( $schema, true );
            if ( $includeSchema )
            {
                fputs( $fp, $this->generateSchemaFile( $schema, $params ) );
            }
            if ( $includeData )
            {
                $data = $this->data( $schema );
                $this->transformData( $data, true );
                fputs( $fp, $this->generateDataFile( $schema, $data, $params ) );
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
                if ( isset( $schema['_info'] ) )
                {
                    $info = $schema['_info'];
                    unset( $schema['_info'] );
                    $schema['_info'] = $info;
                }
                fputs( $fp, '$schema = ' . var_export( $schema, true ) . ";\n" );
            }
            if ( $includeData )
            {
                $data = $this->data( $schema );
                fputs( $fp, "// This array contains the database data\n" );
                if ( isset( $data['_info'] ) )
                {
                    $info = $data['_info'];
                    unset( $data['_info'] );
                    $data['_info'] = $info;
                }
                fputs( $fp, '$data = ' . var_export( $data, true ) . ";\n" );
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
      Insert PHP schema to the current database instance by running one SQL at a time.

      \param $params Optional parameter which controls what to insert:
                     - schema - Whether to insert the schema or not, default is \c true.
                     - data - Whether to insert the data or not, default is \c false
      \return \c false if the schema could not be inserted, \c true if successful
    */
    function insertSchema( $params = array() )
    {
        $params = array_merge( array( 'schema' => true,
                                      'data' => false ),
                               $params );

        if ( !is_object( $this->DBInstance ) )
        {
            eZDebug::writeError( "No database instance is available, cannot insert", 'eZDBSchemaInterface::insertSchema' );
            return false;
        }

        $oldOutputSQL = $this->DBInstance->OutputSQL;
        $this->DBInstance->OutputSQL = false;

        $includeSchema = $params['schema'];
        $includeData = $params['data'];
        $params['format'] = 'local';
        $schema = $this->schema( $params );
        if ( $includeSchema )
        {
            foreach ( $schema as $tableName => $table )
            {
                // Skip the information array, this is not a table
                if ( $tableName == '_info' )
                    continue;

                $sqlList = $this->generateTableSQLList( $tableName, $table, $params, false );
                foreach ( $sqlList as $sql )
                {
                    if ( !$this->DBInstance->query( $sql ) )
                    {
                        eZDebug::writeError( "Failed inserting the SQL:\n$sql" );
                        return false;
                    }
                }
            }
        }
        if ( $includeData )
        {
            $data = $this->data( $schema, false, array( 'format' => 'local' ) );

            $this->DBInstance->begin();

            foreach ( $schema as $tableName => $table )
            {
                // Skip the information array, this is not a table
                if ( $tableName == '_info' )
                    continue;

                if ( !isset( $data[$tableName] ) )
                {
                    continue;
                }

                $sqlList = $this->generateTableInsertSQLList( $tableName, $table, $data[$tableName], $params, false );
                foreach ( $sqlList as $sql )
                {
                    if ( !$this->DBInstance->query( $sql ) )
                    {
                        eZDebug::writeError( "Failed inserting the SQL:\n$sql" );
                        $this->DBInstance->rollback();
                        return false;
                    }
                }
            }

            $this->DBInstance->commit();

            // Update sequences for databases that require this
            if ( method_exists( $this->DBInstance, 'correctSequenceValues' ) )
            {
                $status = $this->DBInstance->correctSequenceValues();
                if ( !$status )
                    return false;
            }
        }
        $this->DBInstance->OutputSQL = $oldOutputSQL;
        return true;
    }

    /*!
     \private
     \param schema database schema
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
            // Skip the info structure, this is not a table
            if ( $tableName == '_info' )
                continue;

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
     \param schema database schema
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
                        $changed_field_def = $changed_field['field-def'];
                        $diffPrams = array_merge( $params, array( 'different-options' => $changed_field['different-options'] ) );
                        $this->appendSQLComments( $changed_field_def, $sql );
                        $sql .= $this->generateAlterFieldSql( $table, $field_name, $changed_field_def, $diffPrams );
                        unset( $diffPrams );
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
                        //eZDebug::writeDebug( $changed_index, "changed index $index_name" );
                        $this->appendSQLComments( $changed_index, $sql );
                        $sql .= $this->generateDropIndexSql( $table, $index_name, $changed_index, $params );
                        $sql .= $this->generateAddIndexSql( $table, $index_name, $changed_index, $params );
                        //eZDebug::writeDebug( 'qqq' );
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
                           - trigger - List of triggers
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
     \protected

     \note Calls generateTableInsertSQLList and joins the SQLs to a string
    */
    function generateTableInsert( $tableName, $tableDef, $dataEntries, $params )
    {
        return join( "\n", $this->generateTableInsertSQLList( $tableName, $tableDef, $dataEntries, $params, true ) );
    }

    /*!
     \protected
    */
    function generateTableInsertSQLList( $tableName, $tableDef, $dataEntries, $params, $withClosure = true )
    {
        $diffFriendly = isset( $params['diff_friendly'] ) ? $params['diff_friendly'] : false;
        $multiInsert = ( isset( $params['allow_multi_insert'] ) and $params['allow_multi_insert'] ) ? $this->isMultiInsertSupported() : false;

        // Make sure we don't generate SQL when there are no rows
        if ( count( $dataEntries['rows'] ) == 0 )
            return '';

        $sqlList = array();
        $sql = '';
        $defText = '';
        $entryIndex = 0;
        foreach ( $dataEntries['fields'] as $fieldName )
        {
            if ( !isset( $tableDef['fields'][$fieldName] ) )
                continue;
            if ( $entryIndex == 0 )
            {
                if ( $diffFriendly )
                {
                    $defText .= "  ";
                }
            }
            else
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
                if ( $entryIndex == 0 )
                {
                    if ( $diffFriendly )
                    {
                        $dataText .= "  ";
                    }
                }
                else
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
                    $sqlList[] = "INSERT INTO $tableName (\n$defText\n) VALUES (\n$dataText\n)" . ( $withClosure ? ";" : "" );
                }
                else
                {
                    $sqlList[] = "INSERT INTO $tableName ($defText) VALUES ($dataText)" . ( $withClosure ? ";" : "" );
                }
            }
        }
        if ( $multiInsert )
        {
            if ( $withClosure )
                $sql .= "\n;";
            $sqlList[] = $sql;
        }
        return $sqlList;
    }

    /*!
     \protected
    */
    function generateDataValueTextSQL( $fieldDef, $value )
    {
        if ( $fieldDef['type'] == 'auto_increment' or
             $fieldDef['type'] == 'int' or
             $fieldDef['type'] == 'float' )
        {
            if ( $value === null or
                 $value === false )
                return "NULL";
            $value = (int)$value;
            $value = (string)$value;
            return $value;
        }
        else if ( is_string( $value ) )
        {
            return "'" . $this->escapeSQLString( $value ) . "'";
        }
        else
        {
            if ( $value === null or
                 $value === false )
                return "NULL";
            return (string)$value;
        }
    }

    /*!
     \pure
     This escapes the string according to the current database type and returns it.
     \note The default just returns the value so it must be reimplemented.
    */
    function escapeSQLString( $value )
    {
        return $value;
    }

    /*!
     \pure
     \protected
     */
    function generateAlterFieldSql( $table_name, $field_name, $def, $params )
    {
    }

    /*!
     \pure
     \protected
     */
    function generateAddFieldSql( $table_name, $field_name, $def, $params )
    {
    }

    /*!
     \private
    */
    function generateDropFieldSql( $table_name, $field_name, $params )
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
        $ini = eZINI::instance( 'dbschema.ini' );

        if ( !$ini )
        {
            eZDebug::writeError( "Error loading $schemaType schema transformation rules" );
            return false;
        }

        $transformationRules = array();

        if ( $ini->hasVariable( $schemaType, 'ColumnNameTranslation' ) )
            $transformationRules['column-name'] = $ini->variable( $schemaType, 'ColumnNameTranslation' );

        if ( $ini->hasVariable( $schemaType, 'ColumnTypeTranslation' ) )
        {
            $transformationRules['column-type'] = $ini->variable( $schemaType, 'ColumnTypeTranslation' );

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

        if ( $ini->hasVariable( $schemaType, 'IndexNameTranslation' ) )
        {
            $tmpIdxNameTranslations = $ini->variable( $schemaType, 'IndexNameTranslation' );

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
                    $transformationRules['index-name'][] = array( $tableName, $genericIdxName, $localIdxName );

                }
            }
            unset( $tmpIdxNameTranslations );
        }

        if ( $ini->hasVariable( $schemaType, 'ColumnOptionTranslations' ) )
        {
            $transformationRules['column-option'] = array();
            foreach( $ini->variable( $schemaType, 'ColumnOptionTranslations' ) as $key => $val )
            {
                list( $tableName, $colName ) = explode( '.', $key );
                $colOptOverride = $val;
                if ( !$tableName || !$colName || !$colOptOverride )
                {
                    eZDebug::writeWarning( "Malformed column option translation rule: $key => $val" );
                    continue;
                }
                $transformationRules['column-option'][] = array( $tableName, $colName, $colOptOverride );
            }
        }

        // prevent PHP warnings when cycling through the rules
        foreach ( array( 'column-name', 'column-type', 'column-option', 'index-name' ) as $rulesType )
        {
            if( !isset( $transformationRules[$rulesType] ) )
                $transformationRules[$rulesType] = array();
        }

        return $transformationRules;
    }

    /*!
    \protected
    \return true on success, false otherwise

    Transforms database schema to the given direction, applying the transformation rules.
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

        // load the schema transformation rules
        $schemaType = $this->schemaType();
        $schemaTransformationRules = eZDBSchemaInterface::loadSchemaTransformationRules( $schemaType );
        if ( $schemaTransformationRules === false )
            return false;

        // transform column names
        foreach ( $schemaTransformationRules['column-name'] as $key => $val )
        {
            list( $tableName, $genericColName ) = explode( '.', $key );
            $localColName = $val;

            if ( $toLocal )
            {
                $searchColName = $genericColName;
                $replacementColName = $localColName;
            }
            else
            {
                $searchColName = $localColName;
                $replacementColName = $genericColName;
            }

            if ( !isset( $schema[$tableName] ) )
                continue;

            // transform column names in tables
            if ( isset( $schema[$tableName]['fields'][$searchColName] )  )
            {
                $schema[$tableName]['fields'][$replacementColName] = $schema[$tableName]['fields'][$searchColName];
                unset( $schema[$tableName]['fields'][$searchColName] );
                ksort( $schema[$tableName]['fields'] );
                eZDebugSetting::writeDebug( 'lib-dbschema-transformation', '',
                                            "transformed table column name $tableName.$searchColName to $replacementColName" );
            }


            // transform column names in indexes
            foreach ( $schema[$tableName]['indexes'] as $indexName => $indexSchema )
            {
                if ( ( $key = array_search( $searchColName, $indexSchema['fields'] ) ) !== false )
                {
                    $schema[$tableName]['indexes'][$indexName]['fields'][$key] = $replacementColName;
                    eZDebugSetting::writeDebug( 'lib-dbschema-transformation', '',
                                                "transformed index columnn name $indexName.$searchColName to $replacementColName" );
                }
            }
        }

        // tranform column types
        foreach ( $schemaTransformationRules['column-type'] as $key => $val )
        {
            list( $tableName, $colName ) = explode( '.', $key );
            list( $genericType, $localType ) = $val;

            if ( !isset( $schema[$tableName] ) )
                continue;

            preg_match( '/(\w+)\((\d+)\)/', $localType, $matches );
            $localLength = ( count($matches) == 3 ) ? $matches[2] : null;
            if ( count($matches) == 3 )
                $localType = $matches[1];

            preg_match( '/(\w+)\((\d+)\)/', $genericType, $matches );
            $genericLength = ( count($matches) == 3 ) ? $matches[2] : null;
            if ( count($matches) == 3 )
                $genericType = $matches[1];

            if ( !isset( $schema[$tableName]['fields'][$colName] ) )
                continue;

            $fieldSchema = $schema[$tableName]['fields'][$colName];

            if ( $toLocal )
            {
                $searchType        = $genericType;
                $searchLength      = $genericLength;
                $replacementType   = $localType;
                $replacementLength = $localLength;
            }
            else // to generic
            {
                $searchType        = $localType;
                $searchLength      = $localLength;
                $replacementType   = $genericType;
                $replacementLength = $genericLength;
            }

            $fieldSchema['type'] = $replacementType;
            if ( $replacementLength !== null )
            {
                $fieldSchema['length'] = $replacementLength;
            }
            else
            {
                unset( $fieldSchema['length'] );
            }

            eZDebugSetting::writeDebug( 'lib-dbschema-transformation', '',
                                        "transformed table column type $schemaType:$tableName.$colName from $searchType to $replacementType" );

            $schema[$tableName]['fields'][$colName] = $fieldSchema;
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
                $searchIdxName      = $genericIdxName;
                $replacementIdxName = $localIdxName;
            }
            else
            {
                $searchIdxName      = $localIdxName;
                $replacementIdxName = $genericIdxName;
            }

            if ( !isset( $schema[$tableName] ) )
                continue;

            if ( isset( $schema[$tableName]['indexes'][$searchIdxName] )  )
            {
                eZDebugSetting::writeDebug( 'lib-dbschema-transformation', '',
                                            "replaced $tableName.$searchIdxName => $replacementIdxName" );
                $schema[$tableName]['indexes'][$replacementIdxName] = $schema[$tableName]['indexes'][$searchIdxName];
                unset( $schema[$tableName]['indexes'][$searchIdxName] );
                ksort( $schema[$tableName]['indexes'] );
            }

        }

        // Transform table column options
        foreach ( $schemaTransformationRules['column-option'] as $colOptTransRule )
        {
            list( $tableName, $colName, $colOptOverride ) = $colOptTransRule;

            if ( !isset( $schema[$tableName] ) || !isset( $schema[$tableName]['fields'][$colName] ) )
                continue;

            $fieldSchema = $schema[$tableName]['fields'][$colName];

            switch ( $colOptOverride )
            {
                case 'null':
                {
                    if ( $toLocal )
                    {
                        // remove "NOT NULL" requirement
                        unset( $fieldSchema['not_null'] );
                        eZDebugSetting::writeDebug( 'lib-dbschema-transformation', '',
                                                    "transformed table column option: $schemaType:$tableName.$colName set to NULL" );
                    }
                    else
                    {
                        // add "NOT NULL" requirement
                        $fieldSchema['not_null'] = '1';
                        eZDebugSetting::writeDebug( 'lib-dbschema-transformation', '',
                                                    "transformed table column option: $schemaType:$tableName.$colName set to NOT NULL" );
                    }

                    // FIXME: ugly hack preserving keys order in the field schema array, just to be diff-friendly
                    {
                        $tmp = $fieldSchema['default'];
                        unset( $fieldSchema['default'] );
                        $fieldSchema['default'] = $tmp;
                    }
                } break;
                default:
                {
                    eZDebug::writeWarning( "Column option override '$colOptOverride' is not supported" );
                } break;
            }

            $schema[$tableName]['fields'][$colName] = $fieldSchema;
        }


        return true;
    }

    /*!
    \protected
    \return true on success, false otherwise

    Transforms database data to the given direction, applying the transformation rules.
    */
    function transformData( &$data, /* bool */ $toLocal )
    {
        // Check if it is already in correct format
        if ( isset( $data['_info']['format'] ) )
        {
            if ( $data['_info']['format'] == ( $toLocal ? 'local' : 'generic' ) )
                return true;
        }

        // Set the new format it will get
        $data['_info']['format'] = $toLocal ? 'local' : 'generic';

        // load the schema transformation rules
        $schemaType = $this->schemaType();
        $schemaTransformationRules = eZDBSchemaInterface::loadSchemaTransformationRules( $schemaType );
        if ( $schemaTransformationRules === false )
            return false;

        // transform column names
        foreach ( $schemaTransformationRules['column-name'] as $key => $val )
        {
            list( $tableName, $genericColName ) = explode( '.', $key );
            $localColName = $val;

            if ( $toLocal )
            {
                $searchColName = $genericColName;
                $replacementColName = $localColName;
            }
            else
            {
                $searchColName = $localColName;
                $replacementColName = $genericColName;
            }

            if ( !isset( $data[$tableName] ) )
                continue;

            // transform column names in tables
            $fieldsData = $data[$tableName]['fields'];
            foreach ( $fieldsData as $key => $fieldName )
            {
                if ( $searchColName == $fieldName )
                {
                    $data[$tableName]['fields'][$key] = $replacementColName;
                    eZDebugSetting::writeDebug( 'lib-dbschema-data-transformation', '',
                                                "transformed table column name $tableName.$searchColName to $replacementColName" );
                }
            }
        }

        return true;
    }

    /// eZDB instance
    public $DBInstance;
    public $Schema;
    public $Data;
}

?>
