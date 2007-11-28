<?php
//
// Definition of eZLintSchema class
//
// Created on: <05-Nov-2004 14:03:27 jb>
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
  \class eZLintSchema ezlintschema.php
  \ingroup eZDbSchema
  \brief Provides lint checking of database schemas

  Checks a given schema by going trough all tables, fields and indexes
  and corrects any mistakes. The result is a new schema which is returned
  in schema(). The new schema can then be used to diff against the original
  and output the changes.

  The current rules apply:
  - Table names must not exceed 26 characters, configurable in dbschema.ini (LintChecker/TableLimit)
  - Field names must not exceed 30 characters, configurable in dbschema.ini (LintChecker/FieldLimit)
  - Index names must not exceed 30 characters, configurable in dbschema.ini (LintChecker/IndexLimit)
  - Index names must not be the same as table names
  - String fields cannot have NOT NULL and an empty string as DEFAULT value.
  - Primary keys must be named PRIMARY

  The lint checker works by taking in another DB Schema object as parameter
  to the constructor. All calls will be forwarded to this object so it will
  work as though it were a real schema.
  The exception are the schema(), data() and validate() methods which makes
  sure the schema is correct.

  To check if the schema has been checked yet call isLintChecked().
  To fetch the DB schema which is checked use otherSchema().

*/

//include_once( 'lib/ezdbschema/classes/ezdbschemainterface.php' );

class eZLintSchema extends eZDBSchemaInterface
{
    /*!
     Initializes the lint checker with a foreign db schema.

     \param $db A dummy parameter, pass \c false.
     \param $otherSchema The db schema that should be checked
    */
    function eZLintSchema( $db, $otherSchema )
    {
        $this->eZDBSchemaInterface( $db );
        $this->OtherSchema = $otherSchema;
        $this->CorrectSchema = false;
        $this->IsLintChecked = false;
    }

    /*!
     Runs the lint checker on the database schema in otherSchema()
     and returns the new schema that is correct.
    */
    function schema( $params = array() )
    {
        if ( $this->IsLintChecked )
        {
            return $this->CorrectSchema;
        }

        $params = array_merge( array( 'meta_data' => false,
                                      'format' => 'generic' ),
                               $params );

        $this->CorrectSchema = $this->OtherSchema->schema( $params );
        $this->lintCheckSchema( $this->CorrectSchema );
        $this->IsLintChecked = true;
        return $this->CorrectSchema;
    }

    /*!
     \reimp
     Runs lint checker on all tables, indexes and fields.
    */
    function validate()
    {
        return $this->lintCheckSchema( $this->CorrectSchema );
    }

    /*!
     \return The schema object which is being lint checked.
    */
    function otherSchema()
    {
        return $this->OtherSchema;
    }

    /*!
     \return \c true if the lint checker has been run on the schema.
    */
    function isLintChecked()
    {
        return $this->IsLintChecked;
    }

    /*!
     \return A modified version of \a $identifier that is guaranteed to be shorter than \a $limit
    */
    function shortenIdentifier( $identifier, $limit, $shortenList )
    {
        reset( $shortenList );
        // Replace one word at a time until we have a string that is short
        // enough, or we run out of replace words
        while ( strlen( $identifier ) > $limit and
                current( $shortenList ) !== false )
        {
            $from = key( $shortenList );
            $to = current( $shortenList );
            next( $shortenList );
            $identifier = str_replace( $from, $to, $identifier );
        }

        // It is still to large so we just cut it off
        if ( strlen( $identifier ) > $limit )
        {
            $identifier = substr( $identifier, 0, $limit );
        }
        return $identifier;
    }

