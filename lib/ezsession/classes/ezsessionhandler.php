<?php
/**
 * File containing abstract session handler
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package lib
 */

/** Abstract session handler class to extend
 *
 * @abstract
 * @package lib
 * @subpackage ezsession
 * @since 4.4.0
 */
abstract class eZSessionHandler
{
    /**
     * __construct
     *
     * @param bool $userHasCookie
     */
    public function __construct( $userHasCookie = false )
    {
        $this->userHasCookie = $userHasCookie;
    }

    /**
     * Checks if session handler is connected with backend.
     *
     * @return bool
     */
    public function isConnected()
    {
        return true;
    }

    /**
     * Set it self as save handler
     *
     * @return bool
     */
    public function setSaveHandler()
    {
        session_module_name( 'user' );
        session_set_save_handler(
            array( $this, 'open' ),
            array( $this, 'close' ),
            array( $this, 'read' ),
            array( $this, 'write' ),
            array( $this, 'destroy' ),
            array( $this, 'gc' )
            );
        return true;
    }

    /**
     * Session open handler
     *
     * @param string $savePath
     * @param string $sessionName
     * @return bool
     */
    public function open( $savePath, $sessionName )
    {
        return true;
    }

    /**
     * Session close handler
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Session read handler
     *
     * @param string $sessionId
     * @return string|false Binary session data
     */
    abstract public function read( $sessionId );

    /**
     * Session write handler
     *
     * @param string $sessionId
     * @param string $sessionData Binary session data
     * @return bool
     */
    abstract public function write( $sessionId, $sessionData );

    /**
     * Regenerate session id
     * Callback (when $updateBackendData is true): "regenerate_[pre|post]"
     *           eZDB $db
     *           string $escNewKey
     *           string $escOldKey
     *           int $escUserID
     *
     * @param bool $updateBackendData True if we want to keep session data with the new session id
     * @return bool
     */
    abstract public function regenerate( $updateBackendData = true );

    /**
     * Session destroy handler
     * Callback: "destroy_[pre|post]"
     *           eZDB $db
     *           string $sessionId
     *           string $escKey
     *
     * @param string $sessionId
     * @return bool
     */
    abstract public function destroy( $sessionId );

   /**
     * Session gc (garbageCollector) handler
     * Callback: "gc_[pre|post]"
     *           eZDB $db
     *           int $maxLifeTime
     *
     * @param int $maxLifeTime In seconds
     * @return bool
     */
    abstract public function gc( $maxLifeTime );

    /**
     * Cleaup session data
     * Callback: "cleanup_[pre|post]"
     *           eZDB $db
     *
     * @return bool
     */
    abstract public function cleanup();

    /**
     * Counts the number of active session and returns it.
     *
     * @return int|null Returns null if handler does not support this.
     */
    static public function countActive()
    {
        return null;
    }

    /**
     * Signals that handler uses ezsession table
     * If not, then features like gc, cleanup and countActive is unsupported.
     *
     * @return bool
     */
    static public function usesDatabaseTable()
    {
        return true;
    }
}
?>
