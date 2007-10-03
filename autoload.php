<?php
/**
 * Autoloader definition for eZ Publish
 *
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL
 * @version //autogentag//
 * @filesource
 *
 */

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
        $ezpKernelClasses = require 'autoloads/ezp_kernel.php';
        $ezpExtensionClasses = require 'autoloads/ezp_extension.php';
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