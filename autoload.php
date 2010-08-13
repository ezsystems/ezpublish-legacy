<?php
/**
 * Autoloader definition for eZ Publish
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

// config.php can set the components path like:
// ini_set( 'include_path', ini_get( 'include_path' ). ':../ezcomponents/trunk' );
// It is also possible to push a custom autoload method to the autoload
// function stack. Remember to check for class prefixes in such a method, if it
// will not serve classes from eZ Publish and eZ Components

if ( file_exists( 'config.php' ) )
{
    require 'config.php';
}

$useBundledComponents = defined( 'EZP_USE_BUNDLED_COMPONENTS' ) ? EZP_USE_BUNDLED_COMPONENTS === true : file_exists( 'lib/ezc' );
if ( $useBundledComponents )
{
    set_include_path( '.' . PATH_SEPARATOR . './lib/ezc' . PATH_SEPARATOR . get_include_path() );
    require 'Base/src/base.php';
    $baseEnabled = true;
}
else if ( defined( 'EZC_BASE_PATH' ) )
{
    require EZC_BASE_PATH;
    $baseEnabled = true;
}
else
{
    $baseEnabled = @include 'ezc/Base/base.php';
    if ( !$baseEnabled )
    {
        $baseEnabled = @include 'Base/src/base.php';
    }
}

define( 'EZCBASE_ENABLED', $baseEnabled );

/**
 * Provides the native autoload functionality for eZ Publish
 *
 * @package kernel
 */
class ezpAutoloader
{
    protected static $ezpOverrideClasses = null;
    protected static $ezpKernelClasses = null;
    protected static $ezpExtensionsClasses = null;
    protected static $ezpTestsClasses = null;

    public static function autoload( $className )
    {
        if ( defined( 'EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE' ) && EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE )
        {
            if ( self::$ezpOverrideClasses === null )
            {
                self::$ezpOverrideClasses = require "var/autoload/ezp_override.php";
            }

            if ( isset( self::$ezpOverrideClasses[$className] ) )
            {
                require self::$ezpOverrideClasses[$className];
                return;
            }
        }

        if ( self::$ezpKernelClasses === null )
        {
            self::$ezpKernelClasses = require "autoload/ezp_kernel.php";
        }

        if ( isset( self::$ezpKernelClasses[$className] ) )
        {
            require self::$ezpKernelClasses[$className];
            return;
        }

        if ( self::$ezpExtensionsClasses === null )
        {
            self::$ezpExtensionsClasses = require "var/autoload/ezp_extension.php";
        }

        if ( isset( self::$ezpExtensionsClasses[$className] ) )
        {
            require self::$ezpExtensionsClasses[$className];
            return;
        }

        if ( self::$ezpTestsClasses === null )
        {
            if ( file_exists( "var/autoload/ezp_tests.php" ) )
            {
                self::$ezpTestsClasses = require "var/autoload/ezp_tests.php";
            }
            else
            {
                // Disabling tests classes loading
                self::$ezpTestsClasses = false;
            }
        }

        if ( isset( self::$ezpTestsClasses[$className] ) )
        {
            require self::$ezpTestsClasses[$className];
        }
    }

    /**
     * Resets the local, in-memory autoload cache.
     *
     * If the autoload arrays are extended during a requests lifetime, this
     * method must be called, to make them available.
     *
     * @return void
     */
    public static function reset()
    {
        self::$ezpOverrideClasses = null;
        self::$ezpKernelClasses = null;
        self::$ezpExtensionsClasses = null;
        self::$ezpTestsClasses = null;
    }

    public static function updateExtensionAutoloadArray()
    {
        $autoloadGenerator = new eZAutoloadGenerator();
        try
        {
            $autoloadGenerator->buildAutoloadArrays();

            self::reset();
        }
        catch ( Exception $e )
        {
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
        }
    }
}

spl_autoload_register( array( 'ezpAutoloader', 'autoload' ) );

if ( EZCBASE_ENABLED )
{
    spl_autoload_register( array( 'ezcBase', 'autoload' ) );
}

?>
