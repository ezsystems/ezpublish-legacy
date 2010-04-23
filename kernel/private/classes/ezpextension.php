<?php
/**
 * File containing the ezpExtension class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Object representing an eZ Publish extension
 */
class ezpExtension
{
    public $name;

    /**
     * Array of multiton instances (Multiton pattern)
     *
     * @see getInstance
     */
    private static $instances = array();

    /**
     * ezpExtension constructor.
     *
     * @param string $name Name of the extension
     */
    protected function __construct( $name )
    {
        $this->name = $name;
    }

    /**
     * ezpExtension constructor.
     *
     * @see $instances
     *
     * @param string $name Name of the extension
     */
    public static function getInstance( $name )
    {
        if (! isset( self::$instances[$name] ) )
            self::$instances[$name] = new self( $name );

        return self::$instances[$name];
    }

    public function getLoadingOrder()
    {
        $dependencyFile = eZExtension::baseDirectory() . "/$this->name/loading.php";

        if ( ! is_readable( $dependencyFile ) )
            return array();

        return require $dependencyFile;
    }
}
?>
