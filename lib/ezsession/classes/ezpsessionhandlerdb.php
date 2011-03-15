<?php
/**
 * File containing DB session handler
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package lib
 */

/** DB session handler class
 *
 * @since 4.4
 * @package lib
 * @subpackage ezsession
 */
class ezpSessionHandlerDB extends ezpSessionHandler
{
    // Seconds before timeout occurs that gc function stops to make sure request completes
    const GC_TIMEOUT_MARGIN = 5;

    // Max execution time if php's setting evaluates to false, to avoid hitting http server timeout
    const GC_MAX_EXECUTION_TIME = 300;

    // Same as above when in CLI mode
    const GC_MAX_EXECUTION_TIME_CLI = 3000;

    /**
     * Specifies how many sessions should be deleted pr iteration
     * when garbage collecting sessions (to avoid sql calls that lock db)
     *
     * @var int|false
     */
    public $gcSessionsPrIteration = 50;

    /**
     * Checks if session handler is connected with backend.
     *
     * @return bool
     */
    public function isConnected()
    {
        $db = eZDB::instance();
        return $db->isConnected();
    }

    /**
     * Session read handler
     *
     * @param string $sessionId
     * @return string|false Binary session data
     */
    public function read( $sessionId )
    {

        $db = eZDB::instance();
        if ( !$db->isConnected() )
        {
            return false;
        }

        $escKey = $db->escapeString( $sessionId );

        $sessionRes = !$this->userHasCookie ? false : $db->arrayQuery( "SELECT data, user_id, expiration_time FROM ezsession WHERE session_key='$escKey'" );

        if ( $sessionRes !== false and count( $sessionRes ) == 1 )
        {
            eZSession::setUserID( $sessionRes[0]['user_id'] );
            $ini = eZINI::instance();

            $sessionUpdatesTime = $sessionRes[0]['expiration_time'] - $ini->variable( 'Session', 'SessionTimeout' );
            $sessionIdle = time() - $sessionUpdatesTime;

            $GLOBALS['eZSessionIdleTime'] = $sessionIdle;

            return $sessionRes[0]['data'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Session write handler
     *
     * @param string $sessionId
     * @param string $sessionData Binary session data
     * @return bool
     */
    public function write( $sessionId, $sessionData )
    {
        $db = eZDB::instance();
        if ( !$db->isConnected() )
        {
            return false;
        }

        $ini = eZINI::instance();
        $expirationTime = time() + $ini->variable( 'Session', 'SessionTimeout' );

        if ( $db->bindingType() != eZDBInterface::BINDING_NO )
        {
            $sessionData = $db->bindVariable( $sessionData, array( 'name' => 'data' ) );
        }
        else
        {
            $sessionData = '\'' . $db->escapeString( $sessionData ) . '\'';
        }
        $escKey = $db->escapeString( $sessionId );
        $userID = $db->escapeString( eZSession::userID() );

        // check if session already exists
        $sessionRes = !$this->userHasCookie ? false : $db->arrayQuery( "SELECT session_key FROM ezsession WHERE session_key='$escKey'" );

        if ( $sessionRes !== false and count( $sessionRes ) == 1 )
        {
            $ret = $db->query( "UPDATE ezsession SET expiration_time='$expirationTime', data=$sessionData, user_id='$userID' WHERE session_key='$escKey'" );
        }
        else
        {
            $insertQuery = "INSERT INTO ezsession ( session_key, expiration_time, data, user_id )
                        VALUES ( '$escKey', '$expirationTime', $sessionData, '$userID' )";
            $ret = $db->query( $insertQuery );
        }
        return true;
    }

    /**
     * Session destroy handler
     *
     * @param string $sessionId
     * @return bool
     */
    public function destroy( $sessionId )
    {
        ezpEvent::getInstance()->notify( 'session/destroy', array( $sessionId ) );

        $db = eZDB::instance();
        $escKey = $db->escapeString( $sessionId );

        eZSession::triggerCallback( 'destroy_pre', array( $db, $sessionId, $escKey ) );
        $db->query( "DELETE FROM ezsession WHERE session_key='$escKey'" );
        eZSession::triggerCallback( 'destroy_post', array( $db, $sessionId, $escKey ) );

        return true;
    }

    /**
     * Regenerate session id
     *
     * @param bool $updateBackendData (true if we want to keep session data with the new session id)
     * @return bool
     */
    public function regenerate( $updateBackendData = true )
    {
        $oldSessionId = session_id();
        session_regenerate_id();
        $newSessionId = session_id();

        ezpEvent::getInstance()->notify( 'session/regenerate', array( $oldSessionId, $newSessionId ) );

        if ( $updateBackendData )
        {
            $db = eZDB::instance();
            if ( !$db->isConnected() )
            {
                return false;
            }

            $escOldKey = $db->escapeString( $oldSessionId );
            $escNewKey = $db->escapeString( $newSessionId );
            $escUserID = $db->escapeString( eZSession::userID() );
            eZSession::triggerCallback( 'regenerate_pre', array( $db, $escNewKey, $escOldKey, $escUserID ) );

            $db->query( "UPDATE ezsession SET session_key='$escNewKey', user_id='$escUserID' WHERE session_key='$escOldKey'" );
            $db->query( "UPDATE ezbasket SET session_id='$escNewKey' WHERE session_id='$escOldKey'" );

            eZSession::triggerCallback( 'regenerate_post', array( $db, $escNewKey, $escOldKey, $escUserID ) );
        }
        return true;
    }

   /**
     * Session gc (garbageCollector) handler
     *
     * @param int $maxLifeTime
     * @return bool
     */
    public function gc( $maxLifeTime )
    {
        ezpEvent::getInstance()->notify( 'session/gc', array( $maxLifeTime ) );
        $db = eZDB::instance();
        $gcCompleted = true;
        eZSession::triggerCallback( 'gc_pre', array( $db, $maxLifeTime ) );

        if ( $this->gcSessionsPrIteration )
        {
            $timedOut = false;
            $maxExecutionTime = ini_get( 'max_execution_time' );
            if ( !$maxExecutionTime )
            {
                if ( PHP_SAPI === 'cli' )
                    $maxExecutionTime = self::GC_MAX_EXECUTION_TIME_CLI;
                else
                    $maxExecutionTime = self::GC_MAX_EXECUTION_TIME;
            }

            do
            {
                $startTime = time();
                $rows = $db->arrayQuery( 'SELECT session_key FROM ezsession WHERE expiration_time < ' . $maxLifeTime ,  array( 'offset' => 0, 'limit' => $this->gcSessionsPrIteration, 'column' => 'session_key' ) );
                if ( $rows )
                {
                    $keyINString = '\'' . implode( '\', \'', $rows ) . '\'';// generateSQLINStatement does not add quotes when casting to string
                    $db->query( "DELETE FROM ezsession WHERE session_key IN ( $keyINString )" );

                    $stopTime = time();
                    $remaningTime = $maxExecutionTime - self::GC_TIMEOUT_MARGIN - ( $stopTime - $maxLifeTime );
                    if ( $remaningTime < ( $stopTime - $startTime ) )
                    {
                        $timedOut = true;
                        break;
                    }
                }
            } while ( $rows );
            $gcCompleted = !$timedOut || !$rows;
        }
        else
        {
            $db->query( 'DELETE FROM ezsession WHERE expiration_time < ' . $maxLifeTime );
        }

        eZSession::triggerCallback( 'gc_post', array( $db, $maxLifeTime ) );
        return $gcCompleted;
    }

    /**
     * Remove all session data (Truncate table)
     *
     * @return bool
     */
    public function cleanup()
    {
        ezpEvent::getInstance()->notify( 'session/cleanup', array() );
        $db = eZDB::instance();

        eZSession::triggerCallback( 'cleanup_pre', array( $db ) );
        $db->query( 'TRUNCATE TABLE ezsession' );
        eZSession::triggerCallback( 'cleanup_post', array( $db ) );

        return true;
    }

    /**
     * Remove all session data for a specific user
     *
     * @param array(int) $userIDArray
     */
    public function deleteByUserIDs( array $userIDArray )
    {
        // re use destroy to make sure it works with callbacks
        $db = eZDB::instance();
        $userINString = $db->generateSQLINStatement( $userIDArray, 'user_id', false, false, 'int' );
        $rows = $db->arrayQuery( "SELECT session_key FROM ezsession WHERE $userINString" );
        foreach ( $rows as $row )
        {
            $this->destroy( $row['session_key'] );
        }
    }

    /**
     * Counts the number of session and returns it.
     *
     * @return int|null Returns null if handler does not support this.
     */
    static public function count()
    {
        $db = eZDB::instance();
        $rows = $db->arrayQuery( 'SELECT count( * ) AS count FROM ezsession' );
        return $rows[0]['count'];
    }
}
?>
