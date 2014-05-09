<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
