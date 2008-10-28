<?php
//
// Created on: <09-Feb-2004 09:06:24 dr>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZPgsqlSchema ezpgsqlschema.php
  \ingroup eZDbSchema
  \brief Handles schemas for PostgreSQL

*/

class eZPgsqlSchema extends eZDBSchemaInterface
{
    const SHOW_TABLES_QUERY = '
        SELECT n.nspname as "Schema",
               c.relname as "Name",
               CASE c.relkind
                    WHEN \'r\' THEN \'table\'
                    WHEN \'v\' THEN \'view\'
                    WHEN \'i\' THEN \'index\'
                    WHEN \'S\' THEN \'sequence\'
                    WHEN \'s\' THEN \'special\'
               END as "Type",
               u.usename as "Owner"
        FROM pg_catalog.pg_class c
             LEFT JOIN pg_catalog.pg_user u ON u.usesysid = c.relowner
             LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
        WHERE c.relkind IN (\'r\',\'\')
              AND n.nspname NOT IN (\'pg_catalog\', \'pg_toast\')
              AND pg_catalog.pg_table_is_visible(c.oid)
        ORDER BY 1, 2';

    const FETCH_TABLE_OID_QUERY = '
        SELECT c.oid,
               n.nspname,
               c.relname
        FROM pg_catalog.pg_class c
             LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
        WHERE pg_catalog.pg_table_is_visible(c.oid)
              AND c.relname ~ \'^<<tablename>>$\'
        ORDER BY 2, 3';

    const FETCH_TABLE_DEF_QUERY = '
        SELECT a.attname,
               pg_catalog.format_type(a.atttypid, a.atttypmod),
               (SELECT substring(d.adsrc for 128) FROM pg_catalog.pg_attrdef d
                WHERE d.adrelid = a.attrelid AND d.adnum = a.attnum AND a.atthasdef) as default,
               a.attnotnull, a.attnum
        FROM pg_catalog.pg_attribute a
        WHERE a.attrelid = \'<<oid>>\' AND a.attnum > 0 AND NOT a.attisdropped
        ORDER BY a.attnum';

    const FETCH_INDEX_DEF_QUERY = '
        SELECT c.relname, i.*
        FROM pg_catalog.pg_index i, pg_catalog.pg_class c
        WHERE indrelid = \'<<oid>>\'
              AND i.indexrelid = c.oid';

    const FETCH_INDEX_COL_NAMES_QUERY = '
        SELECT a.attnum, a.attname
        FROM pg_catalog.pg_attribute a
        WHERE a.attrelid = \'<<indexrelid>>\' AND a.attnum IN (<<attids>>) AND NOT a.attisdropped
        ORDER BY a.attnum';

    /*!
     \reimp
     Constructor

     \param db instance
    */
    function eZPgsqlSchema( $db )
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

        if ( $this->Schema === false )
        {
            $resultArray = $this->DBInstance->arrayQuery( eZPgsqlSchema::SHOW_TABLES_QUERY );

            foreach( $resultArray as $row )
            {
                $table_name = $row['Name'];
                if ( !isset( $params['table_include'] ) or
                     ( is_array( $params['table_include'] ) and
                       in_array( $table_name, $params['table_include'] ) ) )
                {
                    $schema_table['name'] = $table_name;
                    $schema_table['fields'] = $this->fetchTableFields( $table_name, $params );
                    $schema_table['indexes'] = $this->fetchTableIndexes( $table_name, $params );

                    $schema[$table_name] = $schema_table;
                }
            }
            $this->transformSchema( $schema, $params['format'] == 'local' );
            ksort( $schema );
            $this->Schema = $schema;
        }
        else
        {
            $this->transformSchema( $this->Schema, $params['format'] == 'local' );
            $schema = $this->Schema;
        }
        return $schema;
    }

    /*!
     * \private
     */
    function fetchTableFields( $table, $params )
    {
        $fields = array();

        $resultArray = $this->DBInstance->arrayQuery( str_replace( '<<tablename>>', $table, eZPgsqlSchema::FETCH_TABLE_OID_QUERY ) );
        $row = $resultArray[0];
        $oid = $row['oid'];

        $resultArray = $this->DBInstance->arrayQuery( str_replace( '<<oid>>', $oid, eZPgsqlSchema::FETCH_TABLE_DEF_QUERY ) );
        foreach( $resultArray as $row )
        {
            $field = array();
            $autoinc = false;
            $field['type'] = $this->parseType( $row['format_type'], $field['length'] );
            if ( !$field['length'] )
            {
                unset( $field['length'] );
            }

            $field['not_null'] = 0;
            if ( $row['attnotnull'] == 't' )
            {
                $field['not_null'] = '1';
            }

            $field['default'] = false;
            if ( !$field['not_null'] )
            {
                if ( $row['default'] === null )
                    $field['default'] = null;
                else
                    $field['default'] = (string)$this->parseDefault ( $row['default'], $autoinc );
            }
            else
            {
                $field['default'] = (string)$this->parseDefault ( $row['default'], $autoinc );
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
                if ( $field['default'] === false )
                {
                    if ( $field['not_null'] )
                    {
                        $field['default'] = 0;
                    }
                }
                else if ( $field['type'] == 'int' )
                {
                    if ( $field['not_null'] or
                         is_numeric( $field['default'] ) )
                    {
                        $field['default'] = (int)$field['default'];
                    }
                }
                else if ( $field['type'] == 'float' )
                {
                    if ( $field['not_null'] or
                         is_numeric( $field['default'] ) )
                    {
                        $field['default'] = (float)$field['default'];
                    }
                }
            }
            else if ( in_array( $field['type'], $blobTypes ) )
            {
                // We do not want default for blobs.
                $field['default'] = false;
            }

            if ( $autoinc )
            {
                unset( $field['length'] );
                $field['not_null'] = 0;
                $field['default'] = false;
                $field['type'] = 'auto_increment';
            }

            if ( !$field['not_null'] )
                unset( $field['not_null'] );

            $fields[$row['attname']] = $field;
        }
        ksort( $fields );

        return $fields;
    }

    /*!
     * \private
     */
    function fetchTableIndexes( $table, $params )
    {
        $metaData = false;
        if ( isset( $params['meta_data'] ) )
        {
            $metaData = $params['meta_data'];
        }

        $indexes = array();

        $resultArray = $this->DBInstance->arrayQuery( str_replace( '<<tablename>>', $table, eZPgsqlSchema::FETCH_TABLE_OID_QUERY ) );
        $row = $resultArray[0];
        $oid = $row['oid'];

        $resultArray = $this->DBInstance->arrayQuery( str_replace( '<<oid>>', $oid, eZPgsqlSchema::FETCH_INDEX_DEF_QUERY ) );

        foreach( $resultArray as $row )
        {
            $fields = array();
            $kn = $row['relname'];

            $column_id_array = split( ' ', $row['indkey'] );
            if ( $row['indisprimary'] == 't' )
            {
                // If the name of the key matches our primary key naming standard
                // we change the name to PRIMARY, this makes it 100% similar to
                // primary keys in MySQL
                $correctName = $this->primaryKeyIndexName( $table, $kn, $column_id_array );
                if ( strlen( $correctName ) > 63 )
                {
                    eZDebug::writeError( "The index name '$correctName' (" . strlen( $correctName ) . ") exceeds 63 characters which is the PostgreSQL limit for names" );
                }
                if ( $kn == $correctName )
                {
                    $kn = 'PRIMARY';
                }

                // Extra meta data:
                // Include the name of the index that postgresql will use
                if ( $metaData )
                {
                    $indexes[$kn]['postgresql:name'] = $correctName;
                }

                $indexes[$kn]['type'] = 'primary';
            }
            else
            {
                $indexes[$kn]['type'] = $row['indisunique'] == 't' ? 'unique' : 'non-unique';
            }

            /* getting fieldnames requires yet another query and it doesn't return it 'in order' either.
             * grumbl, stupid pgsql :) */
            $att_ids = join( ', ',  $column_id_array );
            $query = str_replace( '<<indexrelid>>', $row['indrelid'], eZPgsqlSchema::FETCH_INDEX_COL_NAMES_QUERY );
            $query = str_replace( '<<attids>>', $att_ids, $query );

            $fieldsArray = $this->DBInstance->arrayQuery( $query );
            foreach( $fieldsArray as $fields_row )
            {
                $fields[$fields_row['attnum']] = $fields_row['attname'];
            }
            foreach ( $column_id_array as $rank => $id )
            {
                $indexes[$kn]['fields'][$rank] = $fields[$id];
            }
        }
        ksort( $indexes );

        return $indexes;
    }

    function parseType( $type_info, &$length_info )
    {
        preg_match ( "@([a-z ]*)(\(([0-9]*|[0-9]*,[0-9]*)\))?@", $type_info, $matches );
        if ( isset( $matches[3] ) )
        {
            $length_info = $matches[3];
            if ( is_numeric( $length_info ) )
                $length_info = (int)$length_info;
        }
        $type = $this->convertToStandardType ( $matches[1], $length_info );
        return $type;
    }

    function isTypeLengthSupported( $pgType )
    {
        switch ( $pgType )
        {
            case 'integer':
            case 'double precision':
            case 'real':
            {
                return false;
            } break;
        }
        return true;
    }

    function convertFromStandardType( $type, &$length )
    {
        switch ( $type )
        {
            case 'char':
            {
                if ( $length == 1 )
                {
                    return 'character';
                }
                else
                {
                    return 'character varying';
                }
            } break;
            case 'int':
            {
                return 'integer';
            } break;
            case 'varchar':
            {
                return 'character varying';
            } break;
            case 'longtext':
            {
                return 'text';
            } break;
            case 'mediumtext':
            {
                return 'text';
            } break;
            case 'text':
            {
                return 'text';
            } break;
            case 'float':
            case 'double':
            {
                return 'double precision';
            } break;
            case 'decimal':
            {
                return 'numeric';
            } break;

            default:
                die ( "ERROR UNHANDLED TYPE: $type\n" );
        }
    }

    /*!
     \private
     The name will consist of the table name and _pkey, since it is only allowed
     to have one primary key pre table that shouldn't be a problem.

     \return A string representing the name of the primary key index.
    */
    function primaryKeyIndexName( $tableName, $indexName, $fields )
    {
        return $tableName . '_pkey';
    }

    function convertToStandardType( $type, &$length )
    {
        switch ( $type )
        {
            case 'bigint':
            {
                return 'int';
            } break;
            case 'integer':
            {
                $length = 11;
                return 'int';
            } break;
            case 'character varying':
            {
                return 'varchar';
            } break;
            case 'text':
            {
                return 'longtext';
            } break;
            case 'double precision':
            {
                return 'float';
            } break;
            case 'character':
            {
                $lenght = 1;
                return 'char';
            } break;
            case 'numeric':
            {
                return 'decimal';
            } break;

            default:
                die ( "ERROR UNHANDLED TYPE: $type\n" );
        }
    }

    function parseDefault( $default, &$autoinc )
    {
        // postgresql 7.x: nextval('ezbasket_s'::text)
        // postgresql 8.x: nextval(('ezbasket_s'::text)::regclass)
        if ( preg_match( "@^nextval\(\(?'([a-z_]+_s)'::text\)@", $default, $matches ) )
        {
            $autoinc = 1;
            return '';
        }

        if ( preg_match( "@^\(?([^()]*)\)?::double precision@", $default, $matches ) )
        {
            return $matches[1];
        }

        if ( preg_match( "@^(.*)::bigint@", $default, $matches ) )
        {
            return $matches[1];
        }

        if ( preg_match( "@^'(.*)'::character\ varying$@", $default, $matches ) )
        {
            return $matches[1];
        }

        if ( preg_match( "@^'(.*)'::[a-zA-Z ]+$@", $default, $matches ) )
        {
            return $matches[1];
        }

        if ( preg_match( "@^'(.*)'$@", $default, $matches ) )
        {
            return $matches[1];
        }

        return $default;
    }

    /*!
     \private
     \param $table_name The table name
     \param $index_name The index name
     \param $def The index structure, see eZDBSchemaInterface for more details
     \param $params An associative array with optional parameters which controls the output of SQLs
     \param $withClosure If \c true then the SQLs will contain semi-colons to close them.
    */
    function generateAddIndexSql( $table_name, $index_name, $def, $params, $withClosure )
    {
        $diffFriendly = isset( $params['diff_friendly'] ) ? $params['diff_friendly'] : false;
        $postgresqlCompatible = isset( $params['compatible_sql'] ) ? $params['compatible_sql'] : false;

        $spacing = $postgresqlCompatible ? "\n    " : " ";
        switch ( $def['type'] )
        {
            case 'primary':
            {
                $pkeyName = $this->primaryKeyIndexName( $table_name, $index_name, $def['fields'] );
                if ( strlen( $pkeyName ) > 63 )
                {
                    eZDebug::writeError( "The primary key '$pkeyName' (" . strlen( $pkeyName ) . ") exceeds 63 characters which is the PostgreSQL limit for names" );
                }
                $sql = "ALTER TABLE ONLY $table_name" . $spacing . "ADD CONSTRAINT $pkeyName PRIMARY KEY";
            } break;

            case 'non-unique':
            {
                $sql = "CREATE INDEX $index_name ON $table_name USING btree";
            } break;

            case 'unique':
            {
                $sql = "CREATE UNIQUE INDEX $index_name ON $table_name USING btree";
            } break;
        }

        $sql .= ( $diffFriendly ? " (\n    " : ( $postgresqlCompatible ? ' (' : ' ( ' ) );
        $i = 0;
        foreach ( $def['fields'] as $fieldDef )
        {
            if ( $i > 0 )
            {
                $sql .= $diffFriendly ? ",\n    " : ', ';
            }
            if ( is_array( $fieldDef ) )
            {
                $fieldName = $fieldDef['name'];
            }
            else
            {
                $fieldName = $fieldDef;
            }
            if ( in_array( $fieldName, $this->reservedKeywordList() ) )
            {
                $sql .= '"' . $fieldName . '"';
            }
            else
            {
                $sql .= $fieldName;
            }
            ++$i;
        }

        $sql .= ( $diffFriendly ? "\n)" : ( $postgresqlCompatible ? ')' : ' )' ) );

        return $sql . ( $withClosure ? ";\n" : "" );
    }

    /*!
     * \private
     */
    function generateDropIndexSql( $table_name, $index_name, $def, $withClosure )
    {
        if ($def['type'] == 'primary' )
        {
            $sql = "ALTER TABLE $table_name DROP CONSTRAINT $index_name";
        }
        else
        {
            $sql = "DROP INDEX $index_name";
        }
        return $sql . ( $withClosure ? ";\n" : "" );
    }

    /*!
     * \private
     */
    function generateFieldDef( $table_name, $field_name, $def, $add_default_not_null = true, $params )
    {
        $diffFriendly = isset( $params['diff_friendly'] ) ? $params['diff_friendly'] : false;

        if ( in_array( $field_name, $this->reservedKeywordList() ) )
        {
            $sql_def = '"' . $field_name . '"';
        }
        else
        {
            $sql_def = $field_name;
        }

        $sql_def .= ( $diffFriendly ? "\n    " : " " );
        if ( $def['type'] != 'auto_increment' )
        {
            $pgType = eZPgsqlSchema::convertFromStandardType( $def['type'], $def['length'] );
            $sql_def .= $pgType;
            if ( eZPgsqlSchema::isTypeLengthSupported( $pgType ) and isset( $def['length'] ) && $def['length'] )
            {
                $sql_def .= "({$def['length']})";
            }
            if ( $add_default_not_null )
            {
                $defaultDef = eZPGSQLSchema::generateDefaultDef( false, false, $def, $params );
                if ( $defaultDef )
                {
                    $sql_def .= ( $diffFriendly ? "\n    " : " " );
                    $sql_def .= rtrim( $defaultDef );
                }
                $nullDef = eZPGSQLSchema::generateNullDef( false, false, $def, $params );
                if ( $nullDef )
                {
                    $sql_def .= ( $diffFriendly ? "\n    " : " " );
                    $sql_def .= trim( $nullDef );
                }
            }
        }
        else
        {
            if ( $diffFriendly )
            {
                $sql_def .= "integer\n    DEFAULT nextval('{$table_name}_s'::text)\n    NOT NULL";
            }
            else
            {
                $sql_def .= "integer DEFAULT nextval('{$table_name}_s'::text) NOT NULL";
            }
        }
        return $sql_def;
    }

    /*!
     \private
    */
    function generateDefaultDef( $table_name, $field_name, $def, $params )
    {
        $postgresqlCompatible = isset( $params['compatible_sql'] ) ? $params['compatible_sql'] : false;
        $sql_def = '';
        if ( $table_name and $field_name )
        {
            $sql_def .= "ALTER TABLE $table_name ALTER $field_name SET ";
        }
        if ( array_key_exists( 'default', $def ) and
             $def['default'] !== false )
        {
            if ( $def['default'] === null )
            {
                if ( !$postgresqlCompatible )
                    $sql_def .= "DEFAULT NULL ";
            }
            else if ( $def['default'] !== false )
            {
                if ( $def['type'] == 'int' )
                {
                    $sql_def .= "DEFAULT {$def['default']} ";
                }
                else if ( $def['type'] == 'float' )
                {
                    $sql_def .= "DEFAULT {$def['default']}::double precision ";
                }
                else if ( $def['type'] == 'varchar' )
                {
                    $sql_def .= "DEFAULT '{$def['default']}'::character varying ";
                }
                else if ( $def['type'] == 'char' )
                {
                    $sql_def .= "DEFAULT '{$def['default']}'::bpchar ";
                }
                else
                {
                    $sql_def .= "DEFAULT '{$def['default']}' ";
                }
            }
        }
        else if ( $table_name and $field_name )
        {
            return false;
        }
        return $sql_def;
    }

    /*!
     \private
    */
    function generateNullDef( $table_name, $field_name, $def, $params )
    {
        $sql_def = '';
        if ( $table_name and $field_name )
        {
            $sql_def .= "ALTER TABLE $table_name ALTER $field_name SET ";
        }
        if ( isset( $def['not_null'] ) && ( $def['not_null'] ) )
        {
            $sql_def .= 'NOT NULL ';
        }
        else if ( $table_name and $field_name )
        {
            return false;
        }
        return $sql_def;
    }

    /*!
     * \private
     */
    function generateAddFieldSql( $table_name, $field_name, $def, $params )
    {
        $sql = "ALTER TABLE $table_name ADD COLUMN ";
        $sql .= eZPgsqlSchema::generateFieldDef( $table_name, $field_name, $def, false, $params ) . ";\n";
        $defaultSQL = eZPGSQLSchema::generateDefaultDef( $table_name, $field_name, $def, $params );
        if ( $defaultSQL )
            $sql .= $defaultSQL . ";\n";
        $nullSQL = eZPGSQLSchema::generateNullDef( $table_name, $field_name, $def, $params );
        if ( $nullSQL )
            $sql .= $nullSQL . ";\n";
        $sql .= "\n";
        return $sql;
    }

    /*!
     * \private
     */
    function generateAlterFieldSql( $table_name, $field_name, $def, $params )
    {
        $sql = "ALTER TABLE $table_name RENAME COLUMN $field_name TO " . $field_name . "_tmp;\n";
        $sql .= "ALTER TABLE $table_name ADD COLUMN ";
        $sql .= eZPgsqlSchema::generateFieldDef( $table_name, $field_name, $def, false, $params ) . ";\n";
        $defaultSQL = eZPGSQLSchema::generateDefaultDef( $table_name, $field_name, $def, $params );
        if ( $defaultSQL )
            $sql .= $defaultSQL . ";\n";
        $nullSQL = eZPGSQLSchema::generateNullDef( $table_name, $field_name, $def, $params );
        if ( $nullSQL )
            $sql .= $nullSQL . ";\n";
        $sql .= "UPDATE $table_name SET $field_name=" . $field_name . "_tmp;\n";
        $sql .= "ALTER TABLE $table_name DROP COLUMN " . $field_name . "_tmp;\n\n";
        return $sql;
    }

    /*!
     \reimp
    */
    function generateTableSchema( $table, $table_def, $params )
    {
        $arrays = $this->generateTableArrays( $table, $table_def, $params, true );
        return ( join( "\n\n", $arrays['sequences'] ) . "\n" .
                 join( "\n\n", $arrays['tables'] ) . "\n" .
                 join( "\n\n", $arrays['indexes'] ) . "\n" .
                 join( "\n\n", $arrays['constraints'] ) . "\n" );
    }

    /*!
     \reimp
    */
    function generateTableSQLList( $table, $table_def, $params, $separateTypes )
    {
        $arrays = $this->generateTableArrays( $table, $table_def, $params, false );

        // If we have to separate the types the current array is sufficient
        if ( $separateTypes )
            return $arrays;
        return array_merge( $arrays['sequences'],
                            $arrays['tables'],
                            $arrays['indexes'],
                            $arrays['constraints'] );
    }

    /*!
     \private
     \param $table The table name
     \param $table_def The table structure, see eZDBSchemaInterface for more details
     \param $params An associative array with optional parameters which controls the output of SQLs
     \param $withClosure If \c true then the SQLs will contain semi-colons to close them.
    */
    function generateTableArrays( $table, $table_def, $params, $withClosure )
    {
        $diffFriendly = isset( $params['diff_friendly'] ) ? $params['diff_friendly'] : false;
        $postgresqlCompatible = isset( $params['compatible_sql'] ) ? $params['compatible_sql'] : false;

        $arrays = array( 'sequences' => array(),
                         'tables' => array(),
                         'indexes' => array(),
                         'constraints' => array() );

        $sql_fields = array();

        $spacing = $postgresqlCompatible ? '    ' : '  ';

        // First we need to check if we use auto increment fields as
        // sequences need to exist before we use them
        foreach ( $table_def['fields'] as $field_name => $field_def )
        {
            if ( $field_def['type'] == 'auto_increment' )
            {
                $sequenceFields = array( "CREATE SEQUENCE {$table}_s",
                                         "START 1",
                                         "INCREMENT 1",
                                         "MAXVALUE 9223372036854775807",
                                         "MINVALUE 1",
                                         "CACHE 1" );
                $arrays['sequences'][] = join( "\n$spacing", $sequenceFields ) . ( $withClosure ? ';' : '' );
            }
        }

        $sql = "CREATE TABLE $table (\n";
        $fields = $table_def['fields'];
        foreach ( $fields as $field_name => $field_def )
        {
            $sql_fields[] = $spacing . eZPgsqlSchema::generateFieldDef( $table, $field_name, $field_def, true, $params );
        }
        $sql .= join( ",\n", $sql_fields ) . ( $withClosure ? "\n);" : "\n)" );
        $arrays['tables'][] = $sql;

        foreach ( $table_def['indexes'] as $index_name => $index_def )
        {
            if ( $index_def['type'] != 'primary' )
            {
                $arrays['indexes'][] = eZPgsqlSchema::generateAddIndexSql( $table, $index_name, $index_def, $params, $withClosure );
            }
        }
        foreach ( $table_def['indexes'] as $index_name => $index_def )
        {
            if ( $index_def['type'] == 'primary' )
            {
                $arrays['constraints'][] = eZPgsqlSchema::generateAddIndexSql( $table, $index_name, $index_def, $params, $withClosure );
            }
        }

        return $arrays;
    }


    /*!
     \reimp

     This calls eZDBSchemaInterface::generateTableInsertSQLList() and adds a setval SQL if
     the table has auto increments.
    */
    function generateTableInsertSQLList( $tableName, $tableDef, $dataEntries, $params, $withClosure = true )
    {
        $sqlList = eZDBSchemaInterface::generateTableInsertSQLList( $tableName, $tableDef, $dataEntries, $params, $withClosure );

        foreach ( $tableDef['fields'] as $fieldName => $fieldDef )
        {
            if ( $fieldDef['type'] == 'auto_increment' )
            {
                $sql = "SELECT setval('" . $tableName . "_s',max(" . $fieldName . ")+1) FROM " . $tableName;
                if ( $withClosure )
                    $sql .= ";";
                $sqlList[] = $sql;
            }
        }
        return $sqlList;
    }

    /*!
      \reimp
    */
    function generateSchemaFile( $schema, $params = array() )
    {
        $sql = '';
        $postgresqlCompatible = isset( $params['compatible_sql'] ) ? $params['compatible_sql'] : false;

        $i = 0;
        $allArrays = array( 'sequences' => array(),
                            'tables' => array(),
                            'indexes' => array(),
                            'constraints' => array() );

        foreach ( $schema as $table => $tableDef )
        {
            // Skip the info structure, this is not a table
            if ( $table == '_info' )
                continue;

            $arrays = $this->generateTableArrays( $table, $tableDef, $params, true );
            if ( $postgresqlCompatible )
            {
                $allArrays['sequences'] = array_merge( $allArrays['sequences'],
                                                       $arrays['sequences'] );
                $allArrays['tables'] = array_merge( $allArrays['tables'],
                                                    $arrays['tables'] );
                $allArrays['indexes'] = array_merge( $allArrays['indexes'],
                                                     $arrays['indexes'] );
                $allArrays['constraints'] = array_merge( $allArrays['constraints'],
                                                         $arrays['constraints'] );
            }
            else
            {
                if ( $i > 0 )
                    $sql .= "\n\n";
                ++$i;

                $sql .= ( join( "\n", $arrays['sequences'] ) . "\n" .
                          join( "\n", $arrays['tables'] ) . "\n" .
                          join( "\n", $arrays['indexes'] ) . "\n" .
                          join( "\n", $arrays['constraints'] ) . "\n" );
            }
        }

        if ( $postgresqlCompatible )
        {
            $sql = ( str_repeat( "\n", 11 ) .
                     join( str_repeat( "\n", 8 ), $allArrays['sequences'] ) . str_repeat( "\n", 8 ) .
                     join( str_repeat( "\n", 8 ), $allArrays['tables'] ) . str_repeat( "\n", 8 ) .
                     join( str_repeat( "\n", 7 ), $allArrays['indexes'] ) . str_repeat( "\n", 8 ) .
                     join( str_repeat( "\n", 7 ), $allArrays['constraints'] ) . str_repeat( "\n", 8 ) );
        }

        return $sql;
    }

    /*!
     * \private
     */
    function generateDropTable( $table )
    {
        return "DROP TABLE $table;\n";
    }

    /*!
     \reimp
    */
    function escapeSQLString( $value )
    {
        $value = str_replace( "'", "\'", $value );
        $value = str_replace( "\"", "\\\"", $value );
        return $value;
    }

    /*!
     \reimp
    */
    function schemaType()
    {
        return 'postgresql';
    }

    /*!
     \reimp
    */
    function schemaName()
    {
        return 'PostgreSQL';
    }

    /*!
     \return An array with keywords that are reserved by PostgreSQL.
    */
    function reservedKeywordList()
    {
        return array( 'abort',
                      'absolute',
                      'access',
                      'action',
                      'add',
                      'after',
                      'aggregate',
                      'all',
                      'alter',
                      'analyse',
                      'analyze',
                      'and',
                      'any',
                      'as',
                      'asc',
                      'assertion',
                      'assignment',
                      'at',
                      'authorization',
                      'backward',
                      'before',
                      'begin',
                      'between',
                      'bigint',
                      'binary',
                      'bit',
                      'boolean',
                      'both',
                      'by',
                      'cache',
                      'called',
                      'cascade',
                      'case',
                      'cast',
                      'chain',
                      'char',
                      'character',
                      'characteristics',
                      'check',
                      'checkpoint',
                      'class',
                      'close',
                      'cluster',
                      'coalesce',
                      'collate',
                      'column',
                      'comment',
                      'commit',
                      'committed',
                      'constraint',
                      'constraints',
                      'conversion',
                      'convert',
                      'copy',
                      'create',
                      'createdb',
                      'createuser',
                      'cross',
                      'current_date',
                      'current_time',
                      'current_timestamp',
                      'current_user',
                      'cursor',
                      'cycle',
                      'database',
                      'day',
                      'deallocate',
                      'dec',
                      'decimal',
                      'declare',
                      'default',
                      'deferrable',
                      'deferred',
                      'definer',
                      'delete',
                      'delimiter',
                      'delimiters',
                      'desc',
                      'distinct',
                      'do',
                      'domain',
                      'double',
                      'drop',
                      'each',
                      'else',
                      'encoding',
                      'encrypted',
                      'end',
                      'escape',
                      'except',
                      'exclusive',
                      'execute',
                      'exists',
                      'explain',
                      'external',
                      'extract',
                      'false',
                      'fetch',
                      'float',
                      'for',
                      'force',
                      'foreign',
                      'forward',
                      'freeze',
                      'from',
                      'full',
                      'function',
                      'get',
                      'global',
                      'grant',
                      'group',
                      'handler',
                      'having',
                      'hour',
                      'ilike',
                      'immediate',
                      'immutable',
                      'implicit',
                      'in',
                      'increment',
                      'index',
                      'inherits',
                      'initially',
                      'inner',
                      'inout',
                      'input',
                      'insensitive',
                      'insert',
                      'instead',
                      'int',
                      'integer',
                      'intersect',
                      'interval',
                      'into',
                      'invoker',
                      'is',
                      'isnull',
                      'isolation',
                      'join',
                      'key',
                      'lancompiler',
                      'language',
                      'leading',
                      'left',
                      'level',
                      'like',
                      'limit',
                      'listen',
                      'load',
                      'local',
                      'localtime',
                      'localtimestamp',
                      'location',
                      'lock',
                      'match',
                      'maxvalue',
                      'minute',
                      'minvalue',
                      'mode',
                      'month',
                      'move',
                      'names',
                      'national',
                      'natural',
                      'nchar',
                      'new',
                      'next',
                      'no',
                      'nocreatedb',
                      'nocreateuser',
                      'none',
                      'not',
                      'nothing',
                      'notify',
                      'notnull',
                      'null',
                      'nullif',
                      'numeric',
                      'of',
                      'off',
                      'offset',
                      'oids',
                      'old',
                      'on',
                      'only',
                      'operator',
                      'option',
                      'or',
                      'order',
                      'out',
                      'outer',
                      'overlaps',
                      'overlay',
                      'owner',
                      'partial',
                      'password',
                      'path',
                      'pendant',
                      'placing',
                      'position',
                      'precision',
                      'prepare',
                      'primary',
                      'prior',
                      'privileges',
                      'procedural',
                      'procedure',
                      'read',
                      'real',
                      'recheck',
                      'references',
                      'reindex',
                      'relative',
                      'rename',
                      'replace',
                      'reset',
                      'restrict',
                      'returns',
                      'revoke',
                      'right',
                      'rollback',
                      'row',
                      'rule',
                      'schema',
                      'scroll',
                      'second',
                      'security',
                      'select',
                      'sequence',
                      'serializable',
                      'session',
                      'session_user',
                      'set',
                      'setof',
                      'share',
                      'show',
                      'similar',
                      'simple',
                      'smallint',
                      'some',
                      'stable',
                      'start',
                      'statement',
                      'statistics',
                      'stdin',
                      'stdout',
                      'storage',
                      'strict',
                      'substring',
                      'sysid',
                      'table',
                      'temp',
                      'template',
                      'temporary',
                      'then',
                      'time',
                      'timestamp',
                      'to',
                      'toast',
                      'trailing',
                      'transaction',
                      'treat',
                      'trigger',
                      'trim',
                      'true',
                      'truncate',
                      'trusted',
                      'type',
                      'unencrypted',
                      'union',
                      'unique',
                      'unknown',
                      'unlisten',
                      'until',
                      'update',
                      'usage',
                      'user',
                      'using',
                      'vacuum',
                      'valid',
                      'validator',
                      'values',
                      'varchar',
                      'varying',
                      'verbose',
                      'version',
                      'view',
                      'volatile',
                      'when',
                      'where',
                      'with',
                      'without',
                      'work',
                      'write',
                      'year',
                      'zone' );
    }
}
?>
