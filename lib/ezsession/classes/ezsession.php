<?php
/**
 * File containing session interface
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/**
 * eZ Publish Session interface class
 *
 * Session wrapper for session management, with support for handlers.
 * Handler is defined by site.ini\[Session]\Handler setting.
 *
 * The session system has a hook system which allows external code to perform
 * extra tasks before and after a certain action is executed. For instance to
 * cleanup a table when sessions are removed.
 * This can be used by adding a callback with the eZSession::addCallback function,
 * first param is type and second is callback (called with call_user_func_array)
 *
 * \code
 * function cleanupStuff( $db, $key, $escKey )
 * {
 *     // Do cleanup here
 * }
 *
 * eZSession::addCallback( 'destroy_pre', 'cleanupstuff');
 * // Or if it was a class function:
 * // eZSession::addCallback( 'destroy_pre', array('myClass', 'myCleanupStuff') );
 * \endcode
 *
 * When a specific session is destroyed in the database it will call the
 * \c destroy_pre and \c destroy_post hooks. The signature of the function is
 * function destroy( $db, $key, $escapedKey )
 *
 * When a specific session is regenerated (login/logout) and kept it will call
 * \c regenerate_pre and \c regenerate_post hooks. The signature of the function is
 * function regenerate( $db, $escapedNewKey, $escapedOldKey, $escUserID )
 *
 * When multiple sessions are expired (garbage collector) in the database it
 * will call the \c gc_pre and \c gc_post hooks. The signature of the function is
 * function gcollect( $db, $expiredTime )
 *
 * When all sessions are removed from the database it will call the
 * \c cleanup_pre and \c cleanup_post hooks. The signature of the function is
 * function cleanup( $db )
 *
 * \param $db The database object used by the session manager.
 * \param $key The session key which are being worked on, see also \a $escapedKey
 * \param $escapedKey The same key as \a $key but is escaped correctly for the database.
 *                    Use this to save a call to eZDBInterface::escapeString()
 * \param $expirationTime An integer specifying the timestamp of when the session
 *                        will expire.
 * \param $expiredTime Similar to \a $expirationTime but is the time used to figure
 *                     if a session is expired in the database. ie. all sessions with
 *                     lower expiration times will be removed.
 *
 * @package lib
 * @subpackage ezsession
 */
class eZSession
{
    /**
     * User id, see {@link eZSession::userID()}.
     *
     * @var int
     */
    static protected $userID = 0;

    /**
     * Flag session has started, see {@link eZSession::start()}.
     *
     * @var bool
     */
    static protected $hasStarted = false;

    /**
     * Flag request contains session cookie, set in {@link eZSession::registerFunctions()}.
     *
     * @var bool|null
     */
    static protected $hasSessionCookie = null;

    /**
     * List of callback actions, see {@link eZSession::addCallback()}.
     *
     * @var array
     */
    static protected $callbackFunctions = array();

    /**
     * Current session handler or false, see {@link eZSession::getHandlerInstance()}.
     *
     * @var ezpSessionHandler|null
     */
    static protected $handlerInstance = null;

    /**
     * Session namespace.
     * If set, all session variables will be stored under $_SESSION[$namespace].
     *
     * @var string
     */
    static protected $namespace = null;

    /**
     * Constructor (not used, this is an all static class)
     */
    protected function __construct()
    {
    }

    /**
     * Returns the session array to use taking into account the namespace that
     * might have been injected.
     *
     * @since 5.0
     * @return byRef session array to use
     */
    static private function &sessionArray()
    {
        if ( self::$namespace !== null )
        {
            if ( !isset( $_SESSION[self::$namespace] ) )
                $_SESSION[self::$namespace] = array();
            return $_SESSION[self::$namespace];
        }
        return $_SESSION;
    }

    /**
     * Get session value (wrapper)
     *
     * @since 4.4
     * @param string|null $key Return the whole session array if null otherwise the value of $key
     * @param null|mixed $defaultValue Return this if not null and session has not started
     * @return mixed|null $defaultValue if key does not exist, otherwise session value depending on $key
     */
    static public function &get( $key = null, $defaultValue = null )
    {
        if ( self::$hasStarted === false )
        {
            if ( $defaultValue !== null )
                return $defaultValue;
            self::start();
        }
        $session =& self::sessionArray();

        if ( $key === null )
            return $session;
        else if ( isset( $session[$key] ) )
            return $session[$key];
        return $defaultValue;
    }