    /*!
     \private
     Goes trough all tables, fields and indexes and makes sure they have valid names.
     \return \c false if something was fixed, \c true otherwise.
    */
    function lintCheckSchema( &$schema )
    {
        $status = true;

        $ini = eZINI::instance( 'dbschema.ini' );

        // A mapping table that maps from a long name to a short name
        // This will be used if an identifier/name is too long
        $shortenList = $ini->variable( 'LintChecker', 'NameMap' );

        // Limitation on the length of identifiers/names
        // Oracle is the database with the most limit (30 characters) so the
        // limit values must be equal or lower to that.
        $tableNameLimit = (int)$ini->variable( 'LintChecker', 'TableLimit' );
        $fieldNameLimit = (int)$ini->variable( 'LintChecker', 'FieldLimit' );
        $indexNameLimit = (int)$ini->variable( 'LintChecker', 'IndexLimit' );

        // Tables which do not get lint checked, they are currently
        // handled with workarounds in the various schema handlers
        // Note: The fields and indexes are still checked.
        $ignoredTableList = $ini->variable( 'LintChecker', 'IgnoredTables' );

        // Fields which do not get lint checked, they are currently
        // handled with workarounds in the various schema handlers
        $list = $ini->variable( 'LintChecker', 'IgnoredFields' );
        $ignoredFieldList = array();
        foreach ( $list as $entry )
        {
            list( $tableName, $fieldName ) = explode( '.', $entry, 2 );
            if ( !isset( $ignoredFieldList[$tableName] ) )
                $ignoredFieldList[$tableName] = array();
            $ignoredFieldList[$tableName][] = $fieldName;
        }

        // Fields which do not get lint checked, they are currently
        // handled with workarounds in the various schema handlers
        $list = $ini->variable( 'LintChecker', 'IgnoredFieldSyntax' );
        $ignoredFieldSyntaxList = array();
        foreach ( $list as $entry )
        {
            list( $tableName, $fieldName ) = explode( '.', $entry, 2 );
            if ( !isset( $ignoredFieldList[$tableName] ) )
                $ignoredFieldSyntaxList[$tableName] = array();
            $ignoredFieldSyntaxList[$tableName][] = $fieldName;
        }

        // Indexes which do not get lint checked, they are currently
        // handled with workarounds in the various schema handlers
        $ignoredIndexList = $ini->variable( 'LintChecker', 'IgnoredIndexes' );

        $badTables = array();
        foreach ( $schema as $tableName => $tableDef )
        {
            // Skip the info structure, this is not a table
            if ( $tableName == '_info' )
                continue;

            $existingTableName = $tableName;
            $tableComments = array();

            // If table is not in ignore list we check the name
            if ( !in_array( $tableName, $ignoredTableList ) )
            {
                // identifiers must be 30 or less
                // for tables we require 26 or less to allow adding suffix or prefix for indexes etc.
                if ( strlen( $tableName ) > $tableNameLimit )
                {
                    $tableComment = "Table names must not exceed $tableNameLimit characters,\n'$tableName' is " . strlen( $tableName ) . " characters,\ndatabases like Oracle will have problems with this.";
                    $tableName = $this->shortenIdentifier( $tableName, $tableNameLimit, $shortenList );
                    $tableComment .= "\nNew name is '$tableName'";
                    $tableComments[] = $tableComment;
                    $status = false;
                }

                if ( strcmp( $tableName, $existingTableName ) != 0 )
                {
                    $badTables[] = array( 'from' => $existingTableName,
                                          'to' => $tableName );
                }
            }

            if ( isset( $tableDef['fields'] ) )
            {
                $badFields = array();
                foreach ( $tableDef['fields'] as $fieldName => $fieldDef )
                {
                    $comments = array();
                    $existingFieldName = $fieldName;

                    // Do we ignore the field name?
                    if ( !isset( $ignoredFieldList[$existingTableName] ) or
                         !in_array( $fieldName, $ignoredFieldList[$existingTableName] ) )
                    {

                        // identifiers must be 30 or less
                        if ( strlen( $fieldName ) > $fieldNameLimit )
                        {
                            $comment = "Field names must not exceed $fieldNameLimit characters,\n'$fieldName' in table '$existingTableName' is " . strlen( $fieldName ) . " characters,\ndatabases like Oracle will have problems with this.";
                            $fieldName = $this->shortenIdentifier( $fieldName, $fieldNameLimit, $shortenList );
                            $comment .= "\nNew name is '$fieldName'";
                            $comments[] = $comment;
                            $status = false;
                        }
                    }

                    if ( !isset( $ignoredFieldSyntaxList[$existingTableName] ) or
                         !in_array( $fieldName, $ignoredFieldSyntaxList[$existingTableName] ) )
                    {
                        /* Temporarily disabled
                        if ( in_array( $fieldDef['type'],
                                       array( 'varchar', 'char',
                                              'longtext', 'mediumtext', 'shorttext' ) ) and
                             isset( $fieldDef['not_null'] ) and
                             $fieldDef['not_null'] and
                             $fieldDef['default'] === '' )
                        {
                            $comments[] = "The string type " . $fieldDef['type'] . " ($existingTableName.$fieldName) cannot have NOT NULL defined and an empty string as DEFAULT value\nDatabase like Oracle will have problems with this.";
                            $status = false;
                        }
                        */
                    }

                    if ( strcmp( $existingFieldName, $fieldName ) != 0 )
                    {
                        $badFields[] = array( 'from' => $existingFieldName,
                                              'to' => $fieldName );
                    }

                    if ( count( $comments ) > 0 )
                    {
                        $schema[$existingTableName]['fields'][$existingFieldName]['comments'] = $comments;
                        foreach ( $comments as $comment )
                        {
                            eZDebug::writeWarning( $comment, 'eZLintSchema::fieldComment' );
                        }
                    }
                }

                foreach ( $badFields as $badField )
                {
                    $schema[$existingTableName]['fields'][$badField['to']] = $schema[$existingTableName]['fields'][$badField['from']];
//                     unset( $schema[$existingTableName]['fields'][$badField['from']] );
                    $schema[$existingTableName]['fields'][$badField['from']]['removed'] = true;
                }
            }

            if ( isset( $tableDef['indexes'] ) )
            {
                $badIndexes = array();
                foreach ( $tableDef['indexes'] as $indexName => $indexDef )
                {
                    // Primary key
                    if ( $indexDef['type'] == 'primary' )
                        continue;

                    // Do we ignore the index?
                    if ( in_array( $indexName, $ignoredIndexList ) )
                        continue;

                    $comments = array();

                    $existingIndexName = $indexName;
                    if ( isset( $schema[$indexName] ) )
                    {
                        $comment = "Index named '$indexName' has same name as an existing table,\ndatabases like PostgreSQL and Oracle will have problems with this.";
                        $indexFieldText = '';
                        $i = 0;
                        foreach ( $indexDef['fields'] as $fieldDef )
                        {
                            if ( $i > 0 )
                                $indexFieldText .= '_';
                            if ( is_array( $fieldDef ) )
                            {
                                $indexFieldText .= $fieldDef['name'];
                            }
                            else
                            {
                                $indexFieldText .= $fieldDef;
                            }
                        }
                        $indexName = $indexName . '_' . $indexFieldText . '_i';
                        $comment .= "\nNew name is '$indexName'";
                        $comments[] = $comment;
                        $status = false;
                    }

                    // Primary indexes must be named PRIMARY
                    if ( $indexDef['type'] == 'primary' and
                         $indexName != 'PRIMARY' )
                    {
                        $comment = "Index named '$indexName' which is a primary key must be named PRIMARY.";
                        $indexName = "PRIMARY";
                        $comments[] = $comment;
                        $status = false;
                    }

                    // identifiers must be 30 or less
                    if ( strlen( $indexName ) > $indexNameLimit )
                    {
                        $comment = "Index names must not exceed $indexNameLimit characters,\n'$indexName' is " . strlen( $indexName ) . " characters,\ndatabases like Oracle will have problems with this.";
                        $indexName = $this->shortenIdentifier( $indexName, $indexNameLimit, $shortenList );
                        $comment .= "\nNew name is '$indexName'";
                        $comments[] = $comment;
                        $status = false;
                    }

                    // Check if there are some database specific entries
                    foreach ( $indexDef['fields'] as $fieldDef )
                    {
                        if ( is_array( $fieldDef ) )
                        {
                            $fieldName = $fieldDef['name'];
                            foreach ( $fieldDef as $fdName => $fdValue )
                            {
                                if ( preg_match( "#^([a-z0-9]+):#", $fdName, $matches ) )
                                {
                                    $dbName = $matches[1];
                                    $comments[] = "Found database specific entry ($dbName) at index $existingIndexName.$fieldName";
                                    $status = false;
                                }
                            }
                        }
                    }

                    if ( strcmp( $existingIndexName, $indexName ) != 0 )
                    {
                        $badIndexes[] = array( 'from' => $existingIndexName,
                                               'to' => $indexName );
                    }
                    if ( count( $comments ) > 0 )
                    {
                        $schema[$existingTableName]['indexes'][$existingIndexName]['comments'] = $comments;
                        foreach ( $comments as $comment )
                        {
                            eZDebug::writeWarning( $comment, 'eZLintSchema::indexComment' );
                        }
                    }
                }

                foreach ( $badIndexes as $badIndex )
                {
                    $schema[$existingTableName]['indexes'][$badIndex['to']] = $schema[$existingTableName]['indexes'][$badIndex['from']];
                    $schema[$existingTableName]['indexes'][$badIndex['from']]['removed'] = true;
                }
            }

            if ( count( $tableComments ) > 0 )
            {
                $schema[$existingTableName]['comments'] = $tableComments;
                foreach ( $tableComments as $comment )
                {
                    eZDebug::writeWarning( $comment, 'eZLintSchema::tableComment' );
                }
            }
        }
        foreach ( $badTables as $badTable )
        {
            $schema[$badTable['to']] = $schema[$badTable['from']];
            $schema[$badTable['from']]['removed'] = true;
        }
        return $status;
    }

