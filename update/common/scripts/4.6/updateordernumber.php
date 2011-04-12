#!/usr/bin/env php
<?php
/**
 * File containing the order number upgrade script.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package update
 */

require 'autoload.php';

set_time_limit( 0 );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => 'eZ Publish webshop order number update script. '.
                                                       'This script makes sure that the new order ' .
                                                       'number is consistent with old order number. '.
                                                       'See issue http://issues.ez.no/18233.',
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );
$options = $script->getOptions( '', '', array( '-q' => 'Quiet mode' ) );

$cli = eZCLI::instance();

$script->initialize();
$isQuiet = $script->isQuiet();

$script->startup();


$db = eZDB::instance();
$maxResult = $db->arrayQuery( 'SELECT MAX( order_nr ) AS max_order_nr FROM ezorder' );
$maxNubmer = $maxResult[0]['max_order_nr'];

$maxResult = $db->arrayQuery( 'SELECT MAX( id ) AS max_id FROM ezorder_nr_incr' );
$maxID = $maxResult[0]['max_id'];

if( (int)$maxNubmer <= (int)$maxID )
{
    $cli->output( 'The maximum existing order number should be larger than ids in ezorder_nr_incr. Update ingored.' );
}
else
{
$db->query( "INSERT INTO ezorder_nr_incr VALUES( $maxNubmer )" );
$cli->output( 'Order number udpate finished. The maximum order number is ' . $maxNubmer . '. ' );
}
$script->shutdown();
?>