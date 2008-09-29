<?php 

class ezpTestDatabaseHelper
{
    public static $schemaFile = array( "kernel/sql/", "kernel_schema.sql" );
    public static $dataFile = array( "kernel/sql/common/", "cleandata.sql" );

    public function create( ezpDsn $dsn, $removeExists = true )
    {
        $db = ezpDatabaseHelper::dbAsRootInstance( $dsn );

        if ( $db and $db->isConnected() )
        {
            // Try to remove existing database
            if ( $removeExists )
                self::remove( $dsn );

            $createDbQuery = ezpDatabaseHelper::generateCreateDatabaseSQL( $dsn->database );
            $db->query( $createDbQuery );

            $db = ezpDatabaseHelper::useDatabase( $dsn );
        }
        else
        {
            $errorMessage = $db->errorMessage();
            die( $errorMessage );
        }

        $schemaSuccess = $db->insertFile( self::$schemaFile[0], self::$schemaFile[1] );
        $dataSuccess = $db->insertFile( self::$dataFile[0], self::$dataFile[1], false );

        if ( !( $schemaSuccess && $dataSuccess ) )
        {
            $errorMessage = $db->errorMessage() . ":" . $db->errorNumber();
            print( $errorMessage );
        }

        return $db;
    }

    public function remove( ezpDsn $dsn )
    {
        $db = ezpDatabaseHelper::dbAsRootInstance( $dsn );
        $removeDbQuery = ezpDatabaseHelper::generateRemoveDatabaseSQL( $dsn->database );
        $db->query( $removeDbQuery );
    }
}

?>