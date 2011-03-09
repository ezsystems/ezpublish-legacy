<?php
/**
 * File containing the ezpEvent class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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
     * Constructer
     * In most cases you would want to use {@see getInstance()} instead
     *
     * @param bool $loadGlobalEvents Load global events from ini settings
     */
    public function __construct( $loadGlobalEvents = true )
    {
        if ( $loadGlobalEvents )
        {
            $listeners = eZINI::instance()->variable( 'Event', 'Listeners' );
            foreach ( $listeners as $listener )
            {
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
     * Notify all listeners on an event
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
     * Notify all listeners on an event but stop if any of them return something else then null
     *
     * @param string $name In the form "content/delete/1", "content/delete", "content/read"
     * @param array|string|numeric $value The value you want to let listeners filter
     * @return mixed $value param after being filtered by filters, or unmodified if no filters
     */
    public function filter( $name, $value )
    {
        if ( empty( $this->listeners[$name] ) )
        {
            return $value;
        }

        foreach ( $this->listeners[$name] as $listener )
        {
            $value = call_user_func( $listener, $value );
        }
        return $value;
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
