<?php
//
// Definition of eZSession class
//
// Created on: <19-Aug-2002 12:49:18 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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
  To create a hook function the global variable \c eZSessionFunctions must be
  filled in. This contains an associative array with hook points, e.g.
  \c destroy_pre which is checked by the session system.
  Each hook point must contain an array with function names to execute, if the
  hook point does not exist the session handler will not handle the hooks.

  \code
  function cleanupStuff( $db, $key, $escKey )
  {
      // Do cleanup here
  }
  if ( !isset( $GLOBALS['eZSessionFunctions']['destroy_pre'] ) )
      $GLOBALS['eZSessionFunctions']['destroy_pre'] = array();
  $GLOBALS['eZSessionFunctions']['destroy_pre'][] = 'cleanupstuff';
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
  \c empty_pre and \c empty_post hooks. The signature of the function is
  function empty( $db )

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

function eZSessionOpen( )
{
    // do nothing eZDB will open connection when needed.
}

function eZSessionClose( )
{
    // eZDB will handle closing the database
}

function eZSessionRead( $key )
{
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();

    $key = $db->escapeString( $key );

    $sessionRes = $db->arrayQuery( "SELECT data, user_id, expiration_time FROM ezsession WHERE session_key='$key'" );

    if ( $sessionRes !== false and count( $sessionRes ) == 1 )
    {
        $ini = eZINI::instance();

        $sessionUpdatesTime = $sessionRes[0]['expiration_time'] - $ini->variable( 'Session', 'SessionTimeout' );
        $sessionIdle = time() - $sessionUpdatesTime;

        $GLOBALS['eZSessionUserID'] = $sessionRes[0]['user_id'];
        $GLOBALS['eZSessionIdleTime'] = $sessionIdle;

        /*
         * The line below is needed to correctly load list of content classes saved
         * in the CanInstantiateClassList session variable.
         * Without this we get incomplete classes loaded (__PHP_Incomplete_Class).
         */
        require_once( 'kernel/classes/ezcontentclass.php' );

        return $sessionRes[0]['data'];
    }
    else
    {
        return false;
    }
}

/*!
  Will write the session information to database.
*/
function eZSessionWrite( $key, $value )
{
//    //include_once( 'lib/ezdb/classes/ezdb.php' );

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
//    $value = $db->escapeString( $value );
    $escKey = $db->escapeString( $key );
    // check if session already exists

    $userID = 0;
    if ( isset( $GLOBALS['eZSessionUserID'] ) )
        $userID = $GLOBALS['eZSessionUserID'];
    $userID = $db->escapeString( $userID );

    $sessionRes = $db->arrayQuery( "SELECT session_key FROM ezsession WHERE session_key='$escKey'" );

    if ( count( $sessionRes ) == 1 )
    {
        if ( isset( $GLOBALS['eZSessionFunctions']['update_pre'] ) )
        {
            foreach ( $GLOBALS['eZSessionFunctions']['update_pre'] as $func )
            {
                $func( $db, $key, $escKey, $expirationTime, $userID, $value );
            }
        }

        $updateQuery = "UPDATE ezsession
                    SET expiration_time='$expirationTime', data=$value, user_id='$userID'
                    WHERE session_key='$escKey'";

        $ret = $db->query( $updateQuery );

        if ( isset( $GLOBALS['eZSessionFunctions']['update_post'] ) )
        {
            foreach ( $GLOBALS['eZSessionFunctions']['update_post'] as $func )
            {
                $func( $db, $key, $escKey, $expirationTime, $userID, $value );
            }
        }
    }
    else
    {
        if ( isset( $GLOBALS['eZSessionFunctions']['insert_pre'] ) )
        {
            foreach ( $GLOBALS['eZSessionFunctions']['insert_pre'] as $func )
            {
                $func( $db, $key, $escKey, $expirationTime, $userID, $value );
            }
        }

        $insertQuery = "INSERT INTO ezsession
                    ( session_key, expiration_time, data, user_id )
                    VALUES ( '$escKey', '$expirationTime', $value, '$userID' )";

        $ret = $db->query( $insertQuery );

        if ( isset( $GLOBALS['eZSessionFunctions']['insert_post'] ) )
        {
            foreach ( $GLOBALS['eZSessionFunctions']['insert_post'] as $func )
            {
                $func( $db, $key, $escKey, $expirationTime, $userID, $value );
            }
        }
    }
}

/*!
  Will remove a session from the database.
*/
function eZSessionDestroy( $key )
{
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();

    $escKey = $db->escapeString( $key );
    if ( isset( $GLOBALS['eZSessionFunctions']['destroy_pre'] ) )
    {
        foreach ( $GLOBALS['eZSessionFunctions']['destroy_pre'] as $func )
        {
            $func( $db, $key, $escKey );
        }
    }

    $query = "DELETE FROM ezsession WHERE session_key='$escKey'";
    $db->query( $query );

    if ( isset( $GLOBALS['eZSessionFunctions']['destroy_post'] ) )
    {
        foreach ( $GLOBALS['eZSessionFunctions']['destroy_post'] as $func )
        {
            $func( $db, $key, $escKey );
        }
    }
}