    /**
     * Set session value (wrapper)
     *
     * @since 4.4
     * @param string $key
     * @return bool
     */
    static public function set( $key, $value )
    {
        if ( self::$hasStarted === false )
        {
            self::start();
        }
        $session =& self::sessionArray();
        $session[$key] = $value;

        return true;
    }

    /**
     * Isset session value (wrapper)
     *
     * @since 4.4
     * @param string $key
     * @param bool $forceStart Force session start if true
     * @return bool|null Null if session has not started and $forceStart is false
     */
    static public function issetkey( $key, $forceStart = true )
    {
        if ( self::$hasStarted === false )
        {
            if ( !$forceStart )
                return null;
            self::start();
        }
        $session = self::sessionArray();
        return isset( $session[$key] );
    }

    /**
     * unset session value (wrapper)
     *
     * @since 4.4
     * @param string $key
     * @param bool $forceStart Force session start if true
     * @return bool|null True if value was removed, false if it did not exist and
     *                   null if session is not started and $forceStart is false
     */
    static public function unsetkey( $key, $forceStart = true )
    {
        if ( self::$hasStarted === false )
        {
            if ( !$forceStart )
                return null;
            self::start();
        }
        $session =& self::sessionArray();

        if ( !isset( $session[$key] ) )
            return false;

        unset( $session[$key] );
        return true;
    }

    /**
     * Deletes all expired session data in the database, this function is not supported
     * by session handlers that don't have a session backend on their own.
     *
     * @since 4.1
     * @return bool
     */
    static public function garbageCollector()
    {
        return self::getHandlerInstance()->gc( (int)$_SERVER['REQUEST_TIME'] );
    }

    /**
     * Truncates all session data in the database, this function is not supported
     * by session handlers that don't have a session backend on their own.
     *
     * @since 4.1
     * @return bool
     */
    static public function cleanup()
    {
        return self::getHandlerInstance()->cleanup();
    }

    /**
     * Counts the number of active session and returns it, this function is not supported
     * by session handlers that don't have a session backend on their own.
     *
     * @since 4.1
     * @return string Number of sessions.
     */
    static public function countActive()
    {
        return self::getHandlerInstance()->count();
    }

    /**
     * Register the needed session functions, this is called automatically by
     * {@link eZSession::start()}, so only call this if you don't start the session.
     *
     * @since 4.1
     * @return bool Depending on if eZSession is registrated as session handler.
    */
    static protected function registerFunctions( $sessionName = false, ezpSessionHandler $handler = null )
    {
        if ( self::$hasStarted || self::$handlerInstance !== null )
            return false;

        $ini = eZINI::instance();
        if ( $sessionName !== false )
        {
            session_name( $sessionName );
        }
        else if ( $ini->variable( 'Session', 'SessionNameHandler' ) === 'custom' )
        {
            $sessionName = $ini->variable( 'Session', 'SessionNamePrefix' );
            if ( $ini->variable( 'Session', 'SessionNamePerSiteAccess' ) === 'enabled' )
            {
                $access = $GLOBALS['eZCurrentAccess'];
                // Use md5 to make sure name is only consistent of alphanumeric characters
                $sessionName .=  md5( $access['name'] );
            }
            session_name( $sessionName );
        }
        else
        {
            $sessionName = session_name();
        }

        // See if user has session, used to avoid reading from db if no session.
        // Allow session bye post params for use by flash, but use $_POST directly
        // to avoid session double start issues ( #014686 ) caused by eZHTTPTool
        if ( isset( $_POST[ $sessionName ] ) )
        {
            // First use session id from post params (for use in flash upload)
            session_id( $_POST[ $sessionName ] );
            self::$hasSessionCookie = true;
        }
        else
        {
            // else check cookie as used by default
            self::$hasSessionCookie = isset( $_COOKIE[ $sessionName ] );
        }

        return self::getHandlerInstance( $handler )->setSaveHandler();
    }

    /**
     * Set the Cookie timeout of session cookie.
     *
     * @since 5.0
     * @param int $lifetime Cookie timeout of the session cookie
     */
    static public function setCookieLifetime( $lifetime )
    {
        session_set_cookie_params( (int)$lifetime );
    }

