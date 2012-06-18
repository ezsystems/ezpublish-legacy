#!/usr/bin/env php
<?php
/**
 * File containing ezexpiretemplateblock.php script.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ Publish Template Block Handler\n" .
                                                        "Allows for easy clearing of Template Block Caches\n" .
                                                        "\n" .
                                                        "Clearing template block cache with name\n" .
                                                        "./bin/php/ezexpiretemplateblock.php --clear-block=<name>\n" .
                                                        "Clearing template block cache for node with name\n" .
                                                        "./bin/php/ezexpiretemplateblock.php --clear-block=<name> --node-id=<node_id>\n" ),
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[node-id:][clear-block:]",
                                "",
                                array( 'node-id' => ( "Clears template block only for given node." ),
                                       'clear-block' => ( "Clear the template block cache with given name." ) ) );
$sys = eZSys::instance();

$script->initialize();

$nodeID = false;

if ( $options['node-id'] )
{
    $nodeID = $options['node-id'];
}

if ( $options['clear-block'] )
{
    $blockName = $options['clear-block'];
    if ( $blockName == '' )
    {
        $cli->output( "No block name given!" );
        $script->shutdown( 0 );
    }
    if ( $nodeID )
    {
        $cli->output( "Clearing cache for template block <" . $blockName . "> and Node <" . $nodeID . ">." );
    }
    else
    {
        $cli->output( "Clearing cache for template block <" . $blockName . ">." );
    }
    eZTemplateCacheBlock::expireCacheByName( $blockName, $nodeID);
}
else
{
    $cli->output( "No block name given!" );
}


$script->shutdown( 1 );

?>
