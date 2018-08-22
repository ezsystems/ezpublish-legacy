<?php
/**
 * Autoloader definition for eZ Publish
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

// config.php can set the components path like:
// ini_set( 'include_path', ini_get( 'include_path' ). ':../ezcomponents/trunk' );
// It is also possible to push a custom autoload method to the autoload
// function stack. Remember to check for class prefixes in such a method, if it
// will not serve classes from eZ Publish and eZ Components

if ( file_exists( __DIR__ . '/config.php' ) )
{
    require_once __DIR__ . '/config.php';
}

// Check for EZCBASE_ENABLED, if set we can skip autoloading Zeta Components
if ( !defined( 'EZCBASE_ENABLED' ) )
{
    // Start by setting EZCBASE_ENABLED to avoid recursion
    define( 'EZCBASE_ENABLED', false );

    // If composer autoloader is already present we can skip trying to load it
    if ( class_exists( 'Composer\Autoload\ClassLoader', false ) )
    {
        // do nothing
    }
    // Composer if in eZ Platform context
    else if ( file_exists( __DIR__ . "/../vendor/autoload.php" ) )
    {
        require_once __DIR__ . "/../vendor/autoload.php";
    }
    // Composer if in eZ Publish legacy context
    else if ( file_exists( __DIR__ . "/vendor/autoload.php" ) )
    {
        require_once __DIR__ . "/vendor/autoload.php";
    }
}

// Check if the composer autoloader exists, if so use it to load classes no matter what the above code did
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) && !class_exists('\\Composer\\Autoload\\ClassLoader') )
{
    require __DIR__ . '/vendor/autoload.php';
}

// Check if ezpAutoloader exists because it can be already declared if running in the Symfony context (e.g. CLI scripts)
if ( !class_exists( 'ezpAutoloader', false ) )
{
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
                $ezpKernelClasses = require __DIR__ . '/autoload/ezp_kernel.php';
                $ezpExtensionClasses = false;
                $ezpTestClasses = false;

                if ( file_exists( __DIR__ . '/var/autoload/ezp_extension.php' ) )
                {
                    $ezpExtensionClasses = require __DIR__ . '/var/autoload/ezp_extension.php';
                }

                if ( file_exists( __DIR__ . '/var/autoload/ezp_tests.php' ) )
                {
                    $ezpTestClasses = require __DIR__ . '/var/autoload/ezp_tests.php';
                }

                if ( $ezpExtensionClasses and $ezpTestClasses )
                {
                    self::$ezpClasses = $ezpTestClasses + $ezpExtensionClasses + $ezpKernelClasses;
                }
                else if ( $ezpExtensionClasses )
                {
                    self::$ezpClasses = $ezpExtensionClasses + $ezpKernelClasses;
                }
                else if ( $ezpTestClasses )
                {
                    self::$ezpClasses = $ezpTestClasses + $ezpKernelClasses;
                }
                else
                {
                    self::$ezpClasses = $ezpKernelClasses;
                }

                if ( defined( 'EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE' ) and EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE )
                {
                    // won't work, as eZDebug isn't initialized yet at that time
                    // eZDebug::writeError( "Kernel override is enabled, but var/autoload/ezp_override.php has not been generated\nUse bin/php/ezpgenerateautoloads.php -o", 'autoload.php' );
                    $ezpKernelOverridePath = __DIR__ . '/var/autoload/ezp_override.php';
                    if ( file_exists( $ezpKernelOverridePath ) && $ezpKernelOverrideClasses = include $ezpKernelOverridePath )
                    {
                        self::$ezpClasses = array_merge( self::$ezpClasses, $ezpKernelOverrideClasses );
                    }
                }
            }

            if ( isset( self::$ezpClasses[$className] ) )
            {
                require( __DIR__ . "/" . self::$ezpClasses[$className] );
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
}

if ( EZCBASE_ENABLED )
{
    spl_autoload_register( array( 'ezcBase', 'autoload' ) );
}

?>