    /**
     * Set default cookie parameters based on site.ini settings (fallback to php.ini settings)
     * Used by {@link eZSession::registerFunctions()}. If you only want to set
     * the cookie lifetime, use {@link eZSession::setCookieLifetime} instead.
     * Note: this will only have affect when session is created / re-created
     *
     * @since 4.4
     * @param int|false $lifetime Cookie timeout of session cookie, will read from ini if not set
    */
    static public function setCookieParams( $lifetime = false )
    {
        $ini      = eZINI::instance();
        $params   = session_get_cookie_params();
        if ( $lifetime === false )
        {
            if ( $ini->hasVariable( 'Session', 'CookieTimeout' )
              && $ini->variable( 'Session', 'CookieTimeout' ) )
                $lifetime = (int) $ini->variable('Session', 'CookieTimeout');
            else
                $lifetime = $params['lifetime'];
        }
        $path   = $ini->hasVariable( 'Session', 'CookiePath' )     ? $ini->variable( 'Session', 'CookiePath' )     : $params['path'];
        $domain = $ini->hasVariable( 'Session', 'CookieDomain' )   ? $ini->variable( 'Session', 'CookieDomain' )   : $params['domain'];
        if ( $ini->hasVariable( 'Session', 'CookieSecure' ) )
        {
            $secure = ( $ini->variable( 'Session', 'CookieSecure' ) == 'true' ) ? true : false ;
        }
        else
        {
            $secure = $params['secure'];
        }
        if ( isset( $params['httponly'] ) ) // only available on PHP 5.2 and up
        {
            if ( $ini->hasVariable( 'Session', 'CookieHttponly') )
            {
                $httponly = ( $ini->variable( 'Session', 'CookieHttponly' ) == 'true' ) ? true : false ;
            }
            else
            {
                $httponly = $params['httponly'];
            }
            session_set_cookie_params( $lifetime, $path, $domain, $secure, $httponly );
        }
        else
        {
            session_set_cookie_params( $lifetime, $path, $domain, $secure );
        }
    }

    /**
     * Starts the session and sets the timeout of the session cookie.
     * Multiple calls will be ignored unless you call {@link eZSession::stop()} first.
     *
     * @since 4.1
     * @param int|false $cookieTimeout Use this to set custom cookie timeout.
     * @return bool Depending on if session was started.
     */
    static public function start( $cookieTimeout = false )
    {
        if ( self::lazyStart( false ) === false )
        {
             return false;
        }
        self::setCookieParams( $cookieTimeout );
        return self::forceStart();
    }

    /**
     * Inits eZSession and starts it if user has cookie and $startIfUserHasCookie is true.
     *
     * @since 4.4
     * @param bool $startIfUserHasCookie
     * @return bool|null
     */
    static public function lazyStart( $startIfUserHasCookie = true )
    {
        if ( self::$hasStarted ||
           ( isset( $GLOBALS['eZSiteBasics']['session-required'] ) &&
             !$GLOBALS['eZSiteBasics']['session-required'] ) )
        {
            return false;
        }
        self::registerFunctions();
        if ( $startIfUserHasCookie && self::$hasSessionCookie )
        {
            self::setCookieParams();
            return self::forceStart();
        }
        return null;
    }

    /**
     * Initializes the legacy session system with injected settings.
     * This is method is to be used when Symfony2 "drives" the session, it
     * replaces start/lazyStart in such case.
     *
     * @param string $name Name of the session
     * @param bool $started Whether the session is already started or not
     * @param string $ns Namespace ie the key under which session data should
     *        be put in global array
     */
    static public function init( $name, $started, $ns, ezpSessionHandler $handler )
    {
        if ( self::$hasStarted )
        {
            return;
        }
        self::registerFunctions( $name, $handler );
        self::$namespace = $ns;
        if ( self::$hasSessionCookie && !$started )
        {
            self::forceStart();
        }
        else if ( $started )
        {
            self::$hasStarted = true;
        }
    }

    /**
     * See {@link eZSession::start()}
     *
     * @see ezpSessionHandler::sessionStart()
     * @since 4.4
     * @return true
     */
    static protected function forceStart()
    {
        return self::$hasStarted = self::getHandlerInstance()->sessionStart();
    }

