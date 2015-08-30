<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

require 'autoload.php';

set_time_limit( 0 );

$cli = eZCLI::instance();
$script = eZScript::instance(
    array(
        'description' => 'Script responsible for changing the number of characters the password_hash column, in ezuser table',
    )
);

$script->initialize();
$script->startup();

$db = eZDB::instance();

$rows = $db->query( "ALTER TABLE `ezuser` CHANGE COLUMN `password_hash` `password_hash` VARCHAR(255) NULL DEFAULT NULL ;" );

$cli->output( ' Done' );
