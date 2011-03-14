#!/usr/bin/env php
<?php
/**
 * Trash purge script
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

$script = eZScript::instance(
    array(
        'description' =>
            "Empty eZ Publish trash.\n" .
            "Permanently deletes all objects in the trash.\n" .
            "\n" .
            "./bin/php/trashpurge.php",
        'use-session' => false,
        'use-modules' => false,
        'use-extensions' => true,
    )
);

$script->startup();

$options = $script->getOptions(
    "[iteration-sleep:][iteration-limit:][memory-monitoring]",
    "",
    array(
        'iteration-sleep' => 'Amount of seconds to sleep between each iteration when performing a purge operation, can be a float. Default is one second.',
        'iteration-limit' => 'Amount of items to remove in each iteration when performing a purge operation. Default is 100.',
        'memory-monitoring' => 'If set, memory usage will be logged in var/log/trashpurge.log.'
    )
);

$script->initialize();

$script->setIterationData( '.', '~' );

$purgeHandler = new eZScriptTrashPurge( eZCLI::instance(), false, (bool)$options['memory-monitoring'], $script );

if (
    $purgeHandler->run(
        $options['iteration-limit'] ? (int)$options['iteration-limit'] : null,
        $options['iteration-sleep'] ? (int)$options['iteration-sleep'] : null
    )
)
{
    $script->shutdown();
}
else
{
    $script->shutdown( 1 );
}

?>