    /*!
     \reimp
     Forwards request to data() on the otherSchema() object.
    */
    function data( $schema = false, $tableNameList = false, $params = array() )
    {
        return $this->OtherSchema->data( $schema, $tableNameList, $params );
    }

    /*!
     \reimp
     Forwards request to generateSchemaFile() on the otherSchema() object.
    */
    function generateSchemaFile( $schema, $params = array() )
    {
        return $this->OtherSchema->generateSchemaFile( $schema, $params );
    }

    /*!
     \reimp
     Forwards request to generateUpgradeFile() on the otherSchema() object.
    */
    function generateUpgradeFile( $differences, $params = array() )
    {
        return $this->OtherSchema->generateUpgradeFile( $differences, $params );
    }

    /*!
     \reimp
     Forwards request to generateDataFile() on the otherSchema() object.
    */
    function generateDataFile( $schema, $data, $params )
    {
        return $this->OtherSchema->generateDataFile( $schema, $data, $params );
    }

    /*!
     \reimp
     Forwards request to generateTableSchema() on the otherSchema() object.
    */
    function generateTableSchema( $table, $tableDef, $params )
    {
        return $this->OtherSchema->generateTableSchema( $table, $tableDef, $params );
    }

    /*!
     \reimp
     Forwards request to generateTableInsert() on the otherSchema() object.
    */
    function generateTableInsert( $tableName, $tableDef, $dataEntries, $params )
    {
        return $this->OtherSchema->generateTableInsert( $tableName, $tableDef, $dataEntries, $params );
    }

