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

if ( !defined( 'EZCBASE_ENABLED' ) )
{
    $appName = defined( 'EZP_APP_FOLDER_NAME' ) ? EZP_APP_FOLDER_NAME : 'ezpublish';
    $appFolder = __DIR__ . "/../$appName";
    $legacyVendorDir = __DIR__ . "/vendor";

    // Bundled
    if ( defined( 'EZP_USE_BUNDLED_COMPONENTS' ) ? EZP_USE_BUNDLED_COMPONENTS === true : file_exists( __DIR__ . "/lib/ezc" ) )
    {
        set_include_path( __DIR__ . PATH_SEPARATOR . __DIR__ . "/lib/ezc" . PATH_SEPARATOR . get_include_path() );
        require 'Base/src/base.php';
        $baseEnabled = true;
    }
    // Custom config.php defined
    else if ( defined( 'EZC_BASE_PATH' ) )
    {
        require EZC_BASE_PATH;
        $baseEnabled = true;
    }
    // Composer if in eZ Publish5 context
    else if ( strpos( $appFolder, "{$appName}/../{$appName}" ) === false && file_exists( "{$appFolder}/autoload.php" ) )
    {
        require_once "{$appFolder}/autoload.php";
        $baseEnabled = false;
    }
    // Composer if in eZ Publish legacy context
    else if ( file_exists( "{$legacyVendorDir}/autoload.php" ) )
    {
        require_once "{$legacyVendorDir}/autoload.php";
        $baseEnabled = false;
    }
    // PEAR install
    else
    {
        $baseEnabled = @include 'ezc/Base/base.php';
        if ( !$baseEnabled )
        {
            $baseEnabled = @include 'Base/src/base.php';
        }
    }

    define( 'EZCBASE_ENABLED', $baseEnabled );
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
                    if ( $ezpKernelOverrideClasses = include __DIR__ . '/var/autoload/ezp_override.php' )
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
