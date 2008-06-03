<?php
/**
 * Autoloader definition for eZ Publish
 *
 * @copyright Copyright (C) 2005-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL
 * @version //autogentag//
 * @filesource
 *
 */

// config.php can set the components path like:
// ini_set( 'include_path', ini_get( 'include_path' ). ':../ezcomponents/trunk' );

if ( file_exists( "config.php" ) )
{
    require "config.php";
}

// require 'Base/src/base.php';
$baseEnabled = @include( 'ezc/Base/base.php' );
if ( !$baseEnabled )
{
    $baseEnabled = @include( 'Base/src/base.php' );
}

define( 'EZCBASE_ENABLED', $baseEnabled );

function __autoload( $className )
{
    static $ezpClasses = null;
    if ( is_null( $ezpClasses ) )
    {
        $ezpKernelClasses = require 'autoload/ezp_kernel.php';
        $ezpExtensionClasses = require 'autoload/ezp_extension.php';
        $ezpClasses = array_merge( $ezpKernelClasses, $ezpExtensionClasses );
    }

    if ( array_key_exists( $className, $ezpClasses ) )
    {
        require( $ezpClasses[$className] );
    }
    elseif ( EZCBASE_ENABLED )
    {
        ezcBase::autoload( $className );
    }
}

?>