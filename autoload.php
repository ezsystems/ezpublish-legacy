<?php
/**
 * Autoloader definition for eZ Publish
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
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
    set_include_path( './lib/ezc' . PATH_SEPARATOR . get_include_path() );
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
    protected static $ezpClasses = null;

    public static function autoload( $className )
    {
        if ( self::$ezpClasses === null )
        {
            $ezpKernelClasses = require 'autoload/ezp_kernel.php';
            $ezpExtensionClasses = false;
            $ezpTestClasses = false;

            if ( file_exists( 'var/autoload/ezp_extension.php' ) )
            {
                $ezpExtensionClasses = require 'var/autoload/ezp_extension.php';
            }

            if ( file_exists( 'var/autoload/ezp_tests.php' ) )
            {
                $ezpTestClasses = require 'var/autoload/ezp_tests.php';
            }

            if ( $ezpExtensionClasses and $ezpTestClasses )
            {
                self::$ezpClasses = array_merge( $ezpKernelClasses, $ezpExtensionClasses, $ezpTestClasses );
            }
            else if ( $ezpExtensionClasses )
            {
                self::$ezpClasses = array_merge( $ezpKernelClasses, $ezpExtensionClasses );
            }
            else if ( $ezpTestClasses )
            {
                self::$ezpClasses = array_merge( $ezpKernelClasses, $ezpTestClasses );
            }
            else
            {
                self::$ezpClasses = $ezpKernelClasses;
            }

            if ( defined( 'EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE' ) and EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE )
            {
                // won't work, as eZDebug isn't initialized yet at that time
                // eZDebug::writeError( "Kernel override is enabled, but var/autoload/ezp_override.php has not been generated\nUse bin/php/ezpgenerateautoloads.php -o", 'autoload.php' );
                if ( $ezpKernelOverrideClasses = include 'var/autoload/ezp_override.php' )
                {
                    self::$ezpClasses = array_merge( self::$ezpClasses, $ezpKernelOverrideClasses );
                }
            }
        }

        if ( array_key_exists( $className, self::$ezpClasses ) )
        {
            require( self::$ezpClasses[$className] );
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
        self::$ezpClasses = null;
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