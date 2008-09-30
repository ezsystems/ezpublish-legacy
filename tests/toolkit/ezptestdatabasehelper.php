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
    public static $schemaFile = array( "kernel/sql/", "kernel_schema.sql" );
    public static $dataFile = array( "kernel/sql/common/", "cleandata.sql" );

    /**
     * Creates a new test database
     *
     * @param ezpDsn $dsn 
     * @param array $sqlFiles array( array( string => string ) )
     * @param bool $removeExisting
     * @return mixed
     */
    public static function create( ezpDsn $dsn, $sqlFiles = false, $removeExisting = true )
    {
        $db = ezpDatabaseHelper::dbAsRootInstance( $dsn );

        if ( $db and $db->isConnected() )
        {
            // Try to remove existing database
            if ( $removeExisting )
                self::remove( $dsn );

            // Create new database
            $createDbQuery = ezpDatabaseHelper::generateCreateDatabaseSQL( $dsn->database );
            $db->query( $createDbQuery );
            $db = ezpDatabaseHelper::useDatabase( $dsn );
        }
        else
        {
            $errorMessage = $db->errorMessage();
            die( $errorMessage );
        }

        $result = self::insertData( $db, $sqlFiles );

        if ( !$result )
        {
            $errorMessage = $db->errorMessage() . ":" . $db->errorNumber();
            print( $errorMessage );
            return false;
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
        $removeDbQuery = ezpDatabaseHelper::generateRemoveDatabaseSQL( $dsn->database );
        $db->query( $removeDbQuery );
    }

    /**
     * Inserts one or more sql files into the test database
     *
     * @param eZDB $db 
     * @param array $sqlFiles array( array( string => string ) )
     * @return bool
     */
    public static function insertData( $db, $sqlFiles = false )
    {
        if ( !is_array( $sqlFiles ) or count( $sqlFiles ) <= 0 )
            return self::insertDefaultData( $db );

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
        $schemaSuccess = $db->insertFile( self::$schemaFile[0], self::$schemaFile[1] );
        $dataSuccess = $db->insertFile( self::$dataFile[0], self::$dataFile[1], false );

        if ( $schemaSuccess && $dataSuccess )
        {
            return true;
        }

        return false;
    }
}

?>