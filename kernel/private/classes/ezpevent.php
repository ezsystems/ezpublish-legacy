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
     *
     * @var int
     */
    protected $listenerIdNumber = 0;

    /**
     * Holds the instance of this class
     *
     * @var null|ezpEvent
     */
    static protected $instance = null;

    /**
     * This is an all static class (a global class), so constructing is not allowed
     */
    protected function __construct( $loadGlobalEvents = true )
    {
        if ( $loadGlobalEvents )
        {
            $listeners = eZINI::instance()->variable('Event', 'Listeners');
            foreach ( $listeners as $listener => $event )
            {
                if ( $event )
                {
                    $this->attach( $event, $listener );
                }
            }
        }
    }

    /**
     * Attach an event listener at run time on demand.
     *
     * @param string $name In the form "content/delete/1" or "content/delete"
     * @param array|string $listener A valid PHP callback {@see http://php.net/manual/en/language.pseudo-types.php#language.types.callback}
     * @return int Listener id, can be used to unsubscribe a listener later {@see unsubscribe()}
     */
    public function attach( $name, $listener )
    {
        $id = $this->listenerIdNumber++;
        // explode callback if static class string, workaround for PHP < 5.2.3
        if ( is_string( $listener ) && strpos( $listener, '::' ) !== false )
        {
            $listener = explode( '::', $listener );
        }

        if ( !isset( $this->listeners[$name] ) )
        {
            $this->listeners[$name] = array( $id => $listener );
        }
        else
        {
            $this->listeners[$name][$id] = $listener;
        }
        return $id;
    }

    /**
     * Detach an event listener by id given when it was added.
     *
     * @param string $name
     * @param int $id
     */
    public function detach( $name, $id )
    {
        if ( !isset( $this->listeners[$name][$id] ) )
        {
            return false;
        }

        unset( $this->listeners[$name][$id] );
        return false;
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

        foreach( $this->listeners[$name] as $listener )
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

        foreach( $this->listeners[$name] as $listener )
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
    static public function getInstance()
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
    static public function resetInstance()
    {
        self::$instance = null;
    }
}

?>
