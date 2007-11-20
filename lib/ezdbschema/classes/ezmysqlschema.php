<?php
//
// Created on: <30-Jan-2004 10:14:58 dr>
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
  \class eZMysqlSchema ezmysqlschema.php
  \ingroup eZDbSchema
  \brief Handles schemas for MySQL

*/

//include_once( 'lib/ezdbschema/classes/ezdbschemainterface.php' );

class eZMysqlSchema extends eZDBSchemaInterface
{

    /*!
     \reimp
     Constructor

     \param db instance
    */
    function eZMysqlSchema( $params )
    {
        $this->eZDBSchemaInterface( $params );
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
            $tableArray = $this->DBInstance->arrayQuery( "SHOW TABLES" );

            foreach( $tableArray as $tableNameArray )
            {
                $table_name = current( $tableNameArray );
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
     \private

     \param table name
     */
    function fetchTableFields( $table, $params )
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
            $field['not_null'] = 0;
            if ( $row['Null'] != 'YES' )
            {
                $field['not_null'] = '1';
            }
            $field['default'] = false;
            if ( !$field['not_null'] )
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
                        $field['default'] = (int)$field['default'];
                }
                else if ( $field['type'] == 'float' or
                          is_numeric( $field['default'] ) )
                {
                    if ( $field['not_null'] or
                         is_numeric( $field['default'] ) )
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
                $field['not_null'] = 0;
                $field['default'] = false;
                $field['type'] = 'auto_increment';
            }

            if ( !$field['not_null'] )
                unset( $field['not_null'] );

            $fields[$row['Field']] = $field;
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
        return $matches[1];
    }

