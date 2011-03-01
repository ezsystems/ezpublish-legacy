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
 * This class takes handles interal kernel events in eZ Publish, aka hooks.
 *
 * @internal
 * @since 4.5.0
 * @version //autogentag//
 * @package kernel
 */
class ezpEvent
{
    /**
     * Contains all registereded callbacks
     *
     * @var array
     */
    static protected $callbacks = array();

    /**
     * Count of listeners, used to generate listener id
     *
     * @var int
     */
    static protected $listenerIdNumber = 0;

    /**
     * This is an all static class (a global class), so constructing is not allowed
     */
    private function __construct()
    {
    }

    /**
     * Subscribe a event listener at run time on demand.
     *
     * @param string $name In the form "content/delete/1" or "content/delete"
     * @param array|string $callback A valid PHP callback {@see http://php.net/manual/en/language.pseudo-types.php#language.types.callback}
     * @return int Listener id, can be used to unsubscribe a listener later {@see unsubscribe()}
     */
    static public function subscribe( $name, $callback )
    {
        $id = self::$listenerIdNumber++;
        if ( !isset( self::$callbacks[$name] ) )
        {
            self::$callbacks[$name] = array( $id => $callback );
        }
        else
        {
            self::$callbacks[$name][$id] = $callback;
        }
        return $id;
    }

    /**
     * Subscribe a list of event listeners, for use by initializing from ini settings using:
     * ezpEvent::subscribeList( $ini->variableArray('Event', 'CallbackList') )
     *
     * @param array $callbackList In the form array( array( '<name>', <callback> ) )
     */
    static public function subscribeList( array $callbackList )
    {
        foreach ( $callbackList as $item )
        {
            self::subscribe( $item[0], $item[1] );
        }
    }

    /**
     * Unsubscribe a event listener by id given when it was added.
     *
     * @param string $name
     * @param int $id
     */
    static public function unsubscribe( $name, $id )
    {
        if ( !isset( self::$callbacks[$name][$id] ) )
        {
            return false;
        }

        unset( self::$callbacks[$name][$id] );
        return false;
    }

    /**
     * Trigger all listeners on an event (depending on return values and $returnOnValue)
     *
     * @param string $name In the form "content/delete/1", "content/delete", "content/read"
     * @param array $params The arguments for the specific event as simple array structure (not hash)
     * @param bool $returnOnValue Will stop callback iteration and return value from callback if it is other then null
     * @return mixed Value accepted (if used at all) as return value depends on event, default is null
     */
    static public function trigger( $name, array $params, $returnOnValue = false )
    {
        if ( !isset( self::$callbacks[$name] ) )
        {
            return null;
        }

        $ret = null;
        foreach( self::$callbacks[$name] as $callback )
        {
            $ret = call_user_func_array( $callback, $params );

            // If some function returns something else then null, then stop & return it
            if ( $returnOnValue && $ret !== null )
              return $ret;
        }
        return $ret;
    }

    /**
     * Resets all listeners
     */
    static public function reset()
    {
        self::$callbacks = array();
        // do not reset $listenerIdNumber to avoid potential issue if some code tries to unsubscribe
        // already removed listener
    }
}

?>
