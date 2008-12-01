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

@include "config.php";

$baseEnabled = @include 'ezc/Base/base.php';
if ( !$baseEnabled )
{
    $baseEnabled = @include 'Base/src/base.php';
}
define( 'EZCBASE_ENABLED', $baseEnabled );

function ezpAutoload( $className )
{
    static $ezpClasses = null;
    if ( is_null( $ezpClasses ) )
    {
        $ezpKernelClasses = require 'autoload/ezp_kernel.php';
        $ezpExtensionClasses = require 'var/autoload/ezp_extension.php';

        $ezpTestClasses = @include 'var/autoload/ezp_tests.php';
        if ( $ezpTestClasses )
        {
            $ezpClasses = array_merge( $ezpKernelClasses, $ezpExtensionClasses, $ezpTestClasses );
        }
        else
        {
            $ezpClasses = array_merge( $ezpKernelClasses, $ezpExtensionClasses );
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