<?php
/**
 * Trash purge cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

$purgeHandler = new eZScriptTrashPurge( eZCLI::instance() );
$purgeHandler->run();

?>
