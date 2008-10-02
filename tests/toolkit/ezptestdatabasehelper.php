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
    public static function create( ezpDsn $dsn, $removeExisting = true )
    {
        $db = ezpDatabaseHelper::dbAsRootInstance( $dsn );

        if ( $db and $db->isConnected() )
        {
            // Try to remove existing database
            if ( $removeExisting )
                self::remove( $dsn );

            // Create new database
            $db->createDatabase( $dsn->database );
            $db = ezpDatabaseHelper::useDatabase( $dsn );
        }
        else
        {
            $errorMessage = $db->errorMessage();
            die( $errorMessage );
        }

        return $db;
    }

    /**
     * Removes a database
     *
     * @param ezpDsn $dsn 
     * @return void
     */
    public static function remove( ezpDsn $dsn )
    {
        $db = ezpDatabaseHelper::dbAsRootInstance( $dsn );
        if ( in_array( $dsn->database, $db->availableDatabases() ) )
        {
            $db->removeDatabase( $dsn->database );
        }
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