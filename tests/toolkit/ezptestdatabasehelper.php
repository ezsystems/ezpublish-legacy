<?php
/**
 * File containing the ezpTestDatabaseHelper class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
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
     * @param ezpDsn $dsn 
     * @return void
     */
    public static function clean( $db )
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
     * @param eZDB $db 
     * @param string $database 
     */
    public static function exists( $db, $database )
    {
        $databases = $db->availableDatabases();
        return ( is_array( $databases ) and in_array( $database, $databases ) );
    }

    /**
     * Inserts one or more sql files into the test database
     *
     * @param eZDB $db 
     * @param array $sqlFiles array( array( string => string ) )
     * @return bool
     */
    public static function insertSqlData( $db, $sqlFiles )
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
     * @param eZDB $db 
     * @return bool
     */
    public static function insertDefaultData( $db )
    {
        $schemaArray = eZDbSchema::read( self::$schemaFile, true );
        $dataArray = eZDbSchema::read( self::$dataFile, true );
        $schemaArray = array_merge( $schemaArray, $dataArray );

        $dbSchema = eZDbSchema::instance( $schemaArray );
        $success = $dbSchema->insertSchema( array( 'schema' => true, 'data' => true ) );

        return $success;
    }
}

?>