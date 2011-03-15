<?php
/**
 * File containing the ezpTestDatabaseHelper class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Helper class to deal with common tasks related to the test database.
 */
class ezpTestDatabaseHelper
{
    public static $schemaFile = 'share/db_schema.dba';
    public static $dataFile = 'share/db_data.dba';

    /**
     * Creates a new test database
     *
     * @param ezpDsn $dsn
     * @param array $sqlFiles array( array( string => string ) )
     * @param bool $removeExisting
     * @return mixed
     */
    public static function create( ezpDsn $dsn )
    {
        //oracle unit test doesn't support creating database, just use database string
        if ( $dsn->parts['phptype'] === 'oracle' )
        {
            $db = ezpDatabaseHelper::useDatabase( $dsn );
            eZDBTool::cleanup( $db );
            return $db;
        }

        $dbRoot = ezpDatabaseHelper::dbAsRootInstance( $dsn );

        if ( self::exists( $dbRoot, $dsn->database ) )
        {
            $db = ezpDatabaseHelper::useDatabase( $dsn );
            self::clean( $db );
        }
        else
        {
            $dbRoot->createDatabase( $dsn->database );
            $db = ezpDatabaseHelper::useDatabase( $dsn );
        }

        return $db;
    }

    /**
     * Removes everything inside a database
     *
     * @param eZDBInterface $db
     * @return void
     */
    public static function clean( eZDBInterface $db )
    {
        $supportedRelationTypes = $db->supportedRelationTypes();
        foreach( $supportedRelationTypes as $type )
        {
            $relations = $db->relationList( $type );
            foreach( $relations as $relation )
            {
                $db->removeRelation( $relation, $type );
            }
        }
    }

    /**
     * Checks if a database exists or not
     *
     * @param eZDBInterface $db
     * @param string $database
     */
    public static function exists( eZDBInterface $db, $database )
    {
        $databases = $db->availableDatabases();
        return ( is_array( $databases ) and in_array( $database, $databases ) );
    }

    /**
     * Inserts one or more sql files into the test database
     *
     * @param eZDBInterface $db
     * @param array $sqlFiles array( array( string => string ) )
     * @return bool
     */
    public static function insertSqlData( eZDBInterface $db, $sqlFiles )
    {
        if ( !is_array( $sqlFiles ) or count( $sqlFiles ) <= 0 )
            return false;

        foreach( $sqlFiles as $sqlFile )
        {
            if ( is_array( $sqlFile ) )
            {
                $success = $db->insertFile( $sqlFile[0], $sqlFile[1] );
            }
            else
            {
                $success = $db->insertFile( dirname( $sqlFile ), basename( $sqlFile ), false );
            }

            if ( !$success )
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Inserts the default eZ Publish schema and clean data
     *
     * @param eZDBInterface $db
     * @return bool
     */
    public static function insertDefaultData( eZDBInterface $db )
    {
        return self::insertData( $db, self::$schemaFile, self::$dataFile );
    }

    /**
     * Inserts the eZ Publish schema and optional clean data
     *
     * @param eZDBInterface $db
     * @param string $schemaFile Path to schema file (share/db_schema.dba)
     * @param string|null $dataFile Optional path to data file (share/db_data.dba)
     * @return bool
     */
    public static function insertData( eZDBInterface $db, $schemaFile, $dataFile = null )
    {
        $schemaArray = eZDbSchema::read( $schemaFile, true );
        $dataArray = $dataFile !== null ? eZDbSchema::read( $dataFile, true ) : array();
        $schemaArray = array_merge( $schemaArray, $dataArray );

        $dbSchema = eZDbSchema::instance( $schemaArray );
        if( ( $db instanceof eZMySQLDB ) || ( $db instanceof eZMySQLiDB ) )
        {
            $success = $dbSchema->insertSchema( array( 'schema' => true, 'data' => true, 'table_type' => 'innodb' ) );
        }
        else
        {
            $success = $dbSchema->insertSchema( array( 'schema' => true, 'data' => true ) );
        }

        return $success;
    }
}

?>
