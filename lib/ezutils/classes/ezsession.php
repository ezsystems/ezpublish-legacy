<?php
//
// Definition of eZSession class
//
// Created on: <19-Aug-2002 12:49:18 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  Re-implementation of PHP session management using database.

  The session system has a hook system which allows external code to perform
  extra tasks before and after a certain action is executed. For instance to
  cleanup a table when sessions are removed.
  This can be used by adding a callback with the eZSession::addCallback function,
  first param is type and second is callback (called with call_user_func_array)

  \code
  function cleanupStuff( $db, $key, $escKey )
  {
      // Do cleanup here
  }

  eZSession::addCallback( 'destroy_pre', 'cleanupstuff');
  // Or if it was a class function:
  // eZSession::addCallback( 'destroy_pre', array('myClass', 'myCleanupStuff') );
  \endcode

  When new data is inserted to the database it will call the \c update_pre and
  \c update_post hooks. The signature of the function is
  function insert( $db, $key, $escapedKey, $expirationTime, $userID, $value )

  When existing data is updated in the databbase it will call the \c insert_pre
  and \c insert_post hook. The signature of the function is
  function update( $db, $key, $escapedKey, $expirationTime, $userID, $value )

  When a specific session is destroyed in the database it will call the
  \c destroy_pre and \c destroy_post hooks. The signature of the function is
  function destroy( $db, $key, $escapedKey )

  When multiple sessions are expired (garbage collector) in the database it
  will call the \c gc_pre and \c gc_post hooks. The signature of the function is
  function gcollect( $db, $expiredTime )

  When all sessionss are removed from the database it will call the
  \c cleanup_pre and \c cleanup_post hooks. The signature of the function is
  function cleanup( $db )

  \param $db The database object used by the session manager.
  \param $key The session key which are being worked on, see also \a $escapedKey
  \param $escapedKey The same key as \a $key but is escaped correctly for the database.
                     Use this to save a call to eZDBInterface::escapeString()
  \param $expirationTime An integer specifying the timestamp of when the session
                         will expire.
  \param $expiredTime Similar to \a $expirationTime but is the time used to figure
                      if a session is expired in the database. ie. all sessions with
                      lower expiration times will be removed.
*/


class eZSession
{
    // name of session handler, change if you override class with autoload
    const HANDLER_NAME = 'ezdb';

    /**
     * User id, see {@link eZSession::userID()}.
     *
     * @access protected
     */
    static protected $userID = 0;

    /**
     * Flag session started, see {@link eZSession::start()}.
     *
     * @access protected
     */
    static protected $hasStarted = false;

    /**
     * Flag eZSession as registrated session handler in {@link eZSession::registerFunctions()}.
     *
     * @access protected
     */
    static protected $isRegistrated = false;

    /**
     * List of callback actions, see {@link eZSession::addCallback()}.
     *
     * @access protected
     */
    static protected $callbackFunctions = array(); 

    /**
     * Constructor
     *
     * @access protected
     */
    protected function eZSession()
    {
    }

    /**
     * Does nothing, eZDB will open connection when needed.
     */
    static public function open()
    {
    }

    /**
     * Does nothing, eZDB will handle closing db connection.
     */
    static public function close()
    {
    }