/*!
  Handles session cleanup. Will delete timed out sessions from the database.
*/
function eZSessionGarbageCollector()
{
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();
    $time = time();

    if ( isset( $GLOBALS['eZSessionFunctions']['gc_pre'] ) )
    {
        foreach ( $GLOBALS['eZSessionFunctions']['gc_pre'] as $func )
        {
            $func( $db, $time );
        }
    }

    $query = "DELETE FROM ezsession WHERE expiration_time < " . $time;

    $db->query( $query );

    if ( isset( $GLOBALS['eZSessionFunctions']['gc_post'] ) )
    {
        foreach ( $GLOBALS['eZSessionFunctions']['gc_post'] as $func )
        {
            $func( $db, $time );
        }
    }
}

/*!
  Removes all entries from session.
*/
function eZSessionEmpty()
{
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();

    if ( isset( $GLOBALS['eZSessionFunctions']['empty_pre'] ) )
    {
        foreach ( $GLOBALS['eZSessionFunctions']['empty_pre'] as $func )
        {
            $func( $db );
        }
    }

    $query = "TRUNCATE TABLE ezsession";

    $db->query( $query );

    if ( isset( $GLOBALS['eZSessionFunctions']['empty_post'] ) )
    {
        foreach ( $GLOBALS['eZSessionFunctions']['empty_post'] as $func )
        {
            $func( $db );
        }
    }
}

/*!
  Counts the number of active session and returns it.
*/
function eZSessionCountActive()
{
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();
    $query = "SELECT count( * ) AS count FROM ezsession";

    $rows = $db->arrayQuery( $query );
    return $rows[0]['count'];
}

/*!
 Register the needed session functions.
 Call this only once.
*/
function eZRegisterSessionFunctions()
{
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
        'ezsessionopen',
        'ezsessionclose',
        'ezsessionread',
        'ezsessionwrite',
        'ezsessiondestroy',
        'ezsessiongarbagecollector' );
}

/*!
 Makes sure that the session is started properly.
 Multiple calls will just be ignored.
*/
function eZSessionStart()
{
    // Check if we are allowed to use sessions
    if ( isset( $GLOBALS['eZSiteBasics'] ) &&
         isset( $GLOBALS['eZSiteBasics']['session-required'] ) &&
         !$GLOBALS['eZSiteBasics']['session-required'] )
    {
        return false;
    }
    if ( isset( $GLOBALS['eZSessionIsStarted'] ) &&
         $GLOBALS['eZSessionIsStarted'] )
    {
         return false;
    }
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();
    if ( !$db->isConnected() )
    {
        return false;
    }
    eZRegisterSessionFunctions();
    $ini = eZINI::instance();
    $cookieTimeout = isset( $GLOBALS['RememberMeTimeout'] ) ? $GLOBALS['RememberMeTimeout'] : $ini->variable( 'Session', 'CookieTimeout' );

    if ( is_numeric( $cookieTimeout ) )
    {
        session_set_cookie_params( (int)$cookieTimeout );
    }
    session_start();
    return $GLOBALS['eZSessionIsStarted'] = true;
}

/*!
 Makes sure session data is stored in the session and stops the session.
*/
function eZSessionStop()
{
    if ( isset( $GLOBALS['eZSessionIsStarted'] ) &&
         !$GLOBALS['eZSessionIsStarted'] )
    {
         return false;
    }
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();
    if ( !$db->isConnected() )
    {
        return false;
    }
    session_write_close();
    $GLOBALS['eZSessionIsStarted'] = false;
    return true;
}

/*!
 Will make sure the user gets a new session ID while keepin the session data.
 This is useful to call on logins, to avoid sessions theft from users.
 \note This requires PHP 4.3.2 and higher which has the session_regenerate_id
 \return \c true if succesful
*/
function eZSessionRegenerate()
{
    if ( isset( $GLOBALS['eZSessionIsStarted'] ) &&
         !$GLOBALS['eZSessionIsStarted'] )
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

/*!
 Removes the current session and resets session variables.
*/
function eZSessionRemove()
{
    if ( isset( $GLOBALS['eZSessionIsStarted'] ) &&
         !$GLOBALS['eZSessionIsStarted'] )
    {
         return false;
    }
    //include_once( 'lib/ezdb/classes/ezdb.php' );
    $db = eZDB::instance();
    if ( !$db->isConnected() )
    {
        return false;
    }
    $_SESSION = array();
    session_destroy();
    $GLOBALS['eZSessionIsStarted'] = false;
    return true;
}

/*!
 Sets the current user ID to \a $userID,
 this ID will be written to the session table field user_id
 when the page is done.
 \sa eZSessionUserID
*/
function eZSessionSetUserID( $userID )
{
    $GLOBALS['eZSessionUserID'] = $userID;
}

/*!
 Returns the current session ID.
 The session handler will not care about value of the ID,
 it's entirely up to the clients of the session handler to use and update this value.
*/
function eZSessionUserID()
{
    if ( isset( $GLOBALS['eZSessionUserID'] ) )
        return $GLOBALS['eZSessionUserID'];
    return 0;
}

?>
