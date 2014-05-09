<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

// Set a default time zone if none is given to avoid "It is not safe to rely
// on the system's timezone settings" warnings. The time zone can be overriden
// in config.php or php.ini.
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}

ignore_user_abort( true );
error_reporting ( E_ALL | E_STRICT );

require 'autoload.php';

$kernel = new ezpKernel( new ezpKernelWeb() );
echo $kernel->run()->getContent();
