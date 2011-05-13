<?php
/**
 * File containing ezpCacheStorageClusterOptions
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Option class for the ezpCacheStorageCluster class.
 */
class ezpCacheStorageClusterOptions extends ezcBaseOptions
{
    /**
     * Parent storage options.
     * @var ezcCacheStorageOptions
     */
    protected $storageOptions;

    /**
     * Constructs a new options class.
     *
     * It also sets the default values of the format property
     *
     * @param array(string=>mixed) $options The initial options to set.

     * @throws ezcBasePropertyNotFoundException
     *         If trying to assign a property which does not exist
     * @throws ezcBaseValueException
     *         If the value for the property is incorrect
     */
    public function __construct( $options = array() )
    {
        // @TODO : Define new options if necessary
        $this->storageOptions = new ezcCacheStorageOptions();
        parent::__construct( $options );
    }

    /**
     * Sets an option.
     * This method is called when an option is set.
     *
     * @param string $key  The option name.
     * @param mixed $value The option value.
     * @ignore
     */
    public function __set( $key, $value )
    {
        switch( $key )
        {
            default:
                $this->storageOptions->$key = $value;
                return;
        }

        $this->properties[$key] = $value;
    }

    /**
     * Property get access.
     * Simply returns a given option.
     *
     * @param string $key The name of the option to get.
     * @return mixed The option value.
     * @ignore
     */
    public function __get( $key )
    {
        if ( isset( $this->properties[$key] ) )
        {
            return $this->properties[$key];
        }
        // Delegate
        return $this->storageOptions->$key;
    }

    /**
     * Returns if a option exists.
     *
     * @param string $key Option name to check for.
     * @return void
     * @ignore
     */
    public function __isset( $key )
    {
        // Delegate
        return ( array_key_exists( $key, $this->properties ) || isset( $this->storageOptions->$key ) );
    }
}
?>
