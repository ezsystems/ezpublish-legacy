<?php
/**
 * Autoloader definition for eZ Publish
 *
 * @copyright Copyright (C) 2005-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL
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

function ezpAutoload( $className )
{
    static $ezpClasses = null;
    if ( is_null( $ezpClasses ) )
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
            $ezpClasses = array_merge( $ezpKernelClasses, $ezpExtensionClasses, $ezpTestClasses );
        }
        else if ( $ezpExtensionClasses )
        {
            $ezpClasses = array_merge( $ezpKernelClasses, $ezpExtensionClasses );
        }
        else if ( $ezpTestClasses )
        {
            $ezpClasses = array_merge( $ezpKernelClasses, $ezpTestClasses );
        }
        else
        {
            $ezpClasses = $ezpKernelClasses;
        }

        if ( defined( 'EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE' ) and EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE )
        {
            $ezpKernelOverrideClasses = require 'var/autoload/ezp_override.php';
            $ezpClasses = array_merge( $ezpClasses, $ezpKernelOverrideClasses );
        }
    }

    if ( array_key_exists( $className, $ezpClasses ) )
    {
        require( $ezpClasses[$className] );
    }
}

spl_autoload_register( 'ezpAutoload' );

if ( EZCBASE_ENABLED )
{
    spl_autoload_register( array( 'ezcBase', 'autoload' ) );
}

?>