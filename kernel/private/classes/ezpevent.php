<?php
/**
 * File containing the ezpEvent class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * This class handles internal kernel events in eZ Publish, aka hooks.
 *
 * @internal
 * @since 4.5.0
 * @version //autogentag//
 * @package kernel
 */
class ezpEvent
{
    /**
     * Contains all registered listeners (callbacks)
     *
     * @var array
     */
    protected $listeners = array();

    /**
     * Count of listeners, used to generate listener id
     * Global to make sure it's unique.
     *
     * @var int
     */
    protected static $listenerIdNumber = 0;

    /**
     * Holds the instance of this class
     *
     * @var null|ezpEvent
     */
    protected static $instance = null;

    /**
     * Load global events from ini settings or not
     *
     * @var bool
     */
    protected $loadGlobalEvents;

    /**
     * Constructer
     * In most cases you would want to use {@see getInstance()} instead
     *
     * @param bool $loadGlobalEvents Load global events from ini settings
     */
    public function __construct( $loadGlobalEvents = true )
    {
        $this->loadGlobalEvents = $loadGlobalEvents;
    }

    /**
     * Registers the event listeners defined the site.ini files.
     */
    public function registerEventListeners()
    {
        if ( $this->loadGlobalEvents )
        {
            $listeners = eZINI::instance()->variable( 'Event', 'Listeners' );
            foreach ( $listeners as $listener )
            {
                // $listener may be empty if some override logic has been involved
                if ( $listener == "" )
                {
                    continue;
                }

                // format from ini is seperated by @
                list( $event, $callback ) = explode( '@', $listener );
                $this->attach( $event, $callback );
            }
        }
    }

    /**
     * Attach an event listener at run time on demand.
     *
     * @param string $name In the form "content/delete/1" or "content/delete"
     * @param array|string $listener A valid PHP callback {@see http://php.net/manual/en/language.pseudo-types.php#language.types.callback}
     * @return int Listener id, can be used to detach a listener later {@see detach()}
     */
    public function attach( $name, $listener )
    {
        $id = self::$listenerIdNumber++;
        // explode callback if static class string, workaround for PHP < 5.2.3
        if ( is_string( $listener ) && strpos( $listener, '::' ) !== false )
        {
            $listener = explode( '::', $listener );
        }

        $this->listeners[$name][$id] = $listener;
        return $id;
    }

    /**
     * Detach an event listener by id given when it was added.
     *
     * @param string $name
     * @param int $id The unique id given by {@see attach()}
     * @return bool True if the listener has been correctly detached
     */
    public function detach( $name, $id )
    {
        if ( !isset( $this->listeners[$name][$id] ) )
        {
            return false;
        }

        unset( $this->listeners[$name][$id] );
        return true;
    }

    /**
     * Notify all listeners of an event
     *
     * @param string $name In the form "content/delete/1", "content/delete", "content/read"
     * @param array $params The arguments for the specific event as simple array structure (not hash)
     * @return bool True if some listener where called
     */
    public function notify( $name, array $params = array() )
    {
        if ( empty( $this->listeners[$name] ) )
        {
            return false;
        }

        foreach ( $this->listeners[$name] as $listener )
        {
            call_user_func_array( $listener, $params );
        }
        return true;
    }

    /**
     * Call all listeners of an event and allow them to filter (change) first value
     *
     * @param string $name In the form "content/delete/1", "content/delete", "content/read"
     * @param array|string|numeric $value The value you want to let listeners filter
     * @param array|string|numeric $value,... Optional additional values provided to listeners 
     * @return mixed First $value param after being filtered by filters, or unmodified if no filters
     */
    public function filter( $name, $value )
    {
        if ( empty( $this->listeners[$name] ) )
        {
            return $value;
        }

        $params = func_get_args();
        // We delete the first param, which is the name of the filter
        // in order to retrieve only params for the listener
        array_shift( $params );

        foreach ( $this->listeners[$name] as $listener )
        {
            $params[0] = call_user_func_array( $listener, $params );
        }
        return $params[0];
    }

    /**
     * Gets instance
     *
     * @return ezpEvent
     */
    public static function getInstance()
    {
        if ( !self::$instance instanceof ezpEvent )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Resets instance
     */
    public static function resetInstance()
    {
        self::$instance = null;
    }
}

?>
