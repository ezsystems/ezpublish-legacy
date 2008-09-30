<?php
/**
 * File containing the ezpDatabaseHelper class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
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
    static function useDatabase( ezpDsn $dsn )
    {
        $dbParams = $dsn->parts;
        $dbParams['use_defaults'] = false;

        $db = eZDB::instance( $dsn->phptype, $dbParams, true );

        eZDB::setInstance( $db );

        $db = eZDB::instance();

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

    /**
     * Generates database name
     *
     * @param string @domain
     * @return string
     */
    static function generateDatabaseName( $domain )
    {
        $dbName = $domain . "_db";

        // Normalize database name.
        // Reference: http://dev.mysql.com/doc/refman/5.0/en/identifiers.html
        $reserved = ' !&\/:*?"<>|.,\-\\\\';
        $dbName = preg_replace( "#([{$reserved}])#", '_', $dbName );

        // MySQL supports database names up to 64 chars long
        if ( strlen( $dbName ) >= 65 )
        {
            $dbName = substr( $dbName, 0, 57 );
            $dbName .= mt_rand( 0, 9999999 );
        }

        return $dbName;
    }

    /**
     * Generates delete database SQL statement
     *
     * @param string $database
     * @return string
     */
    static function generateRemoveDatabaseSQL( $database )
    {
        return "DROP DATABASE $database";
    }

    /**
     * Generates database username
     *
     * @param string $domain
     * @return string
     */
    static function generateUsername( $domain )
    {
        $username = $domain;

        // Make sure the username doesn't contain any illegal characters
        $reserved = ' !&\/:*?"<>|.,\-\\\\';
        $username = preg_replace( "#([{$reserved}])#", '_', $username );

        // MySQL only supports usernames 16 chars long
        if ( strlen( $username ) >= 17 )
        {
            // strip 7 characters from the username
            $username = substr( $username, 0, 9 );
            // Add a random number with up to 7 digits to the username
            $username .= mt_rand( 0, 9999999 );
        }

        return $username;
    }

    /**
     * Generates database password
     *
     * @param string $domain
     * @return string
     */
    static function generatePassword( $domain )
    {
        $password = md5( $domain . "_password_" . mt_rand() );
        return $password;
    }

    /**
     * Generates a 'create database' SQL statement
     *
     * @param string $databaseName
     * @return string
     */
    static function generateCreateDatabaseSQL( $databaseName )
    {
       return "CREATE DATABASE $databaseName";
    }

    /**
     * Generates SQL statement which grants all privileges to a database
     *
     * @param string $database
     * @param string $username
     * @param string $password
     * @return string
     */
    static function generateGrantPermissionSQL( $database, $username, $password )
    {
        $ini = eZINI::instance( 'sitefactory.ini' );
        $serverName = $ini->variable( 'WebServerSettings', 'IP' );

        $SQL = "GRANT ALL PRIVILEGES ON $database.* ";
        $SQL .= "TO $username@$serverName ";
        $SQL .= "IDENTIFIED BY '$password'";

        return $SQL;
    }
}

?>