    /*!
     \reimp
     Forwards request to generateDropTable() on the otherSchema() object.
    */
    function generateDropTable( $table, $params )
    {
        return $this->OtherSchema->generateDropTable( $table, $params );
    }

    /*!
     \reimp
     Forwards request to generateAddFieldSql() on the otherSchema() object.
    */
    function generateAddFieldSql( $table, $field_name, $added_field, $params )
    {
        return $this->OtherSchema->generateAddFieldSql( $table, $field_name, $added_field, $params );
    }

    /*!
     \reimp
     Forwards request to generateAlterFieldSql() on the otherSchema() object.
    */
    function generateAlterFieldSql( $table, $field_name, $changed_field, $params )
    {
        return $this->OtherSchema->generateAlterFieldSql( $table, $field_name, $changed_field, $params );
    }

    /*!
     \reimp
     Forwards request to generateDropFieldSql() on the otherSchema() object.
    */
    function generateDropFieldSql( $table, $field_name, $params )
    {
        return $this->OtherSchema->generateDropFieldSql( $table, $field_name, $params );
    }

    /*!
     \reimp
     Forwards request to generateAddIndexSql() on the otherSchema() object.
    */
    function generateAddIndexSql( $table, $index_name, $added_index, $params )
    {
        return $this->OtherSchema->generateAddIndexSql( $table, $index_name, $added_index, $params );
    }

    /*!
     \reimp
     Forwards request to generateDropIndexSql() on the otherSchema() object.
    */
    function generateDropIndexSql( $table, $index_name, $removed_index, $params )
    {
        return $this->OtherSchema->generateDropIndexSql( $table, $index_name, $removed_index, $params );
    }

    /*!
     \reimp
     Forwards request to isMultiInsertSupported() on the otherSchema() object.
    */
    function isMultiInsertSupported()
    {
        return $this->OtherSchema->isMultiInsertSupported();
    }

    /*!
     \reimp
     Forwards request to generateDataValueTextSQL() on the otherSchema() object.
    */
    function generateDataValueTextSQL( $fieldDef, $value )
    {
        return $this->OtherSchema->generateDataValueTextSQL( $fieldDef, $value );
    }

    /*!
     \reimp
     Forwards request to schemaType() on the otherSchema() object.
    */
    function schemaType()
    {
        return $this->OtherSchema->schemaType();
    }

    /*!
     \reimp
     Forwards request to schemaName() on the otherSchema() object.
    */
    function schemaName()
    {
        return $this->OtherSchema->schemaName();
    }

    /// \privatesection
    /// eZDBSchemaInterface object which should be lint checked
    public $OtherSchema;
    /// The corrected schema
    public $CorrectSchema;
    /// Whether the schema has been checked or not
    public $IsLintChecked;
}

?>
