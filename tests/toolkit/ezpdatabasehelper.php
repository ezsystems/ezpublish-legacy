<?php
/**
 * File containing the ezpDatabaseHelper class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class ezpDatabaseHelper
{
    /**
     * Constructs new ezpDatabaseHelper and sets default db instance.
     */
    function __construct()
    {
        $this->DefaultDB = eZDB::instance();
        self::setInstance( $this );
    }

    /**
     * Returns true if we have instance of the ezpDatabaseHelper object, else false.
     *
     * @return bool
     */
    static function hasInstance()
    {
        return isset( $GLOBALS['ezpDatabaseHelper'] ) &&
                      $GLOBALS['ezpDatabaseHelper'] instanceof ezpDatabaseHelper;
    }

    /**
     * Sets the global ezpDatabaseHelper instance to \a $instance.
     *
     * @param ezpDatabaseHelper $instance
     */
    static function setInstance( $instance )
    {
        $GLOBALS['ezpDatabaseHelper'] = $instance;
    }

    /**
     * Returns an instance of the ezpDatabaseHelper object.
     *
     * @return ezpDatabaseHelper Instance of ezpDatabaseHelper
     */
    static function instance()
    {
        if ( isset( $GLOBALS['ezpDatabaseHelper'] ) )
        {
            $impl =& $GLOBALS['ezpDatabaseHelper'];

            return $impl;
        }
        else
        {
            $impl = new ezpDatabaseHelper();
            self::setInstance( $impl );
            return $impl;
        }
    }

    /**
     * Returns a database handler which uses database $database
     *
     * @param ezpDsn $database
     * @return eZDB Instance of eZDB
     */
    static function useDatabase( ezpDsn $dsn, $makeDefaultInstance = true )
    {
        $dbParams = $dsn->parts;
        $dbParams['use_defaults'] = false;

        $db = eZDB::instance( $dsn->phptype, $dbParams, true );

        if ( $makeDefaultInstance )
            eZDB::setInstance( $db );

        return $db;
    }

    /**
     * Returns a ezdb instance which should be logged in as root
     *
     * @param ezpDsn $database
     * @return eZDB Instance of eZDB
     */
    static function dbAsRootInstance( ezpDsn $dsn )
    {
        $dbParams = $dsn->parts;
        $dbParams['database'] = "";
        $dbParams['use_defaults'] = false;

        $db = eZDB::instance( $dsn->phptype, $dbParams, true );

        return $db;
    }

    /**
     * Resets the database handler back to default
     *
     * @return eZDB Instance of eZDB
     */
    public function resetDatabaseHandler()
    {
        if ( !$this->DefaultDB->isConnected() )
            return false;

        eZDB::setInstance( $this->DefaultDB );

        return $this->DefaultDB;
    }
}

?>