    /**
     * Writes session data and stops the session, if not already stopped.
     *
     * @since 4.1
     * @return bool Depending on if session was stopped.
     */
    static public function stop()
    {
        if ( !self::$hasStarted )
        {
             return false;
        }
        session_write_close();
        self::$hasStarted = false;
        self::$handlerInstance = null;
        return true;
    }

    /**
     * Will make sure the user gets a new session ID while keepin the session data.
     * This is useful to call on logins, to avoid sessions theft from users.
     * NOTE: make sure you set new user id first using {@link eZSession::setUserID()}
     *
     * @since 4.1
     * @param bool $updateBackendData set to false to not update session backend with new session id and user id.
     * @return bool Depending on if session was regenerated.
     */
    static public function regenerate( $updateBackendData = true )
    {
        if ( !self::$hasStarted )
        {
             return false;
        }
        if ( !function_exists( 'session_regenerate_id' ) )
        {
            return false;
        }
        if ( headers_sent() )
        {
            if ( PHP_SAPI !== 'cli' )
                eZDebug::writeWarning( 'Could not regenerate session id, HTTP headers already sent.', __METHOD__ );
            return false;
        }

        return self::getHandlerInstance()->regenerate( ($updateBackendData && self::$hasSessionCookie) );
    }

    /**
     * Removes the current session and resets session variables.
     * Note: implicit stops session as well!
     *
     * @since 4.1
     * @return bool Depending on if session was removed.
     */
    static public function remove()
    {
        // CLI scripts can use sessions and in that case session is forced to start.
        // However, Symfony does not handle sessions in CLI (which seems to be normal), so session is never started.
        // Hence check with session_id()
        if ( !self::$hasStarted || session_id() === '' )
        {
             return false;
        }
        $_SESSION = array();
        session_destroy();
        self::$hasStarted = false;
        self::$handlerInstance = null;
        return true;
    }

    /**
     * Sets the current userID used by ezpSessionHandlerDB::write() on shutdown.
     *
     * @since 4.1
     * @param int $userID to use in {@link ezpSessionHandlerDB::write()}
     */
    static public function setUserID( $userID )
    {
        self::$userID = $userID;
    }

    /**
     * Gets the current user id.
     *
     * @since 4.1
     * @return int User id stored by {@link eZSession::setUserID()}
     */
    static public function userID()
    {
        return self::$userID;
    }

    /**
     * Returns if user had session cookie at start of request or not.
     *
     * @since 4.1
     * @return bool|null Null if session is not started yet.
     */
    static public function userHasSessionCookie()
    {
        return self::$hasSessionCookie;
    }

    /**
     * Return value to indicate if session has started or not
     *
     * @since 4.4
     * @return bool
     */
    static public function hasStarted()
    {
        return self::$hasStarted;
    }

    /**
     * Get curren session handler
     *
     * @since 4.4
     * @return ezpSessionHandler
     */
    static public function getHandlerInstance( ezpSessionHandler $handler = null )
    {
        if ( self::$handlerInstance === null )
        {
            if ( $handler === null )
            {
                $ini = eZINI::instance();
                if ( $ini->variable( 'Session', 'Handler' ) !== '' )
                {
                    $optionArray = array(
                        'iniFile'       => 'site.ini',
                        'iniSection'    => 'Session',
                        'iniVariable'   => 'Handler',
                        'handlerParams' => array( self::$hasSessionCookie )
                    );

                    $options = new ezpExtensionOptions( $optionArray );
                    self::$handlerInstance = eZExtension::getHandlerClass( $options );
                }
            }
            else
            {
                self::$handlerInstance = $handler;
            }
            if ( !self::$handlerInstance instanceof ezpSessionHandler )
            {
                self::$handlerInstance = new ezpSessionHandlerPHP( self::$hasSessionCookie );
            }
        }
        return self::$handlerInstance;
    }

    /**
     * Adds a callback function, to be triggered by {@link eZSession::triggerCallback()}
     * when a certain session event occurs.
     * Use: eZSession::addCallback('gc_pre', myCustomGarabageFunction );
     *
     * @since 4.1
     * @deprecated since 4.5, use {@link ezpEvent::getInstance()->attach()} with new events
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
     * @since 4.1
     * @deprecated since 4.5, use {@link ezpEvent::getInstance()->notify()} with new events
     * @param string $type cleanup, gc, destroy, insert and update, pre and post types.
     * @param array $params list of parameters to pass to the callback function.
     * @return bool
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

?>