    /*!
     * \private
     */
    function generateAddIndexSql( $table_name, $index_name, $def, $params, $isEmbedded = false )
    {
        $diffFriendly = isset( $params['diff_friendly'] ) ? $params['diff_friendly'] : false;
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
    function generateFieldDef( $field_name, $def, &$skip_primary, $params = null )
    {
        $diffFriendly = isset( $params['diff_friendly'] ) ? $params['diff_friendly'] : false;
        // If the output should compatible with existing MySQL dumps
        $mysqlCompatible = isset( $params['compatible_sql'] ) ? $params['compatible_sql'] : false;

        $sql_def = $field_name . ' ';
        $defaultText = $mysqlCompatible ? "default" : "DEFAULT";

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
                    $defList[] = "$defaultText NULL";
                }
                else if ( $def['default'] !== false )
                {
                    $defList[] = "$defaultText '{$def['default']}'";
                }
            }
            else if ( $def['type'] == 'varchar' )
            {
                $defList[] = "$defaultText ''";
            }
            $sql_def .= join( $diffFriendly ? "\n    " : " ", $defList );
            $skip_primary = false;
        }
        else
        {
            $incrementText = $mysqlCompatible ? "auto_increment" : "AUTO_INCREMENT";
            if ( $diffFriendly )
            {
                $sql_def .= "int(11)\n    NOT NULL\n    $incrementText";
            }
            else
            {
                $sql_def .= "int(11) NOT NULL $incrementText";
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
        $sql .= eZMysqlSchema::generateFieldDef ( $field_name, $def, $dummy );

        return $sql . ";\n";
    }

    /*!
     * \private
     */
    function generateAlterFieldSql( $table_name, $field_name, $def, $params )
    {
        $sql = "ALTER TABLE $table_name CHANGE COLUMN $field_name ";
        $sql .= eZMysqlSchema::generateFieldDef ( $field_name, $def, $dummy );

        return $sql . ";\n";
    }

    /*!
     \reimp
     \note Calls generateTableSQL() with \a $asArray set to \c false
    */
    function generateTableSchema( $tableName, $table, $params )
    {
        return $this->generateTableSQL( $tableName, $table, $params, false, false );
    }

    /*!
     \reimp
     \note Calls generateTableSQL() with \a $asArray set to \c true
    */
    function generateTableSQLList( $tableName, $table, $params, $separateTypes )
    {
        return $this->generateTableSQL( $tableName, $table, $params, true, $separateTypes );
    }

    /*!
     \private

     \param $asArray If \c true all SQLs are return in an array,
                     if not they are returned as a string.
     \note When returned as array the SQLs will not have a semi-colon to end the statement
    */
    function generateTableSQL( $tableName, $tableDef, $params, $asArray, $separateTypes = false )
    {
        $diffFriendly = isset( $params['diff_friendly'] ) ? $params['diff_friendly'] : false;
        $mysqlCompatible = isset( $params['compatible_sql'] ) ? $params['compatible_sql'] : false;

        if ( $asArray )
        {
            if ( $separateTypes )
            {
                $sqlList = array( 'tables' => array() );
            }
            else
            {
                $sqlList = array();
            }
        }

        $sql = '';
        $skip_pk = false;
        $sql_fields = array();
        $sql .= "CREATE TABLE $tableName (\n";

        $fields = $tableDef['fields'];

        foreach ( $fields as $field_name => $field_def )
        {
            $sql_fields[] = '  ' . eZMysqlSchema::generateFieldDef( $field_name, $field_def, $skip_pk_flag, $params );
            if ( $skip_pk_flag )
            {
                $skip_pk = true;
            }
        }

        // Make sure the order is as defined by 'offset'
        $indexes = $tableDef['indexes'];

        // We need to add all keys in table definition
        foreach ( $indexes as $index_name => $index_def )
        {
            $sql_fields[] = ( $diffFriendly ? '' : '  ' ) . eZMysqlSchema::generateAddIndexSql( $tableName, $index_name, $index_def, $params, true );
        }
        $sql .= join( ",\n", $sql_fields );
        $sql .= "\n)";

        // Add some extra table options if they are required
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
        if ( isset( $tableDef['options'] ) )
        {
            foreach( $tableDef['options'] as $optionType => $optionValue )
            {
                $optionText = $this->generateTableOption( $tableName, $tableDef, $optionType, $optionValue, $params );
                if ( $optionText )
                    $extraOptions[] = $optionText;
            }
        }

        if ( count( $extraOptions ) > 0 )
        {
            $sql .= " " . implode( $diffFriendly ? "\n" : " ", $extraOptions );
        }

        if ( $asArray )
        {
            if ( $separateTypes )
            {
                $sqlList['tables'][] = $sql;
            }
            else
            {
                $sqlList[] = $sql;
            }
        }
        else
        {
            $sql .= ";\n";

            if ( $mysqlCompatible )
            {
                $sql .= "\n\n\n";
            }
        }

        return $asArray ? $sqlList : $sql;
    }

    /*!
     Detects known options and generates the MySQL SQL code for it.
     \return The SQL code as a string or \c false if not known.
     \param $optionType The type of option, the supported ones are:
                        - delay_key_write - If \a $optionValue is true then adds DELAY_KEY_WRITE=1
    */
    function generateTableOption( $tableName, $tableDef, $optionType, $optionValue, $params )
    {
        switch ( $optionType )
        {
            case 'mysql:delay_key_write':
            {
                if ( $optionValue )
                    return 'DELAY_KEY_WRITE=1';
            } break;
        }
        return false;
    }

    /*!
      \return The name of the charset \a $charset in a format MySQL understands.
    */
    function tableCharsetName( $charset )
    {
        //include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
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
                                 'koi8-r' => 'koi8r',
                                 'koi8-u' => 'koi8u' );
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
        if ( $this->DBInstance instanceof eZDBInterface )
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
    function escapeSQLString( $value )
    {
        if ( $this->DBInstance instanceof eZDBInterface )
        {
            return $this->DBInstance->escapeString( $value );
        }

        return $value;
    }

    /*!
     \reimp
    */
    function schemaType()
    {
        return 'mysql';
    }

    /*!
     \reimp
    */
    function schemaName()
    {
        return 'MySQL';
    }

}
?>