    /**
     * Reads the session data from the database, this function is 
     * register in {@link eZSession::registerFunctions()}
     *
     * @param string $sessionId
     * @return string|false Returns false if session doesn't exits
     */
    static public function read( $sessionId )
    {
        $db = eZDB::instance();
        $escKey = $db->escapeString( $sessionId );

        $sessionRes = $db->arrayQuery( "SELECT data, user_id, expiration_time FROM ezsession WHERE session_key='$escKey'" );

        if ( $sessionRes !== false and count( $sessionRes ) == 1 )
        {
            $ini = eZINI::instance();

            $sessionUpdatesTime = $sessionRes[0]['expiration_time'] - $ini->variable( 'Session', 'SessionTimeout' );
            $sessionIdle = time() - $sessionUpdatesTime;

            self::$userID = $sessionRes[0]['user_id'];
            $GLOBALS['eZSessionIdleTime'] = $sessionIdle;

            return $sessionRes[0]['data'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Inserts|Updates the session data in the database, this function is 
     * register in {@link eZSession::registerFunctions()}
     *
     * @param string $sessionId
     */
    static public function write( $sessionId, $value )
    {
        if ( isset( $GLOBALS["eZRequestError"] ) && $GLOBALS["eZRequestError"] )
        {
            return;
        }

        $db = eZDB::instance();
        $ini = eZINI::instance();
        $expirationTime = time() + $ini->variable( 'Session', 'SessionTimeout' );

        if ( $db->bindingType() != eZDBInterface::BINDING_NO )
        {
            $value = $db->bindVariable( $value, array( 'name' => 'data' ) );
        }
        else
        {
            $value = '\'' . $db->escapeString( $value ) . '\'';
        }
        $escKey = $db->escapeString( $sessionId );

        $userID = $db->escapeString( self::$userID );

        // check if session already exists
        $sessionRes = $db->arrayQuery( "SELECT session_key FROM ezsession WHERE session_key='$escKey'" );

        if ( $sessionRes !== false and count( $sessionRes ) == 1 )
        {
            self::triggerCallback( 'update_pre', array( $db, $sessionId, $escKey, $expirationTime, $userID, $value ) );

            $updateQuery = "UPDATE ezsession
                        SET expiration_time='$expirationTime', data=$value, user_id='$userID'
                        WHERE session_key='$escKey'";
            $ret = $db->query( $updateQuery );

            self::triggerCallback( 'update_post', array( $db, $sessionId, $escKey, $expirationTime, $userID, $value ) );
        }
        else
        {
            self::triggerCallback( 'insert_pre', array( $db, $sessionId, $escKey, $expirationTime, $userID, $value ) );

            $insertQuery = "INSERT INTO ezsession
                        ( session_key, expiration_time, data, user_id )
                        VALUES ( '$escKey', '$expirationTime', $value, '$userID' )";
            $ret = $db->query( $insertQuery );

            self::triggerCallback( 'insert_post', array( $db, $sessionId, $escKey, $expirationTime, $userID, $value ) );
        }
    }

    /**
     * Deletes the session data from the database, this function is 
     * register in {@link eZSession::registerFunctions()}
     *
     * @param string $sessionId
     */
    static public function destroy( $sessionId )
    {
        $db = eZDB::instance();
        $escKey = $db->escapeString( $sessionId );

        self::triggerCallback( 'destroy_pre', array( $db, $sessionId, $escKey ) );

        $db->query( "DELETE FROM ezsession WHERE session_key='$escKey'" );

        self::triggerCallback( 'destroy_post', array( $db, $sessionId, $escKey ) );
    }

    /**
     * Deletes all expired session data in the database, this function is 
     * register in {@link eZSession::registerFunctions()}
     */
    static public function garbageCollector()
    {
        $db = eZDB::instance();
        $time = time();

        self::triggerCallback( 'gc_pre', array( $db, $time ) );

        $db->query( "DELETE FROM ezsession WHERE expiration_time < $time" );

        self::triggerCallback( 'gc_post', array( $db, $time ) );
    }

    /**
     * Truncates all session data in the database.
     * Named eZSessionEmpty() in eZ Publish 4.0 and earlier!
     */
    static public function cleanup()
    {
        $db = eZDB::instance();

        self::triggerCallback( 'cleanup_pre', array( $db ) );

        $db->query( "TRUNCATE TABLE ezsession" );

        self::triggerCallback( 'cleanup_post', array( $db ) );
    }

    /**
     * Counts the number of active session and returns it.
     * 
     * @return string Returns number of sessions.
     */
    static public function countActive()
    {
        $db = eZDB::instance();

        $rows = $db->arrayQuery( "SELECT count( * ) AS count FROM ezsession" );
        return $rows[0]['count'];
    }

    /**
     * Register the needed session functions, this is called automatically by 
     * {@link eZSession::start()}, so only call this if you don't start the session.
     * Named eZRegisterSessionFunctions() in eZ Publish 4.0 and earlier!
     * 
     * @return bool Returns true|false depending on if eZSession is registrated as session handler.
    */
    static protected function registerFunctions()
    {
        if ( self::$isRegistrated )
            return false;
        session_module_name( 'user' );
        $ini = eZINI::instance();
        if ( $ini->variable( 'Session', 'SessionNameHandler' ) == 'custom' )
        {
            $sessionName = $ini->variable( 'Session', 'SessionNamePrefix' );
            if ( $ini->variable( 'Session', 'SessionNamePerSiteAccess' ) == 'enabled' )
            {
                $access = $GLOBALS['eZCurrentAccess'];
                $sessionName .=  $access['name'];
            }
            session_name( $sessionName );
        }

        session_set_save_handler(
            array('eZSession', 'open'),
            array('eZSession', 'close'),
            array('eZSession', 'read'),
            array('eZSession', 'write'),
            array('eZSession', 'destroy'),
            array('eZSession', 'garbageCollector')
            );
        return self::$isRegistrated = true;
    }

    /**
     * Starts the session and sets the timeout of the session cookie.
     * Multiple calls will be ignored unless you call {@link eZSession::stop()} first.
     * 
     * @return bool Returns true|false depending on if session was started.
     */
    static public function start( $cookieTimeout = false )
    {
        // Check if we are allowed to use sessions
        if ( isset( $GLOBALS['eZSiteBasics'] ) &&
             isset( $GLOBALS['eZSiteBasics']['session-required'] ) &&
             !$GLOBALS['eZSiteBasics']['session-required'] )
        {
            return false;
        }
        if ( self::$hasStarted )
        {
             return false;
        }
        $db = eZDB::instance();
        if ( !$db->isConnected() )
        {
            return false;
        }
        self::registerFunctions();
        if ( $cookieTimeout == false )
        {
            $ini = eZINI::instance();
            $cookieTimeout = $ini->variable( 'Session', 'CookieTimeout' );
        }

        if ( is_numeric( $cookieTimeout ) )
        {
            session_set_cookie_params( (int)$cookieTimeout );
        }
        session_start();
        return self::$hasStarted = true;
    }

    /**
     * Writes session data and stops the session, if not already stopped.
     * 
     * @return bool Returns true|false depending on if session was stopped.
     */
    static public function stop()
    {
        if ( !self::$hasStarted )
        {
             return false;
        }
        $db = eZDB::instance();
        if ( !$db->isConnected() )
        {
            return false;
        }
        session_write_close();
        self::$hasStarted = false;
        return true;
    }

    /**
     * Will make sure the user gets a new session ID while keepin the session data.
     * This is useful to call on logins, to avoid sessions theft from users.
     * 
     * @return bool Returns true|false depending on if session was regenerated.
     */
    static public function regenerate()
    {
        if ( !self::$hasStarted )
        {
             return false;
        }
        if ( !function_exists( 'session_regenerate_id' ) )
        {
            return false;
        }
        // This doesn't seem to work as expected
    //     session_regenerate_id();
        return true;
    }

    /**
     * Removes the current session and resets session variables.
     * 
     * @return bool Returns true|false depending on if session was removed.
     */
    static public function remove()
    {
        if ( !self::$hasStarted )
        {
             return false;
        }
        $db = eZDB::instance();
        if ( !$db->isConnected() )
        {
            return false;
        }
        $_SESSION = array();
        session_destroy();
        self::$hasStarted = false;
        return true;
    }

    /**
     * Sets the current userID used by self::write on shutdown.
     * 
     * @param int $userID to use in {@link eZSession::write()}
     */
    static public function setUserID( $userID )
    {
        self::$userID = $userID;
    }

    /**
     * Gets the current user id.
     * 
     * @return int Returns user id stored by {@link eZSession::setUserID()}
     */
    static public function userID()
    {
        return self::$userID;
    }

    /**
     * Adds a callback function, to be triggered by {@link eZSession::triggerCallback()}
     * when a certan session event occurs.
     * Use: eZSession::addCallback('gc_pre', myCustomGarabageFunction );
     * 
     * @param string $type cleanup, gc, destroy, insert and update, pre and post types.
     * @param handler $callback a function to call.
     */
    static public function addCallback( $type, $callback )
    {
        if ( !isset( self::$callbackFunctions[$type] ) )
        {
            self::$callbackFunctions[$type] = array();
        }
        self::$callbackFunctions[$type][] = $callback;
    }

    /**
     * Triggers callback functions by type, registrated by {@link eZSession::addCallback()}
     * Use: eZSession::triggerCallback('gc_pre', array( $db, $time ) );
     * 
     * @param string $type cleanup, gc, destroy, insert and update, pre and post types.
     * @param array $params list of parameters to pass to the callback function.
     */
    static public function triggerCallback( $type, $params )
    {
        if ( isset( self::$callbackFunctions[$type] ) )
        {
            foreach( self::$callbackFunctions[$type] as $callback )
            {
                call_user_func_array( $callback, $params );
            }
            return true;
        }
        return false;
    }
}

// DEPRECATED (For BC use only)
function eZSessionStart()
{
    eZSession::start();
}

?>