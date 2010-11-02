<?php
/**
 * File containing the ezpInvalidTime class.
 *
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 */

/**
 * Special replacement class for eZTime when the time is invalid.
 *
 */

class ezpInvalidTime
{
    public $timestamp = '';
    public $time_of_day = '';
    public $hour = '';
    public $minute = '';
    public $is_valid = false;
    public $Locale = null;
    public $Time = null;

    /**
     * @throws ezcBasePropertyNotFoundException since only defined member variables can be accessed.
     */
    public function __get( $name )
    {
        throw new ezcBasePropertyNotFoundException( $name );
    }

    /**
     * Returns false since only defined member variables can be accessed.
     * @return bool
     */
    public function __isset( $name )
    {
        return false;
    }

    /**
     * @throws ezcBasePropertyNotFoundException since only defined member variables can be accessed.
     */
    public function __set( $name, $value )
    {
        throw new ezcBasePropertyNotFoundException( $name );
    }

    /**
     * @throws ezcBasePropertyNotFoundException since only defined member variables can be accessed.
     */
    public function __unset( $name )
    {
        throw new ezcBasePropertyNotFoundException( $name );
    }
}

?>
