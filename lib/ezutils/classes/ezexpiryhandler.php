<?php
//
// Definition of eZExpiryHandler class
//
// Created on: <28-Feb-2003 16:52:53 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/**
 * Keeps track of expiry keys and their timestamps
 * @class eZExpiryHandler ezexpiryhandler.php
 **/
class eZExpiryHandler
{
    /**
     * Constructor
     **/
    function eZExpiryHandler()
    {
        $this->Timestamps = array();
        $this->IsModified = false;

        $cacheDirectory = eZSys::cacheDirectory();
        $this->CacheFile = eZClusterFileHandler::instance( $cacheDirectory . '/' . 'expiry.php' );
        $this->restore();
    }

    /**
     * Load the expiry timestamps from cache
     *
     * @return void
     **/
    function restore()
    {
        $Timestamps = $this->CacheFile->processFile( array( $this, 'fetchData' ) );
        $this->Timestamps = $Timestamps;
        $this->IsModified = false;
    }

    /**
     * Includes the expiry file and extracts the $Timestamps variable from it.
     * @param string $path
     **/
    static function fetchData( $path )
    {
        include( $path );
        return $Timestamps;
    }

    /**
     * Stores the current timestamps values to cache
     **/
    function store()
    {
        if ( $this->IsModified )
        {
            $this->CacheFile->storeContents( "<?php\n\$Timestamps = " . var_export( $this->Timestamps, true ) . ";\n?>", 'expirycache', false, true );
            $this->IsModified = false;
        }
    }

    /**
     * Sets the expiry timestamp for a key
     *
     * @param string $name Expiry key
     * @param int    $value Expiry timestamp value
     * @param string $arrayKey Sub array key used to store indexed values
     **/
    function setTimestamp( $name, $value, $arrayKey = null )
    {
        if ( $arrayKey === null )
        {
            // An expiry array being set without an $arrayKey should
            // replace all its sub elements with the new value.
            if ( isset( $this->Timestamps[$name] ) && is_array( $this->Timestamps[$name] ) )
            {
                $this->Timestamps[$name] = array_fill_keys( array_keys( $this->Timestamps[$name] ), $value );
            }
            else
            {
                $this->Timestamps[$name] = $value;
            }
        }
        else
        {
            $this->Timestamps[$name][$arrayKey] = $value;
        }
        $this->IsModified = true;
    }

    /**
     * Checks if an expiry timestamp exist
     *
     * @param string $name Expiry key name
     *
     * @return bool true if the timestamp exists, false otherwise
     **/
    function hasTimestamp( $name )
    {
        return isset( $this->Timestamps[$name] );
    }

    /**
     * Returns the expiry timestamp for a key
     *
     * @param string $name Expiry key
     * @param string $arrayKey Sub array key in which the value is stored
     *
     * @return int|false The timestamp if it exists, false otherwise
     **/
    function timestamp( $name, $arrayKey = null )
    {
        if ( !isset( $this->Timestamps[$name] ) )
        {
            eZDebug::writeError( "Unknown expiry timestamp called '$name'", 'eZExpiryHandler::timestamp' );
            return false;
        }

        if ( $arrayKey === null )
        {
            return $this->Timestamps[$name];
        }

        if ( !isset( $this->Timestamps[$name][$arrayKey] ) )
        {
            eZDebug::writeError( "Unknown expiry timestamp called '$name[$arrayKey]'", 'eZExpiryHandler::timestamp' );
            return false;
        }

        return $this->Timestamps[$name][$arrayKey];
    }

    /**
     * Returns the expiry timestamp for a key, or a default value if it isn't set
     *
     * @param string $name Expiry key name
     * @param int $default Default value that will be returned if the key isn't set
     * @param string $arrayKey Sub array key in which the value is stored
     *
     * @return mixed The expiry timestamp, or $default
     **/
    static function getTimestamp( $name, $default = false, $arrayKey = null )
    {
        $handler = eZExpiryHandler::instance();
        if ( !isset( $handler->Timestamps[$name] ) )
        {
            return $default;
        }

        if ( $arrayKey === null )
        {
            return $handler->Timestamps[$name];
        }

        if ( !isset( $handler->Timestamps[$name][$arrayKey] ) )
        {
            return $default;
        }

        return $handler->Timestamps[$name][$arrayKey];
    }

    /**
     * Returns a shared instance of the eZExpiryHandler class
     *
     * @return eZExpiryHandler
     */
    static function instance()
    {
        if ( !isset( $GLOBALS['eZExpiryHandlerInstance'] ) ||
             !( $GLOBALS['eZExpiryHandlerInstance'] instanceof eZExpiryHandler ) )
        {
            $GLOBALS['eZExpiryHandlerInstance'] = new eZExpiryHandler();
        }

        return $GLOBALS['eZExpiryHandlerInstance'];
    }

    /**
     * Checks if a shared instance of eZExpiryHandler exists
     *
     * @return bool true if an instance exists, false otherwise
     **/
    static function hasInstance()
    {
        return isset( $GLOBALS['eZExpiryHandlerInstance'] ) && $GLOBALS['eZExpiryHandlerInstance'] instanceof eZExpiryHandler;
    }

    /**
     * Called at the end of execution and will store the data if it is modified.
     **/
    static function shutdown()
    {
        if ( eZExpiryHandler::hasInstance() )
        {
            eZExpiryHandler::instance()->store();
        }
    }

    /**
     * Registers the shutdown function.
     * @see eZExpiryHandler::shutdown()
     **/
    public static function registerShutdownFunction(){
        if ( !eZExpiryHandler::$isShutdownFunctionRegistered ) {
            register_shutdown_function( array('eZExpiryHandler', 'shutdown') );
            eZExpiryHandler::$isShutdownFunctionRegistered = true;
        }
    }

    /**
     * Returns the data modification status
     *
     * @return bool true if data was modified, false if it wasn't
     * @deprecated 4.2 will be removed in 4.3
     **/
    public function isModified()
    {
        return $this->IsModified;
    }

    /**
     * Indicates if thre shutdown function has been registered
     * @var bool
     **/
    private static $isShutdownFunctionRegistered = false;

    /**
     * Holds the expiry timestamps array
     * @var array
     **/
    public $Timestamps;

    /**
     * Wether data has been modified or not
     * @var bool
     **/
    public $IsModified;
}

?>
