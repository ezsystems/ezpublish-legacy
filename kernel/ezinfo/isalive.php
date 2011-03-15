<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

header( "Content-Type: text/plain;" );

$db = eZDB::instance();

if ( $db->isConnected() === true )
    print( "eZ Publish is alive" );
else
    print( "No connection" );

eZExecution::cleanExit();
?>
