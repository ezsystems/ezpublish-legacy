<?php

class ezpDatabaseTestSuite extends ezpTestSuite
{
    public function __construct()
    {
        parent::__construct();

        $this->schemaFile = array( "kernel/sql/", "kernel_schema.sql" );
        $this->dataFile = array( "kernel/sql/common/", "cleandata.sql" );

        $this->databaseFile = false;
    }

    /**
     * Sets up a test database
     *
     * @return void
     */
    protected function setUp()
    {
        $dsnOption = ezpTestRunner::$consoleInput->getOption( 'dsn' );

        if ( $dsnOption->value ) 
        {
            $this->dsn = new ezpDsn( $dsnOption->value );
        }
        else
        {
            throw new ezcConsoleOptionMandatoryViolationException( $dsnOption );
        }

        $db = ezpDatabaseHelper::dbAsRootInstance( $this->dsn );

        if ( $db and $db->isConnected() )
        {
            // Try to remove existing database
            $this->removeDatabase();

            $createDbQuery = ezpDatabaseHelper::generateCreateDatabaseSQL( $this->dsn->database );
            $db->query( $createDbQuery );

            $db = ezpDatabaseHelper::useDatabase( $this->dsn );
            $this->sharedFixture = $db;
        }
        else
        {
            $errorMessage = $db->errorMessage();
            die( $errorMessage );
        }

        if ( isset( $this->databaseFile ) && $this->databaseFile )
        {
            $success = $db->insertFile( $this->databaseFile[0], $this->databaseFile[1] );
        }
        else
        {
            $success = $db->insertFile( $this->schemaFile[0], $this->schemaFile[1] );

            if ( $success )
            {
                $success = $db->insertFile( $this->dataFile[0], $this->dataFile[1], false );
            }
        }

        if ( !$success )
        {
            $errorMessage = $db->errorMessage() . ":" . $db->errorNumber();
            print( $errorMessage );
        }
    }

    /**
     * Removes and cleans up test database
     *
     * @return void
     */
    protected function removeDatabase()
    {
        $db = ezpDatabaseHelper::dbAsRootInstance( $this->dsn );
        $removeDbQuery = ezpDatabaseHelper::generateRemoveDatabaseSQL( $this->dsn->database );
        $db->query( $removeDbQuery );
    }
}
?>
