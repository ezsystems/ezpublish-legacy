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
    /**
     * @var array|null
     */
    protected static $ezpClasses = null;

    /**
     * @var string
     */
    const CACHE_FILE = 'var/cache/autoload.php';

    /**
     * Autoload eZ Publish, extension classes and lazy load ezcBase autoloader if needed
     *
     * @param  $className
     * @return bool|mixed
     */
    public static function autoload( $className )
    {
        // Load class list array from cache or generate + save if it is not loaded
        if ( self::$ezpClasses === null )
        {
            if ( file_exists( self::CACHE_FILE ) )
            {
                self::$ezpClasses = include self::CACHE_FILE;
            }
            else
            {
                self::$ezpClasses = self::saveClassesCache( self::generateClassesList() );
            }
        }

        // Load class by autoload array
        if ( isset( self::$ezpClasses[$className] ) )
        {
            return require( self::$ezpClasses[$className] );
        }
    }

    /**
     * Merges all autoload files and return result
     *
     * @return array
     */
    public static function generateClassesList()
    {

        $ezpClasses = require 'autoload/ezp_kernel.php';
        $ezpTestClasses = array();
        $ezpExtensionClasses = array();
        $ezpKernelOverrideClasses = array();

        if ( file_exists( 'var/autoload/ezp_extension.php' ) )
        {
            $ezpExtensionClasses = require 'var/autoload/ezp_extension.php';
        }

        if ( file_exists( 'var/autoload/ezp_tests.php' ) )
        {
            $ezpTestClasses = require 'var/autoload/ezp_tests.php';
        }

        if ( defined( 'EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE' ) and EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE )
        {
            if ( file_exists( 'var/autoload/ezp_override.php' ) )
            {
                $ezpKernelOverrideClasses = require 'var/autoload/ezp_override.php';
            }
        }

        return array_merge( $ezpClasses, $ezpExtensionClasses, $ezpTestClasses, $ezpKernelOverrideClasses );
    }

    /**
     * Save autoload cache file for override classes.
     *
     * @param array $classes
     * @return array
     */
    protected static function saveClassesCache( $classes )
    {
        try
        {
            $generator = new ezcPhpGenerator( self::CACHE_FILE );
            $generator->appendComment( "This is auto generated hash of autoload override classes!" );
            $generator->appendValueAssignment( 'classes', $classes );
            $generator->appendCustomCode( 'return $classes;' );
            $generator->finish();
        }
        catch ( Exception $e )
        {
            // constructor     : ezcBaseFileNotFoundException or ezcBaseFilePermissionException
            // all other calls : ezcPhpGeneratorException
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
        }
        return $classes;
    }

    /**
     * Delete autoload cache file for override classes.
     *
     * @return bool
     */
    protected static function deleteClassesCache()
    {
        if ( file_exists( self::CACHE_FILE ) )
        {
            return unlink( self::CACHE_FILE );
        }
        return false;
    }

    /**
     * Resets the local, in-memory autoload cache.
     *
     * If the autoload arrays are extended during a requests lifetime, this
     * method must be called, to make them available.
     *
     * @param bool $clearFileCache Also clear on disk autoload file cache.
     * @return void
     */
    public static function reset( $clearFileCache = true )
    {
        self::$ezpClasses = null;
        if ( $clearFileCache )
        {
            self::deleteClassesCache();
        }
    }

    /**
     * Shortcut to regenerate autoload files, also takes care of refreshing autoload cache
     */
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
